<?php
// Start the session if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch current booking details from session
$current_booking = $_SESSION['current_booking'] ?? null;

if (!$current_booking) {
    // Redirect to schedulePickup.php if no booking is found
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
    $_SESSION['error_message'] = "Error fetching courier details: " . htmlspecialchars($e->getMessage());
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

        // Clear current booking from session
        unset($_SESSION['current_booking']);
        header('Location: pickupConfirmation.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error confirming pickup: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Schedule Pickup | Confirm Pickup</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/confirmPickup.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="confirm-pickup">
        <!-- Display Error Message -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert-message"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <h2>Confirm Pickup Details</h2>
        <div class="pickup-details">
            <h2>Pickup Details</h2>
            <p><strong>Pickup Date:</strong> <?= htmlspecialchars($current_booking['pickup_date']); ?></p>
            <p><strong>Pickup Time:</strong> <?= htmlspecialchars($current_booking['pickup_time']); ?></p>
            <p><strong>Pickup Address:</strong> <?= htmlspecialchars($current_booking['pickup_address'] ?? 'N/A'); ?></p>
            <p><strong>Delivery Address:</strong> <?= htmlspecialchars($current_booking['delivery_location'] ?? 'N/A'); ?></p>
            <p><strong>Phone Number:</strong> <?= htmlspecialchars($current_booking['phone_number'] ?? 'N/A'); ?></p>
            <p><strong>Item Description:</strong> <?= htmlspecialchars($current_booking['item_description'] ?? 'N/A'); ?></p>
            <p><strong>Item Weight:</strong> <?= htmlspecialchars($current_booking['item_weight'] ?? 'N/A'); ?> kg</p>
        </div>

        <div class="courier-details">
            <h2>Selected Courier</h2>
            <p>Name: <?= htmlspecialchars($courier['first_name'] . ' ' . $courier['last_name']); ?></p>
            <p>Phone: <?= htmlspecialchars($courier['contact_info']); ?></p>
            <p>Availability Time: <?= htmlspecialchars($courier['available_time']); ?></p>
        </div>

        <!-- Confirmation Form -->
        <form method="POST" action="confirmPickup.php">
            <button type="submit" class="confirm-btn" id="confirm-btn">Confirm Pickup</button>
            <a href="chooseCourier.php" id="edit-btn">Choose Another Courier</a>
        </form>
    </main>

    <script src="../js/confirmPickup.js" defer></script>
</body>
</html>