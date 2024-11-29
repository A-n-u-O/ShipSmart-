<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ./views/landingPage.php"); // Redirect to the landing page in the views folder
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation - ShipSmart</title>
    <link rel="stylesheet" href="../css/logoutPage.css">
    <script src="../js/logoutPage.js" defer></script> <!-- Include JS if needed -->
</head>

<body>
    <div class="logout-container">
        <h2>Logout Confirmation</h2>
        <p>Are you sure you want to log out? If you log out, you will need to log in again to access your account.</p>
        
        <form action="logout.php" method="POST">
            <input type="hidden" name="confirm" value="yes">
            <button type="submit" class="logout-button">Yes, Log Out</button>
        </form>

        <form action="./views/dashboard.php" method="GET"> <!-- Redirecting to the dashboard in views folder -->
            <button type="submit" class="cancel-button">Cancel</button>
        </form>

        <button class="back-button" onclick="history.back()">&#8592; Back</button>
    </div>
</body>

</html>