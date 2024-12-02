<?php
session_start();
require_once 'db_connection.php';

// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle pickup scheduling submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pickup_date = $_POST['pickup_date'];
        $pickup_time = $_POST['pickup_time'];
        $pickup_address = $_POST['pickup_address'];
        $phone_number = $_POST['phone_number']; // New field
        $item_description = $_POST['item_description']; // New field
        $item_weight = $_POST['item_weight']; // New field
        $user_id = $_SESSION['user_id'];

        if (empty($pickup_date) || empty($pickup_time) || empty($pickup_address) || empty($phone_number) || empty($item_description) || empty($item_weight)) {
            throw new Exception('All fields are required.');
        }

        $stmt = $pdo->prepare("INSERT INTO Bookings (user_id, pickup_date, pickup_time, pickup_location, phone_number, item_description, item_weight, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->execute([$user_id, $pickup_date, $pickup_time, $pickup_address, $phone_number, $item_description, $item_weight]);

        $_SESSION['current_booking'] = [
            'pickup_date' => $pickup_date,
            'pickup_time' => $pickup_time,
            'pickup_address' => $pickup_address,
            'phone_number' => $phone_number,
            'item_description' => $item_description,
            'item_weight' => $item_weight,
        ];

        header('Location: confirmPickup.php');
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
    <title>ShipSmart | Schedule Pickup</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/schedulePickup.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?> <!-- Navbar -->

    <main class="schedule-pickup">
        <div class="company-info">
            <div class="company-profile">
                <img src="../Assets/images/Shipsmart-icon-light.png" alt="company logo" class="company-logo">
                <h2>ShipSmart Shipping</h2>
                <div class="company-details">
                    <p>Logistics support</p>
                    <p>Mon-Fri, 8:30-11:00 AM</p>
                </div>
            </div>
        </div>

        <div class="schedule-pickup-container">
            <!-- Display error messages -->
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert-message"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>

            <form method="POST" action="schedulePickup.php">
                <div class="detail-choosing">
                    <div class="pickup-date">
                        <h3>Pickup Date</h3>
                        <input type="date" id="pickup_date" name="pickup_date" required>
                    </div>

                    <div class="time-slots">
                        <h3>Pickup Time</h3>
                        <input type="time" id="pickup_time" name="pickup_time" required>
                    </div>

                    <div class="pickup-address">
                        <h3>Pickup Address</h3>
                        <input type="text" id="pickup_address" name="pickup_address" placeholder="Enter pickup address" autocomplete="off" required>
                    </div>

                    <div class="phone-number">
                        <h3>Phone Number</h3>
                        <input type="text" id="phone_number" name="phone_number" placeholder="Enter your phone number" autocomplete="off" required>
                    </div>

                    <div class="item-description">
                        <h3>Item Description</h3>
                        <input type="text" id="item_description" name="item_description" placeholder="Describe the item(s)" autocomplete="off" required>
                    </div>

                    <div class="item-weight">
                        <h3>Item Weight (kg)</h3>
                        <input type="number" id="item_weight" name="item_weight" placeholder="Enter item weight" step="0.1" min="0" required>
                    </div>
                </div>

                <div class="pickup-details">
                    <button type="submit" class="confirm-pickup-btn">Confirm Pickup</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
