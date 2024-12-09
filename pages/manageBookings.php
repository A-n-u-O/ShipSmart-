<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch completed bookings
try {
    $stmt = $pdo->prepare("
        SELECT b.booking_id, b.pickup_date, b.pickup_time, b.pickup_location, b.status,
               s.tracking_number, s.current_status, 
               c.first_name AS courier_first_name, c.last_name AS courier_last_name, 
               c.contact_info AS courier_phone
        FROM Bookings b
        LEFT JOIN Shipments s ON b.booking_id = s.booking_id
        LEFT JOIN Couriers c ON b.courier_id = c.courier_id
        WHERE b.user_id = ? AND b.status = 'Pickup'
        ORDER BY b.pickup_date DESC, b.pickup_time DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching bookings: " . $e->getMessage();
    $bookings = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShipSmart | Manage Bookings</title>
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/manageBookings.css">
</head>

<body>
  <?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->

  <header>Manage Bookings</header>

  <main class="manage-bookings">
    <?php
    // Display error messages if any
    if (!empty($_SESSION['error_message'])): ?>
        <div class="error-message">
            <?= htmlspecialchars($_SESSION['error_message']); ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (!empty($bookings)): ?>
        <div class="booking-list">
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-item">
                    <h4>Booking ID: <?= htmlspecialchars($booking['booking_id']); ?></h4>
                    <p><strong>Date:</strong> <?= htmlspecialchars($booking['pickup_date']); ?></p>
                    <p><strong>Time:</strong> <?= htmlspecialchars($booking['pickup_time']); ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($booking['pickup_location']); ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($booking['status']); ?></p>
                    <?php if ($booking['tracking_number']): ?>
                        <p><strong>Tracking Number:</strong> <?= htmlspecialchars($booking['tracking_number']); ?></p>
                        <p><strong>Shipment Status:</strong> <?= htmlspecialchars($booking['current_status']); ?></p>
                    <?php endif; ?>

                    <?php if ($booking['courier_first_name']): ?>
                        <h4>Courier Information</h4>
                        <p>Name: <?= htmlspecialchars($booking['courier_first_name'] . ' ' . $booking['courier_last_name']); ?></p>
                        <p>Phone: <?= htmlspecialchars($booking['courier_phone']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No completed bookings found.</p>
    <?php endif; ?>
  </main>

</body>

</html>
