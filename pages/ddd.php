<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle pickup scheduling submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get user input
        $pickup_date = $_POST['pickup_date'];
        $pickup_time = $_POST['pickup_time'];
        $pickup_address = $_POST['pickup_address'];
        $user_id = $_SESSION['user_id'];

        // Validate input
        if (empty($pickup_date) || empty($pickup_time) || empty($pickup_address)) {
            throw new Exception('All fields are required.');
        }

        // Prepare SQL to insert booking
        $stmt = $pdo->prepare("INSERT INTO Bookings (user_id, pickup_date, pickup_time, pickup_location, status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->execute([$user_id, $pickup_date, $pickup_time, $pickup_address]);

        // Get the last inserted booking ID
        $booking_id = $pdo->lastInsertId();

        // Store booking details in session for confirmation page
        $_SESSION['current_booking'] = [
            'booking_id' => $booking_id,
            'pickup_date' => $pickup_date,
            'pickup_time' => $pickup_time,
            'pickup_address' => $pickup_address,
        ];

        // Redirect to confirmation page
        header('Location: confirmPickup.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: schedulePickup.php');
        exit();
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
    <?php include '../Views/navbar.php'; ?> <!-- Include reusable navbar -->

    <main class="schedule-pickup">
        <h1>Schedule a Pickup</h1>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <form method="POST" action="schedulePickup.php">
            <label for="pickup_date">Pickup Date:</label>
            <input type="date" id="pickup_date" name="pickup_date" required>

            <label for="pickup_time">Pickup Time:</label>
            <input type="time" id="pickup_time" name="pickup_time" required>

            <label for="pickup_address">Pickup Address:</label>
            <input type="text" id="pickup_address" name="pickup_address" placeholder="Enter address" required>

            <button type="submit" id="continue-btn">Continue</button>
        </form>
    </main>
</body>
</html>
