<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Updates</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/updates.css">
</head>

<body>
    <?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->
    <div class="container">
        <h1>System Updates</h1>
        <p>Discover the latest improvements and features added to ShipSmart.</p>

        <div class="updates-list">
            <div class="update-card">
                <h3>New Dashboard Features</h3>
                <p>We’ve introduced quick stats and shortcuts for enhanced user experience on the dashboard.</p>
                <small>Released on: December 1, 2024</small>
            </div>
            <div class="update-card">
                <h3>Improved Tracking System</h3>
                <p>Our real-time shipment tracking is now faster and more accurate than ever.</p>
                <small>Released on: November 25, 2024</small>
            </div>
            <div class="update-card">
                <h3>Security Enhancements</h3>
                <p>Your data security is our priority. We’ve implemented advanced encryption and firewall updates.</p>
                <small>Released on: November 20, 2024</small>
            </div>
        </div>
    </div>
</body>

</html>
