<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ./landingPage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <link rel="stylesheet" href="../css/logoutPage.css">
    <script src="../js/logoutPage.js" defer></script>
</head>

<body>
    <div class="logout-container">
        <h2>Are you sure you want to log out?</h2>
        <p>If you log out, you will need to log in again to access your account.</p>
        <form action="logout.php" method="POST">
            <button type="submit">Yes, Log Out</button>
            <a href="../Views/landingPage.php" class="cancel-button">Cancel</a>
        </form>
        <button class="back-button" onclick="confirmBack()">&#8592; Back</button>
    </div>
</body>

</html>