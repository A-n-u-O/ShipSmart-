<?php
session_start();
require_once 'db_connection.php';

// Initialize error messages array
$error_messages = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup_date = trim($_POST['pickup_date'] ?? '');
    $pickup_time = trim($_POST['pickup_time'] ?? '');
    $pickup_address = trim($_POST['pickup_address'] ?? '');
    $delivery_location = trim($_POST['delivery_location'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $item_description = trim($_POST['item_description'] ?? '');
    $item_weight = trim($_POST['item_weight'] ?? '');

    // Validate inputs
    if (empty($pickup_date)) {
        $error_messages['pickup_date'] = 'Pickup date is required.';
    } else {
        // Validate date
        $today = new DateTime();
        $selected_date = new DateTime($pickup_date);
        if ($selected_date < $today) {
            $error_messages['pickup_date'] = 'Pickup date cannot be in the past.';
        } elseif ($selected_date > (clone $today)->modify('+3 months')) {
            $error_messages['pickup_date'] = 'Pickup date cannot be more than 3 months in the future.';
        }
    }

    if (empty($pickup_time)) {
        $error_messages['pickup_time'] = 'Pickup time is required.';
    } else {
        // Validate time (9 AM to 5 PM)
        $hour = (int)explode(':', $pickup_time)[0];
        if ($hour < 9 || $hour >= 17) {
            $error_messages['pickup_time'] = "Please select a time between 9:00 AM and 5:00 PM.";
        }
    }

    if (empty($pickup_address)) {
        $error_messages['pickup_address'] = 'Pickup address is required.';
    }
    
    if (empty($delivery_location)) {
        $error_messages['delivery_location'] = 'Delivery address is required.';
    }
    
    if (empty($phone_number)) {
        $error_messages['phone_number'] = 'Phone number is required.';
    } elseif (!preg_match('/^(?:\+234|0)\d{10}$/', $phone_number)) {
        $error_messages['phone_number'] = 'Invalid phone number format. Use +234XXXXXXXXXX or 0XXXXXXXXXX.';
    }
    
    if (empty($item_description)) {
        $error_messages['item_description'] = 'Item description is required.';
    } elseif (str_word_count($item_description) < 3) {
        $error_messages['item_description'] = 'Item description must be at least 3 words.';
    }

    if (empty($item_weight)) {
        $error_messages['item_weight'] = 'Item weight is required.';
    } elseif (!is_numeric($item_weight) || $item_weight <= 0) {
        $error_messages['item_weight'] = 'Item weight must be a positive number.';
    }

    // If there are no validation errors, proceed to insert booking
    if (empty($error_messages)) {
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

            // Redirect to choose courier page
            header('Location: chooseCourier.php');
            exit();
        } catch (Exception $e) {
            // Store error message in the session
            $_SESSION['error_message'] = "Error processing your request: " . htmlspecialchars($e->getMessage());
        }
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
<header>
<p>Schedule Pickup</p>
</header>

<main class="schedule-pickup-form">
<?php if (!empty($error_messages)): ?>
<div class="error-messages">
<ul>
<?php foreach ($error_messages as $message): ?>
<li><?= htmlspecialchars($message) ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<form action="schedulePickup.php" method="POST" class="scheduling-form">
<div class="inputs">
<!-- Input fields for pickup details -->
<div id="pickup_date_input" class="form-input">
<label for="pickup_date">Pickup Date</label>
<input type="date" id="pickup_date" name="pickup_date" required>
<span id="error_date" class="error-message"></span> <!-- Error message for date -->
</div>

<div id="pickup_time_input" class="form-input">
<label for="pickup_time">Pickup Time</label>
<input type="time" id="pickup_time" name="pickup_time" required>
<span id="error_time" class="error-message"></span> <!-- Error message for time -->
</div>

<div id="pickup_address_input" class="form-input">
<label for="pickup_address">Pickup Address</label>
<textarea id="pickup_address" name="pickup_address" required></textarea>
<span id="error_pickup_address" class="error-message"></span> <!-- Error message for pickup address -->
</div>

<div id="delivery_location_input" class="form-input">
<label for="delivery_location">Delivery Address</label>
<textarea id="delivery_location" name="delivery_location" required></textarea>
<span id="error_delivery_location" class="error-message"></span> <!-- Error message for delivery location -->
</div>

<div id="phone_number_input" class="form-input">
<label for="phone_number">Phone Number</label>
<input type="tel" id="phone_number" name="phone_number" required placeholder="+234XXXXXXXXXX">
<span id="error_phone_number" class="error-message"></span> <!-- Error message for phone number -->
</div>

<div id="item_weight_input" class="form-input">
<label for="item_weight">Item Weight (kg)</label>
<input type="number" id="item_weight" name="item_weight" required step=".1">
<span id='error_item_weight' class='error-message'></span> <!-- Error message for item weight -->
</div>

<div id='item_description_input' class='form-input'>
<label for='item_description'>Item Description</label>
<input type='text' id='item_description' name='item_description' required placeholder='At least 3 words'>
<span id='error_item_description' class='error-message'></span> <!-- Error message for item description -->
</div>
</div>

<button type='submit' id='confirm-pickup-btn' class='confirm-pickup-btn'>Choose Courier</button>
</form>
</main>

<script src="../js/schedulePickup.js"></script>
</body>
</html>