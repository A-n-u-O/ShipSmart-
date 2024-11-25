<?php
session_start();
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session
header("Location: ../Views/landingPage.php"); // Redirect to the landing page
exit; // Ensure no further code is executed