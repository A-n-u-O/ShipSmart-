<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debugging: Log the request method
error_log("Request method: " . $_SERVER['REQUEST_METHOD']);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging: Log POST data
    error_log("POST data: " . print_r($_POST, true));

    // Check if the logout confirmation was received
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        // Debugging: Log the logout confirmation
        error_log("User  confirmed logout.");

        // Log out the user
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session

        // Redirect to the landing page in the views folder
        header("Location: ./views/landingPage.php");
        exit;
    } else {
        // Debugging: Log the cancel action
        error_log("User  canceled logout.");

        // If the user cancels the logout, redirect to the dashboard in the views folder
        header("Location: ./views/dashboard.php");
        exit;
    }
} else {
    // Debugging: Log direct access to logout.php
    error_log("Accessed logout.php directly.");

    // If accessed directly, redirect to the logout confirmation page in the pages folder
    header("Location: ./pages/logoutPage.php");
    exit;
}
?>