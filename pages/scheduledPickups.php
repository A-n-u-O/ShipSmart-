<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user's scheduled pickups
try {
    $stmt = $pdo->prepare("
        SELECT b.booking_id, b.pickup_date, b.pickup_time, b.pickup_location, b.status, 
               s.tracking_number, s.current_status
        FROM Bookings b
        LEFT JOIN Shipments s ON b.booking_id = s.booking_id
        WHERE b.user_id = ?
        ORDER BY b.pickup_date DESC, b.pickup_time DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $pickups = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    $pickups = [];
}
?>

<!-- HTML for scheduledPickups.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Pickups</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/scheduledPickups.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="scheduled-pickups">
        <h1>Your Scheduled Pickups</h1>
        
        <?php if (!empty($pickups)): ?>
            <div class="pickup-list">
                <?php foreach ($pickups as $pickup): ?>
                    <div class="pickup-item">
                        <h3>Pickup on <?= htmlspecialchars($pickup['pickup_date']) ?></h3>
                        <p>Time: <?= htmlspecialchars($pickup['pickup_time']) ?></p>
                        <p>Address: <?= htmlspecialchars($pickup['pickup_location']) ?></p>
                        <p>Booking Status: <?= htmlspecialchars($pickup['status']) ?></p>
                        <?php if (!empty($pickup['tracking_number'])): ?>
                            <p>Tracking Number: <?= htmlspecialchars($pickup['tracking_number']) ?></p>
                            <p>Shipment Status: <?= htmlspecialchars($pickup['current_status']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p id="scheduled-details">No pickups scheduled yet.</p>
        <?php endif; ?>

        <button id="edit-schedule-btn" onclick="window.location.href='editPickupDetails.php'">Edit Pickup Details</button>
    </main>
</body>
</html>