<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Announcements</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/announcements.css">
</head>

<body>
    <?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->
    <div class="container">
        <h1>Announcements</h1>
        <p>Stay updated with the latest news, updates, and offers from ShipSmart.</p>

        <div class="announcement-list">
            <div class="announcement-card">
                <h3>System Update</h3>
                <p>We are introducing new features to improve your experience. Stay tuned for more details.</p>
                <small>Posted on: December 1, 2024</small>
            </div>
            <div class="announcement-card">
                <h3>Maintenance Schedule</h3>
                <p>Our platform will undergo scheduled maintenance on December 5, 2024. Services may be temporarily unavailable.</p>
                <small>Posted on: November 30, 2024</small>
            </div>
            <div class="announcement-card">
                <h3>New Year Offer</h3>
                <p>Celebrate the new year with 20% off on all shipments. Offer valid from January 1–7, 2025.</p>
                <small>Posted on: November 28, 2024</small>
            </div>
        </div>
    </div>
</body>

</html>