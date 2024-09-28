<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tasks);
exit();
?>
