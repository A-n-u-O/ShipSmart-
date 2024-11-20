<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user_email']); ?>!</h1>
    <p>You are now logged in.</p>
    <a href="../logout.php">Logout</a>
</body>
</html>
