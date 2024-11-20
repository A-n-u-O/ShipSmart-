<?php
session_start();
include '../config/database.php'; 
include '../includes/functions.php'; // Include the redirect function

// Handle POST request for signup
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            redirect('signup.php?error=Email already registered.');
        }

        // Insert user into database
        $stmt = $pdo->prepare("INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Redirect to login page on successful registration
        redirect('login.php?success=Registration successful. Please log in.');
    } catch (PDOException $e) {
        redirect('signup.php?error=Failed to register. Try again later.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - ShipSmart</title>
    <link href="../public/css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="p-5 shadow rounded" style="width: 100%; max-width: 400px;">
        <form method="POST" action="signup.php">
            <h2 class="text-center mb-4">Sign Up</h2>

            <!-- Error Display -->
            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_GET['error']); ?>
                </div>
            <?php } ?>

            <!-- Success Display -->
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_GET['success']); ?>
                </div>
            <?php } ?>

            <!-- Username Field -->
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>

            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>

            <!-- Password Field -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>

            <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>
