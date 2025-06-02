<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "로그인이 필요합니다."]);
    exit;
}

$pdo = getDbConnection();
$stmt = $pdo->prepare("SELECT emotion, COUNT(*) as count FROM messages WHERE user_id = ? AND emotion IS NOT NULL GROUP BY emotion");
$stmt->execute([$_SESSION['user_id']]);
$results = $stmt->fetchAll();

$data = [];
foreach ($results as $row) {
    $data[$row['emotion']] = (int)$row['count'];
}

header('Content-Type: application/json');
echo json_encode($data);
?>

