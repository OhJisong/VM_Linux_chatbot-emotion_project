<?php
ob_start(); // 출력 버퍼 시작 (header 오류 방지)

session_start();
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nickname'] = $user['nickname'];
        header('Location: index.php');
        exit;
    } else {
        echo "❌ 로그인 실패! 아이디나 비밀번호를 확인하세요.<br><br>";
    }
}
?>

<form method="POST">
  아이디: <input type="text" name="username" required><br><br>
  비밀번호: <input type="password" name="password" required><br><br>
  <button type="submit">로그인</button>
</form>
<a href="register.php">계정이 없으신가요? 회원가입</a>

<?php ob_end_flush(); // 출력 버퍼 종료 ?>

