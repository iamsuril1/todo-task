<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connect.php';

    $task = $_POST['task'];
    $stmt = $pdo->prepare("INSERT INTO tasks (task, user_id) VALUES (:task, :user_id)");
    $stmt->execute(['task' => $task, 'user_id' => $_SESSION['user_id']]);

    echo json_encode(['success' => true]);
    exit();
}
?>
