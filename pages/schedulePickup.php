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

    try {
        // Insert the booking into the database
        $stmt = $pdo->prepare("INSERT INTO Bookings (user_id, pickup_date, pickup_time, pickup_location, delivery_location, phone_number, item_description, item_weight, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->execute([$_SESSION['user_id'], $pickup_date, $pickup_time, $pickup_address, $delivery_location, $phone_number, $item_description, $item_weight]);

        // Fetch the last inserted booking ID
        $booking_id = $pdo->lastInsertId();

        // Store booking details and ID in the session
        $_SESSION['current_booking'] = [
            'booking_id' => $booking_id,
            'pickup_date' => $pickup_date,
            'pickup_time' => $pickup_time,
            'pickup_address' => $pickup_address,
            'delivery_location' => $delivery_location,
            'phone_number' => $phone_number,
            'item_description' => $item_description,
            'item_weight' => $item_weight,
        ];

        // Redirect to choose courier page (confirm the booking)
        header('Location: chooseCourier.php');
        exit();
    } catch (Exception $e) {
        // Store error message in the session
        $_SESSION['error_message'] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Pickup</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/schedulePickup.css">
</head>

<body>
    <?php include '../Views/navbar.php'; ?>
    <header>Schedule Pickup</header>

    <main class="schedule-pickup-form">

        <?php if (!empty($error_messages)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($error_messages as $message): ?>
                        <li><?= sanitizeOutput($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="schedulePickup.php" method="POST" class="scheduling-form">
            <div class="inputs">
                <div id="pickup_date_input" class="form-input">
                    <label for="pickup_date">Pickup Date</label>
                    <input type="date" id="pickup_date" name="pickup_date" required>
                </div>

                <div id="pickup_time_input" class="form-input">
                    <label for="pickup_time">Pickup Time</label>
                    <input type="time" id="pickup_time" name="pickup_time" required>
                </div>

                <div id="pickup_address_input" class="form-input">
                    <label for="pickup_address">Pickup Address</label>
                    <textarea id="pickup_address" name="pickup_address" required></textarea>
                </div>

                <div id="delivery_location_input" class="form-input">
                    <label for="delivery_location">Delivery Address</label>
                    <textarea id="delivery_location" name="delivery_location" required></textarea>
                </div>

                <div id="phone_number_input" class="form-input">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" required placeholder="Enter phone number">
                </div>

                <div id="item_weight_input" class="form-input">
                    <label for="item_weight">Item Weight (kg)</label>
                    <input type="number" id="item_weight" name="item_weight" required step="0.1">
                </div>

                <div id="item_description_input" class="form-input">
                    <label for="item_description">Item Description</label>
                    <input type="text" id="item_description" name="item_description" required>
                </div>
            </div>


            <button type="submit" id="confirm-pickup-btn" class="confirm-pickup-btn">Schedule Pickup</button>
        </form>
    </main>
    <script src="../js/schedulePickup.js"></script>
</body>

</html>