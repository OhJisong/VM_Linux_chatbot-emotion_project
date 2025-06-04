<?php
session_start();
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT id, nickname, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nickname'] = $user['nickname'];
        header("Location: index.php");
        exit;
    } else {
        $error = "아이디 또는 비밀번호가 잘못되었습니다.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>로그인</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>로그인</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="아이디" required>
            <input type="password" name="password" placeholder="비밀번호" required>
            <button class="btn" type="submit">로그인</button>
        </form>
        <p style="margin-top:10px;">아직 계정이 없다면? <a href="register.php">회원가입</a></p>
    </div>
</body>
</html>

