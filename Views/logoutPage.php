<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: landingPage.php");
    exit;
}

// Handle logout confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    } elseif (isset($_POST['confirm']) && $_POST['confirm'] === 'no') {
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
</head>
<body>
    <div>
        <h2>Are you sure you want to log out?</h2>
        <form action="logoutPage.php" method="POST">
            <button type="submit" name="confirm" value="yes">Yes, Log Out</button>
            <button type="submit" name="confirm" value="no">Cancel</button>
        </form>
    </div>
</body>
</html>
