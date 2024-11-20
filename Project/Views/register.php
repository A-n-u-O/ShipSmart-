<?php  
session_start();

// Redirect if user is already logged in
if (isset($_SESSION['user_id']) || isset($_SESSION['user_email'])) {
    header("Location: index.php"); // Redirect to dashboard if logged in
    exit;
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "shipsmart");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: register.php?error=All fields are required.");
        exit;
    }

    // Check if email already exists
    $emailCheck = $conn->prepare("SELECT * FROM Users WHERE email = ?");
    $emailCheck->bind_param("s", $email);
    $emailCheck->execute();
    $result = $emailCheck->get_result();
    if ($result->num_rows > 0) {
        header("Location: register.php?error=Email is already registered.");
        $emailCheck->close();
        $conn->close();
        exit;
    }
    $emailCheck->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $stmt = $conn->prepare("INSERT INTO Users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute and handle results
    if ($stmt->execute()) {
        header("Location: login.php?success=Registration successful! Please log in.");
    } else {
        header("Location: register.php?error=Registration failed. Please try again.");
    }

    // Close resources
    $stmt->close();
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ShipSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <form class="p-5 rounded shadow" style="max-width: 30rem; width: 100%" method="POST" action="register.php">
            <h1 class="text-center display-4 pb-5">REGISTER</h1>

            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_GET['error']); ?>
                </div>
            <?php } ?>
            
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_GET['success']); ?>
                </div>
            <?php } ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
            <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>
