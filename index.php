<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the dashboard
    header("Location: views/dashboardMain.php");
}
 else {
    // Redirect to the login page
    header("Location: views/login.php");
    exit;
}
?>
