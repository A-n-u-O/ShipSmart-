<?php
session_start();
require_once 'db_connection.php';

// Initialize error messages array
$error_messages = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup_date = $_POST['pickup_date'] ?? '';
    $pickup_time = $_POST['pickup_time'] ?? '';
    $pickup_address = $_POST['pickup_address'] ?? '';
    $delivery_location = $_POST['delivery_location'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $item_description = $_POST['item_description'] ?? '';
    $item_weight = $_POST['item_weight'] ?? '';

    // Validate inputs
    if (empty($pickup_date)) {
        $error_messages['pickup_date'] = 'Pickup date is required.';
    }
    if (empty($pickup_time)) {
        $error_messages['pickup_time'] = 'Pickup time is required.';
    }
    if (empty($pickup_address)) {
        $error_messages['pickup_address'] = 'Pickup address is required.';
    }
    if (empty($delivery_location)) {
        $error_messages['delivery_location'] = 'Delivery address is required.';
    }
    if (empty($phone_number)) {
        $error_messages['phone_number'] = 'Phone number is required.';
    }
    if (empty($item_description)) {
        $error_messages['item_description'] = 'Item description is required.';
    }
    if (empty($item_weight)) {
        $error_messages['item_weight'] = 'Item weight is required.';
    }

    // If there are no errors, proceed with database insertion
    if (empty($error_messages)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO Bookings (user_id, pickup_date, pickup_time, pickup_location, delivery_location, phone_number, item_description, item_weight, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
            $stmt->execute([$_SESSION['user_id'], $pickup_date, $pickup_time, $pickup_address, $delivery_location, $phone_number, $item_description, $item_weight]);

            // Set the current booking in session
            $_SESSION['current_booking'] = [
                'pickup_date' => $pickup_date,
                'pickup_time' => $pickup_time,
                'pickup_address' => $pickup_address,
                'delivery_location' => $delivery_location,
                'phone_number' => $phone_number,
                'item_description' => $item_description,
                'item_weight' => $item_weight,
            ];

            header('Location: confirmPickup.php');
            exit();
        } catch (Exception $e) {
            $error_messages['general'] = 'An error occurred while scheduling the pickup. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Schedule Pickup</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/schedulePickup.css">
</head>

<body>
    <?php include '../Views/navbar.php'; ?>
    <main class="schedule-pickup">
        <div class="company-info">
            <h2>ShipSmart Shipping</h2>
            <p>Logistics support | Mon-Fri, 9:00 AM - 5:00 PM</p>
        </div>
        <div class="schedule-pickup-container">
            <?php if (isset($error_messages['general'])): ?>
                <div class="alert-message"><?= $error_messages['general']; ?></div>
            <?php endif; ?>
            <form method="POST" action="schedulePickup.php">
                <label>Pickup Date</label>
                <input type="date" id="pickup_date" name="pickup_date" required>
                <div class="error-message"><?= $error_messages['pickup_date'] ?? ''; ?></div>

                <label>Pickup Time</label>
                <input type="time" id="pickup_time" name="pickup_time" required>
                <div class="error-message" id="error_time"><?= $error_messages['pickup_time'] ?? ''; ?></div>

                <label>Pickup Address</label>
                <input type="text" id="pickup_address" name="pickup_address" placeholder="Enter pickup address" required>
                <div class="error-message"><?= $error_messages['pickup_address'] ?? ''; ?></div>

                <label>Delivery Address</label>
                <input type="text" id="delivery_location" name="delivery_location" placeholder="Enter delivery address" required>
                <div class="error-message"><?= $error_messages['delivery_location'] ?? ''; ?></div>

                <label>Phone Number</label>
                <input type="tel" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>
                <div class="error-message"><?= $error_messages['phone_number'] ?? ''; ?></div>

                <label>Item Description</label>
                <input type="text" id="item_description" name="item_description" placeholder="Describe the item(s)" required>
                <div class="error-message"><?= $error_messages['item_description'] ?? ''; ?></div>

                <label>Item Weight (kg)</label>
                <input type="number" id="item_weight" name="item_weight" placeholder="Enter item weight" step="0.1" min="0" required>
                <div class="error-message"><?= $error_messages['item_weight'] ?? ''; ?></div>

                <button type="submit" class="confirm-pickup-btn" id="confirm-pickup-btn">Confirm Pickup</button>
            </form>
        </div>
    </main>
    <script src="../js/schedulePickup.js" defer></script>
</body>
</html>