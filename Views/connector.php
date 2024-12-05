<?php
try {
    // Create a connection to the database
    $pdo = new PDO('mysql:host=localhost;dbname=ShipSmart', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
