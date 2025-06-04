<?php
session_start();
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $nickname = trim($_POST['nickname']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $pdo = getDbConnection();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        $error = "이미 존재하는 아이디입니다.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, nickname, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $nickname, $password]);
        header("Location: register_success.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>회원가입</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="아이디" required>
            <input type="text" name="nickname" placeholder="닉네임" required>
            <input type="password" name="password" placeholder="비밀번호" required>
            <button class="btn" type="submit">가입하기</button>
        </form>
        <p style="margin-top:10px;">이미 계정이 있나요? <a href="login.php">로그인</a></p>
    </div>
</body>
</html>

