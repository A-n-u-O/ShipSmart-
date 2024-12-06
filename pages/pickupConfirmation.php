<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch the booking details from the session
$current_booking = $_SESSION['current_booking'] ?? null;

// If no current booking is found, redirect to the scheduling page
if (!$current_booking) {
    header('Location: schedulePickup.php');
    exit();
}

// Clear the session data after confirmation
unset($_SESSION['current_booking']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Pickup Confirmation</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/pickupConfirmation.css">
</head>

<body>
    <?php include '../Views/navbar.php'; ?> <!-- Navbar -->
    <!-- Confirmation Summary -->

    <main class="confirmation">
        <h2>Pickup Confirmed</h2>

        <div class="confirmation-summary">
            <p><strong>Pickup Date:</strong> <?= htmlspecialchars($current_booking['pickup_date']); ?></p>
            <p><strong>Pickup Time:</strong> <?= htmlspecialchars($current_booking['pickup_time']); ?></p>
            <p><strong>Pickup Address:</strong> <?= htmlspecialchars($current_booking['pickup_address']); ?></p>
            <p><strong>Delivery Address:</strong> <?= htmlspecialchars($current_booking['delivery_location']); ?></p>
            <p><strong>Phone Number:</strong> <?= htmlspecialchars($current_booking['phone_number']); ?></p>
            <p><strong>Item Description:</strong> <?= htmlspecialchars($current_booking['item_description']); ?></p>
            <p><strong>Item Weight:</strong> <?= htmlspecialchars($current_booking['item_weight']); ?> kg</p>
        </div>
        <!-- Confirmation Message -->

        <p>Your pickup has been confirmed! You will be notified once your shipment is in transit.</p>

        <a href="dashboard.php" class="back-to-dashboard">Go to Dashboard</a>
    </main>

</body>

</html>