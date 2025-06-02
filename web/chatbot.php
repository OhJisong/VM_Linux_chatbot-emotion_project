<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["error" => "로그인이 필요합니다."]);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $userMessage = trim($_POST['message']);

    // 1. 감정 분석 (PHP 7.x 호환)
    function analyzeEmotion($text) {
        $text = mb_strtolower($text); // 한글 소문자화
        if (mb_strpos($text, '기뻐') !== false || mb_strpos($text, '좋아') !== false || mb_strpos($text, '행복') !== false) return '기쁨';
        if (mb_strpos($text, '슬퍼') !== false || mb_strpos($text, '울고') !== false || mb_strpos($text, '외로') !== false) return '슬픔';
        if (mb_strpos($text, '짜증') !== false || mb_strpos($text, '화나') !== false || mb_strpos($text, '열받') !== false) return '분노';
        if (mb_strpos($text, '불안') !== false || mb_strpos($text, '무서워') !== false || mb_strpos($text, '떨려') !== false) return '불안';
        return '중립';
    }

    $emotion = analyzeEmotion($userMessage);

    // 2. 챗봇 응답
    switch ($emotion) {
        case '기쁨':
            $reply = "와~ 너 오늘 기분 좋아 보여! 🤗";
            break;
        case '슬픔':
            $reply = "무슨 일이 있었는지 말해줘도 괜찮아... 내가 들어줄게.";
            break;
        case '분노':
            $reply = "많이 화났겠구나... 무슨 일 있었는지 말해봐.";
            break;
        case '불안':
            $reply = "요즘 마음이 복잡한가 보다… 괜찮아, 내가 옆에 있을게.";
            break;
        default:
            $reply = "음, 계속 얘기해볼까?";
    }

    // 3. DB 저장
    $pdo = getDbConnection();

    // 사용자 메시지 저장
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, role, message, emotion) VALUES (?, 'user', ?, ?)");
    $stmt->execute([$userId, $userMessage, $emotion]);

    // 챗봇 응답 저장
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, role, message, emotion) VALUES (?, 'bot', ?, ?)");
    $stmt->execute([$userId, $reply, $emotion]);

    // 4. 턴 수 체크
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE user_id = ? AND role = 'user'");
    $stmt->execute([$userId]);
    $turns = $stmt->fetchColumn();

    $done = false;
    $music = '';

    // 5. 음악 추천 (5턴 이상 시)
    if ($turns >= 5) {
        $done = true;

        $musicMap = [
            '기쁨' => "https://youtu.be/hTWKbfoikeg",
            '슬픔' => "https://youtu.be/ho9rZjlsyYY",
            '분노' => "https://youtu.be/rYEDA3JcQqw",
            '불안' => "https://youtu.be/2Vv-BfVoq4g",
            '중립' => "https://youtu.be/j5-yKhDd64s"
        ];

        // 이전 추천 감정 확인
        $stmt = $pdo->prepare("SELECT emotion, music_url FROM last_recommend WHERE user_id = ?");
        $stmt->execute([$userId]);
        $prev = $stmt->fetch();

        if ($prev && $prev['emotion'] === $emotion) {
            $music = $prev['music_url'];
            $reply .= " 지난번에도 이 감정일 때 이 노래 들었잖아~ 다시 들어볼래?";
        } else {
            $music = $musicMap[$emotion] ?? "https://music.youtube.com/";
            $stmt = $pdo->prepare("REPLACE INTO last_recommend (user_id, emotion, music_url) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $emotion, $music]);
        }

       // session_destroy(); // 5턴 대화 후 초기화
    }

    echo json_encode([
        'reply' => $reply,
        'done' => $done,
        'music' => $music
    ]);
}
?>

