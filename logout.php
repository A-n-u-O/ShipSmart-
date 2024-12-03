<?php
session_start();

// Destroy all sessions
session_unset();
session_destroy();

// Redirect to landing page
header("Location: ./views/landingPage.php");
exit();
?>