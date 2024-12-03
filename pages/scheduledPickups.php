<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Function to sanitize output
function sanitizeOutput($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Handle delete action
if (isset($_POST['delete_booking_id'])) {
    $booking_id = $_POST['delete_booking_id'];
    
    try {
        $pdo->beginTransaction();

        // Verify the booking belongs to the logged-in user
        $check_stmt = $pdo->prepare("SELECT user_id FROM Bookings WHERE booking_id = ?");
        $check_stmt->execute([$booking_id]);
        $booking = $check_stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
            throw new Exception("You are not authorized to delete this booking.");
        }
        
        // Delete associated shipments first (if they exist)
        $delete_shipments_stmt = $pdo->prepare("DELETE FROM Shipments WHERE booking_id = ?");
        $delete_shipments_stmt->execute([$booking_id]);
        
        // Then delete the booking
        $delete_stmt = $pdo->prepare("DELETE FROM Bookings WHERE booking_id = ?");
        $delete_stmt->execute([$booking_id]);

        $pdo->commit();
        
        $_SESSION['success_message'] = "Pickup successfully canceled.";
        header('Location: scheduledPickups.php');
        exit();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        error_log("Deletion error: " . $e->getMessage());
        $_SESSION['error_message'] = "Error deleting pickup: " . $e->getMessage();
        header('Location: scheduledPickups.php');
        exit();
    }
}

// Fetch user's scheduled pickups with courier details
try {
    $stmt = $pdo->prepare("
        SELECT b.booking_id, b.pickup_date, b.pickup_time, b.pickup_location, b.status, 
               s.tracking_number, s.current_status, 
               c.first_name AS courier_first_name, c.last_name AS courier_last_name, c.phone_number AS courier_phone, c.is_available AS courier_available
        FROM Bookings b
        LEFT JOIN Shipments s ON b.booking_id = s.booking_id
        LEFT JOIN Couriers c ON b.courier_id = c.courier_id
        WHERE b.user_id = ?
        ORDER BY b.pickup_date DESC, b.pickup_time DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $pickups = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching pickups: " . $e->getMessage();
    $pickups = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Pickups</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/scheduledPickups.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="scheduled-pickups">
        <h1>Your Scheduled Pickups</h1>
        
        <?php 
        if (isset($_SESSION['error_message'])) {
            echo '<div class="error-message">' . sanitizeOutput($_SESSION['error_message']) . '</div>';
            unset($_SESSION['error_message']);
        }

        if (isset($_SESSION['success_message'])): ?>
            <div class="success-message" id="successMessage">
                <?= sanitizeOutput($_SESSION['success_message']); ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        
        <?php if (!empty($pickups)): ?>
            <div class="pickup-list">
                <?php foreach ($pickups as $pickup): ?>
                    <div class="pickup-item">
                        <h3>Pickup on <?= sanitizeOutput($pickup['pickup_date']) ?></h3>
                        <p>Time: <?= sanitizeOutput($pickup['pickup_time']) ?></p>
                        <p>Address: <?= sanitizeOutput($pickup['pickup_location']) ?></p>
                        <p>Booking Status: <?= sanitizeOutput($pickup['status']) ?></p>
                        <?php if (!empty($pickup['tracking_number'])): ?>
                            <p>Tracking Number: <?= sanitizeOutput($pickup['tracking_number']) ?></p>
                            <p>Shipment Status: <?= sanitizeOutput($pickup['current_status']) ?></p>
                        <?php endif; ?>

                        <h4>Courier Information</h4>
                        <p>Name: <?= sanitizeOutput($pickup['courier_first_name'] . ' ' . $pickup['courier_last_name']); ?></p>
                        <p>Phone: <?= sanitizeOutput($pickup['courier_phone']); ?></p>
                        <p>Available: <?= $pickup['courier_available'] ? 'Yes' : 'No'; ?></p>

                        <div class="position-button">
                            <form action="scheduledPickups.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this pickup?');">
                                <button type="submit" class="cancel-btn" name="delete_booking_id" value="<?= $pickup['booking_id'] ?>">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p id="scheduled-details">No pickups scheduled yet.</p>
        <?php endif; ?>

        <button id="edit-schedule-btn" onclick="window.location.href='editPickupDetails.php'">Edit Pickup Details</button>
    </main>

    <script>
        // Remove success message after a few seconds
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>
