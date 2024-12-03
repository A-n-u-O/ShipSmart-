<?php
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    // Database connection file
    include "../config/database.php";

    // Sanitize input
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // Query to fetch user data
        $sql = "SELECT * FROM Users WHERE email = ?"; // Query by email
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        // Check if user exists
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch user data as associative array
            $user_id = $user['user_id'];
            $user_email = $user['email'];
            $user_password = $user['password']; // Hashed password
            $username = $user['username']; // Fetch username

            // Verify the password
            if (password_verify($password, $user_password)) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $user_email;
                $_SESSION['username'] = $username; // Store username in session

                // After successful login, set the login message
                $_SESSION['login_message'] = "You are now logged in.";

                // Redirect to dashboard
                header("Location: ../views/dashboard.php");
                exit;
            } else {
                // Invalid password
                $error = "Incorrect email or password.";
                header("Location: ../views/login.php?error=" . urlencode($error));
                exit;
            }
        } else {
            // User does not exist
            $error = "Incorrect email or password.";
            header("Location: ../views/login.php?error=" . urlencode($error));
            exit;
        }
    } catch (PDOException $e) {
        // Database error
        $error = "Server error, try again later.";
        header("Location: ../views/login.php?error=" . urlencode($error));
        exit;
    }
} else {
    // Redirect if accessed without POST request
    header("Location: ../");
    exit;
}