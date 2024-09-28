<?php
$host = 'localhost';
$user = 'root';  
$pass = '';      
$db = 'todo_db';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $db");
    $pdo->exec("USE $db");

    
    $createUsersTable = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )";
    $pdo->exec($createUsersTable);

    
    $createTasksTable = "
        CREATE TABLE IF NOT EXISTS tasks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            task TEXT NOT NULL,
            user_id INT,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";
    $pdo->exec($createTasksTable);

    echo "Database and tables created successfully!";
} catch (PDOException $e) {
    die("Error creating database and tables: " . $e->getMessage());
}
?>
