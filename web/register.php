<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nickname = $_POST['nickname'];

    $pdo = getDbConnection();

    // 아이디 중복 확인
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        echo "❌ 이미 존재하는 아이디입니다!";
        exit;
    }

    // 사용자 정보 저장
    $stmt = $pdo->prepare("INSERT INTO users (username, password, nickname) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $nickname]);

    echo "✅ 회원가입이 완료되었습니다. <a href='login.php'>로그인하러 가기</a>";
    exit;
}
?>

<h2>회원가입</h2>
<form method="POST">
  아이디: <input type="text" name="username" required><br><br>
  비밀번호: <input type="password" name="password" required><br><br>
  닉네임: <input type="text" name="nickname" required><br><br>
  <button type="submit">가입하기</button>
</form>
<a href="login.php">이미 계정이 있으신가요? 로그인</a>

