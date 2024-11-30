<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and has a current booking
if (!isset($_SESSION['user_id']) || !isset($_SESSION['current_booking'])) {
    header('Location: schedulePickup.php');
    exit();
}

// Handle booking confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $booking_id = $_SESSION['current_booking']['booking_id'];

        // Update booking status to confirmed
        $stmt = $pdo->prepare("UPDATE Bookings SET status = 'Confirmed' WHERE booking_id = ? AND user_id = ?");
        $stmt->execute([$booking_id, $_SESSION['user_id']]);

        // Create a shipment record
        $tracking_number = 'SS' . str_pad($booking_id, 8, '0', STR_PAD_LEFT);
        $stmt = $pdo->prepare("INSERT INTO Shipments (booking_id, tracking_number, current_status) VALUES (?, ?, 'Pending')");
        $stmt->execute([$booking_id, $tracking_number]);

        // Clear the current booking from session
        unset($_SESSION['current_booking']);

        // Redirect to scheduled pickups
        header('Location: scheduledPickups.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Pickup</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/confirmPickup.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?> <!-- Include reusable navbar -->

    <main class="confirm-pickup">
        <h1>Confirm Your Pickup</h1>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <div class="pickup-details">
            <p>Date: <?= htmlspecialchars($_SESSION['current_booking']['pickup_date']); ?></p>
            <p>Time: <?= htmlspecialchars($_SESSION['current_booking']['pickup_time']); ?></p>
            <p>Address: <?= htmlspecialchars($_SESSION['current_booking']['pickup_address']); ?></p>
        </div>

        <form method="POST" action="confirmPickup.php">
            <button type="submit" id="confirm-btn">Confirm Pickup</button>
            <a href="schedulePickup.php" id="edit-btn">Edit Pickup</a>
        </form>
    </main>
</body>
</html>
