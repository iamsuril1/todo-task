<?php
session_start();
include 'db_connect.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit();
    } else {
        $login_error = "Invalid username or password!";
    }
}

if (isset($_POST['signup'])) {
    $signup_username = $_POST['signup_username'];
    $signup_password = password_hash($_POST['signup_password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    try {
        $stmt->execute(['username' => $signup_username, 'password' => $signup_password]);
        $signup_success = "Account created successfully! You can now log in.";
    } catch (PDOException $e) {
        $signup_error = "Username already exists!";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

$tasks = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Todo List</title>
</head>
<body>
    <div class="container">
        <h1>Todo List Application</h1>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="auth-container">
                <div class="login-section">
                    <h2>Login</h2>
                    <form method="POST">
                        <input type="text" name="username" placeholder="Username" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit" name="login">Login</button>
                    </form>
                    <div class="error"><?= isset($login_error) ? $login_error : ''; ?></div>
                </div>

                <div class="vertical-line"></div>

                <div class="signup-section">
                    <h2>Sign Up</h2>
                    <form method="POST">
                        <input type="text" name="signup_username" placeholder="Username" required>
                        <input type="password" name="signup_password" placeholder="Password" required>
                        <button type="submit" name="signup">Sign Up</button>
                    </form>
                    <div class="success"><?= isset($signup_success) ? $signup_success : ''; ?></div>
                    <div class="error"><?= isset($signup_error) ? $signup_error : ''; ?></div>
                </div>
            </div>
        <?php else: ?>
            <div class="task-manager">
                <h2>Welcome, <?= htmlspecialchars($username) ?>!</h2>
                <input type="text" id="taskInput" placeholder="Add a new task...">
                <button id="addTaskButton">Add Task</button>
                <ul id="taskList">
                    <?php foreach ($tasks as $task): ?>
                        <li class="task-item" data-id="<?= $task['id'] ?>">
                            <?= htmlspecialchars($task['task']) ?>
                            <button class="deleteTaskButton">Delete</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="?logout=true" class="logout-link">Logout</a>
            </div>
        <?php endif; ?>
    </div>
    <script src="script.js"></script>
</body>
</html>
