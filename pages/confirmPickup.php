<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and has a current booking
if (!isset($_SESSION['user_id']) || !isset($_SESSION['current_booking'])) {
    header('Location: schedulePickup.php');
    exit();
}

// Ensure the courier is selected
if (!isset($_SESSION['current_booking']['courier_id'])) {
    header('Location: chooseCourier.php');
    exit();
}

try {
    // Fetch the selected courier details
    $courier_id = $_SESSION['current_booking']['courier_id'];
    $stmt = $pdo->prepare("SELECT * FROM Couriers WHERE courier_id = ?");
    $stmt->execute([$courier_id]);
    $courier = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$courier) {
        throw new Exception("The selected courier is no longer available. Please choose another courier.");
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: chooseCourier.php');
    exit();
}

// Handle booking confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $booking_id = $_SESSION['current_booking']['booking_id'];

        // Update booking status to confirmed
        $stmt = $pdo->prepare("UPDATE Bookings SET status = 'Confirmed', courier_id = ? WHERE booking_id = ? AND user_id = ?");
        $stmt->execute([$courier_id, $booking_id, $_SESSION['user_id']]);

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
        
        <!-- Display Error Message -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <div class="pickup-details">
            <h2>Pickup Details</h2>
            <p>Date: <?= htmlspecialchars($_SESSION['current_booking']['pickup_date']); ?></p>
            <p>Time: <?= htmlspecialchars($_SESSION['current_booking']['pickup_time']); ?></p>
            <p>Address: <?= htmlspecialchars($_SESSION['current_booking']['pickup_address']); ?></p>
        </div>

        <div class="courier-details">
            <h2>Selected Courier</h2>
            <p>Name: <?= htmlspecialchars($courier['first_name'] . ' ' . $courier['last_name']); ?></p>
            <p>Phone: <?= htmlspecialchars($courier['phone_number']); ?></p>
            <p>Availability Time: <?= htmlspecialchars($courier['available_time']); ?></p>
        </div>

        <form method="POST" action="confirmPickup.php">
            <button type="submit" id="confirm-btn">Confirm Pickup</button>
            <a href="chooseCourier.php" id="edit-btn">Choose Another Courier</a>
        </form>
    </main>
</body>
</html>
