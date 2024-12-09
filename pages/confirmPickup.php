<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch the latest booking details along with courier and company info
try {
    $stmt = $pdo->prepare("
        SELECT b.*, 
               d.company_name, 
               CONCAT(co.first_name, ' ', co.last_name) AS courier_name
        FROM Bookings b
        JOIN Couriers co ON b.courier_id = co.courier_id
        JOIN deliverycompanies d ON d.delivery_company_id = co.fk_delivery_company_id
        WHERE b.user_id = :user_id
        ORDER BY b.booking_id DESC 
        LIMIT 1
    ");

    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $pickup = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pickup) {
        $_SESSION['error_message'] = "No recent bookings found.";
        header('Location: schedulePickup.php');
        exit();
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $_SESSION['error_message'] = "An error occurred while fetching your booking details.";
    header('Location: schedulePickup.php');
    exit();
}

// Handle form submission for confirming the booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($pickup['booking_id'])) {
        $booking_id = $pickup['booking_id'];

        try {
            $stmt = $pdo->prepare("
                UPDATE Bookings 
                SET status = 'Confirmed' 
                WHERE booking_id = ? AND user_id = ?
            ");
            $stmt->execute([$booking_id, $_SESSION['user_id']]);

            $_SESSION['success_message'] = "Pickup confirmed! Booking status updated to 'Confirmed'.";
            // Store relevant booking details in session for future use
            $current_booking = [
                'courier_id' => $pickup['courier_id'] ?? null,
                'item_weight' => $pickup['item_weight'] ?? null,
                'pickup_date' => $pickup['pickup_date'] ?? null,
                'pickup_time' => $pickup['pickup_time'] ?? null,
                'pickup_location' => $pickup['pickup_location'] ?? null,
                'delivery_location' => $pickup['delivery_location'] ?? null,
                'phone_number' => $pickup['phone_number'] ?? null,
                'item_description' => $pickup['item_description'] ?? null,
                'shipping_port' => $pickup['shipping_port'] ?? null,
                'destination_zone' => $pickup['destination_zone'] ?? null,
            ];
        } catch (Exception $e) {
            error_log("Failed to confirm pickup: " . $e->getMessage());
            $_SESSION['error_message'] = "Failed to confirm pickup. Please try again later.";
        }
    }
    $_SESSION['current_booking'] = $current_booking;

    // Redirect to rates and pricing page after confirmation
    header('Location: ratesAndPricing.php');
    exit();
}
?>
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
</head>

<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="schedule-pickup-form">
        <h1>Confirm Your Pickup</h1>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div class="pickup-details">
            <!-- Display pickup details -->
            <?php foreach ($pickup as $key => $value): ?>
                <div class="form-group">
                    <label><?= ucfirst(str_replace('_', ' ', $key)) ?></label>
                    <p><?= htmlspecialchars($value ?? 'Not available') ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="booking-actions">
            <form method="POST" class="confirm-form">
                <button type="submit" class="submit-btn">Confirm Booking</button>
            </form>

            <form action="schedulePickup.php" method="GET" class="edit-form">
                <button type="submit" class="submit-btn edit-btn">Edit Details</button>
            </form>
        </div>
    </main>

    <script src="../js/confirmPickup.js"></script>
</body>

</html>