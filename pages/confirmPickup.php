<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$current_booking = $_SESSION['current_booking'] ?? null;

if (!$current_booking) {
    // Redirect to schedulePickup.php if no booking is found
    header('Location: schedulePickup.php');
    exit();
}

// Handle form submission for confirming the pickup
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("UPDATE Bookings 
                               SET status = 'Confirmed', 
                                   updated_at = NOW() 
                               WHERE user_id = ? AND pickup_date = ? AND pickup_time = ?");
        $stmt->execute([$_SESSION['user_id'], $current_booking['pickup_date'], $current_booking['pickup_time']]);

        unset($_SESSION['current_booking']);
        header('Location: pickupConfirmation.php');
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
    <title>ShipSmart | Schedule Pickup | Confirm Pickup</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/confirmPickup.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="confirm-pickup">
        <div class="company-info">
            <h2>ShipSmart Shipping</h2>
            <p>Logistics support | Mon-Fri, 8:30-11:00 AM</p>
        </div>

        <div class="confirm-pickup-container">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert-message"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>

            <h2>Confirm Pickup Details</h2>
            <div class="pickup-summary">
                <p><strong>Pickup Date:</strong> <?= htmlspecialchars($current_booking['pickup_date']); ?></p>
                <p><strong>Pickup Time:</strong> <?= htmlspecialchars($current_booking['pickup_time']); ?></p>
                <p><strong>Pickup Address:</strong> <?= htmlspecialchars($current_booking['pickup_address'] ?? 'N/A'); ?></p>
                <p><strong>Delivery Address:</strong> <?= htmlspecialchars($current_booking['delivery_location'] ?? 'N/A'); ?></p>
                <p><strong>Phone Number:</strong> <?= htmlspecialchars($current_booking['phone_number'] ?? 'N/A'); ?></p>
                <p><strong>Item Description:</strong> <?= htmlspecialchars($current_booking['item_description'] ?? 'N/A'); ?></p>
                <p><strong>Item Weight:</strong> <?= htmlspecialchars($current_booking['item_weight'] ?? 'N/A'); ?> kg</p>
            </div>

            <form method="POST" action="confirmPickup.php">
                <button type="submit" class="confirm-btn" id="confirm-btn">Confirm Pickup</button>
            </form>
        </div>
    </main>
    <script src="../js/confirmPickup.js" defer></script>
</body>
</html>