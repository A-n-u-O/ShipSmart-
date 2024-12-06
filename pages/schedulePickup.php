<?php
session_start();
require_once 'db_connection.php'; // Ensure this file contains the PDO connection setup

// Initialize error messages array
$error_messages = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
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
        $today = new DateTime();
        $maxDate = (clone $today)->modify('+3 months');
        $selectedDate = new DateTime($pickup_date);
        
        // Check if the selected date is a weekend
        if ($selectedDate->format('N') >= 6) {
            $error_messages['pickup_date'] = 'Pickup date cannot be on a weekend.';
        } elseif ($selectedDate < $today) {
            $error_messages['pickup_date'] = 'Pickup date cannot be in the past.';
        } elseif ($selectedDate > $maxDate) {
            $error_messages['pickup_date'] = 'Pickup date cannot be more than 3 months in the future.';
        }
    }

    if (empty($pickup_time)) {
        $error_messages['pickup_time'] = 'Pickup time is required.';
    } else {
        // Validate working hours (9 AM to 5 PM)
        $time = DateTime::createFromFormat('H:i', $pickup_time);
        if ($time < DateTime::createFromFormat('H:i', '09:00') || $time > DateTime::createFromFormat('H:i', '17:00')) {
            $error_messages['pickup_time'] = 'Pickup time must be between 9 AM and 5 PM.';
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
            $_SESSION['error_message'] = "Error scheduling pickup: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/schedulePickup.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?>
    <header>Schedule a Pickup</header>

    <header>
        <div class="company-info">
            <h1>Your Company Name</h1>
        </div>
    </header>

    <main>
        <div class="scheduling-form fade-in">
            <h2>Schedule a Pickup</h2>
            <form id="pickup-form" method="POST" action="schedulePickup.php">
                <div class="inputs">
                    <div class="form-input">
                        <label for="pickup_date">Pickup Date:</label>
                        <input type="date" name="pickup_date" id="pickup_date" required>
                        <?php if (isset($error_messages['pickup_date'])): ?>
                            <div class="error"><?= htmlspecialchars($error_messages['pickup_date']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-input">
                        <label for="pickup_time">Pickup Time:</label>
                        <input type="time" name="pickup_time" id="pickup_time" required>
                        <?php if (isset($error_messages['pickup_time'])): ?>
                            <div class="error"><?= htmlspecialchars($error_messages['pickup_time']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-input">
                        <label for="pickup_address">Pickup Address:</label>
                        <input type="text" name="pickup_address" id="pickup_address" required>
                        <?php if (isset($error_messages['pickup_address'])): ?>
                            <div class="error"><?= htmlspecialchars($error_messages['pickup_address']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-input">
                        <label for="delivery_location">Delivery Location:</label>
                        <input type="text" name="delivery_location" id="delivery_location" required>
                        <?php if (isset($error_messages['delivery_location'])): ?>
                            <div class="error"><?= htmlspecialchars($error_messages['delivery_location']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-input">
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" name="phone_number" id="phone_number" required>
                        <?php if (isset($error_messages['phone_number'])): ?>
                            <div class="error"><?= htmlspecialchars($error_messages['phone_number']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-input">
                        <label for="item_description">Item Description:</label>
                        <textarea name="item_description" id="item_description" required></textarea>
                        <?php if (isset($error_messages['item_description'])): ?>
                            <div class="error"><?= htmlspecialchars($error_messages['item_description']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-input">
                        <label for="item_weight">Item Weight (kg):</label>
                        <input type="number" name="item_weight" id="item_weight" required>
                        <?php if (isset($error_messages['item_weight'])): ?>
                            <div class="error"><?= htmlspecialchars($error_messages['item_weight']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <button type="submit" class="confirm-pickup-btn">Confirm Pickup</button>
            </form>
        </div>
    </main>
    <script src="../js/schedulePickup.js" defer></script>
</body>
</html>