<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connect.php';

    $taskId = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $taskId, 'user_id' => $_SESSION['user_id']]);

    echo json_encode(['success' => true]);
    exit();
}
?>
