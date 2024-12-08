<?php
session_start();
require_once 'db_connection.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM Bookings WHERE user_id = ? ORDER BY booking_id DESC LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $pickup = $stmt->fetch();

    if (!$pickup) {
        header('Location: schedulePickup.php');
        exit();
    }
} catch (Exception $e) {
    die("Error retrieving booking details. Please try again.");
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
    <script src="../js/confirmPickup.js"></script>

</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="schedule-pickup-form">
        <h1>Confirm Your Pickup</h1>

        <div class="form-group">
            <label>Pickup Date</label>
            <p><?= htmlspecialchars($pickup['pickup_date']) ?></p>
        </div>

        <div class="form-group">
            <label>Pickup Time</label>
            <p><?= htmlspecialchars($pickup['pickup_time']) ?></p>
        </div>

        <div class="form-group">
            <label>Pickup Address</label>
            <p><?= nl2br(htmlspecialchars($pickup['pickup_location'])) ?></p>
        </div>

        <div class="form-group">
            <label>Delivery Address</label>
            <p><?= nl2br(htmlspecialchars($pickup['delivery_location'])) ?></p>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <p><?= htmlspecialchars($pickup['phone_number']) ?></p>
        </div>

        <div class="form-group">
            <label>Item Description</label>
            <p><?= htmlspecialchars($pickup['item_description']) ?></p>
        </div>

        <div class="form-group">
            <label>Item Weight (kg)</label>
            <p><?= htmlspecialchars($pickup['item_weight']) ?></p>
        </div>

        <form action="chooseCourier.php" method="POST">
            <button type="submit" class="submit-btn">Confirm & Choose Courier</button>
        </form>

        <form action="schedulePickup.php" method="GET">
            <button type="submit" class="submit-btn" style="background-color: #d32f2f;">Edit Details</button>
        </form>
    </main>
</body>
</html>
