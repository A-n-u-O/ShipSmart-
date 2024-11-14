<?php
session_start();
include '../config/connector.php'; // Ensure the path is correct for your ShipSmart setup

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connect to the ShipSmart database and retrieve user data
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Store user session data
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_email'] = $user['email'];
        header("Location: ../Views/index.php"); // Redirect to dashboard or index page
    } else {
        header("Location: ../Views/login.php?error=Invalid email or password");
    }
} else {
    header("Location: ../Views/login.php?error=Please enter email and password");
}
?>
