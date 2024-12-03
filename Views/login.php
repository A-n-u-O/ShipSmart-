<?php
session_start();
include '../config/database.php'; // Include the database connection file
include '../includes/functions.php'; // Include the redirect function

// Check if the user is already logged in and redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    redirect('dashboard.php'); // Redirect to dashboard page
}

// Handle login logic if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email and password
    $stmt = $pdo->prepare("SELECT user_id, name, email, password FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Store the user data in the session
        $_SESSION['user_id'] = $user['user_id'];  // Store user_id in session
        $_SESSION['username'] = $user['name'];    // Store username (name) in session
        $_SESSION['user_email'] = $user['email']; // Store email in session

        // Redirect to dashboard
        redirect('dashboard.php');
    } else {
        $error = "Invalid credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ShipSmart</title>
    <link href="../css/login.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="p-5 shadow rounded" style="max-width: 400px; width: 100%;">
            <form method="POST" action="login.php">
                <h2 class="text-center mb-4">Login</h2>

                <!-- Display error message if login failed -->
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php } ?>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
                <p class="text-center mt-3">Don't have an account? <a href="signup.php">Sign up here</a></p>
            </form>
        </div>
    </div>
</body>

</html>