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
        <h2>Logout Confirmation</h2>
        <p>Are you sure you want to log out? If you log out, you will need to log in again to access your account.</p>
        
<<<<<<< HEAD
        <form action="../logout.php" method="POST">
=======
        <form action="logout.php" method="POST">
>>>>>>> tomisin
            <input type="hidden" name="confirm" value="yes">
            <button type="submit" class="logout-button">Yes, Log Out</button>
        </form>

<<<<<<< HEAD
        <form action="../Views/dashboard.php" method="GET"> <!-- Redirecting to the dashboard in views folder -->
=======
        <form action="./views/dashboard.php" method="GET"> <!-- Redirecting to the dashboard in views folder -->
>>>>>>> tomisin
            <button type="submit" class="cancel-button">Cancel</button>
        </form>

        <button class="back-button" onclick="history.back()">&#8592; Back</button>
<<<<<<< HEAD
<!--         <h2>Are you sure you want to log out?</h2>
        <p>If you log out, you will need to log in again to access your account.</p>
        <form action="logout.php" method="POST">
            <button type="submit">Yes, Log Out</button>
            <a href="../Views/landingPage.php" class="cancel-button">Cancel</a>
        </form>
        <button class="back-button" onclick="confirmBack()">&#8592; Back</button> -->
=======
>>>>>>> tomisin
    </div>
</body>

</html>