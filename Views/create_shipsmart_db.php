<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "shipsmart";

try {
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the new database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "Database 'shipsmart' created successfully.<br>";

    // Switch to the new database
    $pdo->exec("USE $dbname");

    // Create the Users table
    $createUsersTable = "CREATE TABLE IF NOT EXISTS Users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $pdo->exec($createUsersTable);
    echo "Table 'Users' created successfully.<br>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
