<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_booking_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM Bookings WHERE booking_id = ? AND user_id = ?");
        $stmt->execute([$_POST['delete_booking_id'], $_SESSION['user_id']]);
        $_SESSION['success_message'] = "Booking removed successfully.";
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error deleting booking: " . $e->getMessage();
    }
    header('Location: scheduledPickups.php');
    exit();
}

// Fetch user's scheduled pickups excluding "In Progress" or "Confirmed"
try {
    $stmt = $pdo->prepare("
        SELECT b.booking_id, b.pickup_date, b.pickup_time, b.pickup_location, b.status, 
               b.item_weight, b.phone_number, b.item_description,
               s.tracking_number, s.current_status, 
               c.first_name AS courier_first_name, c.last_name AS courier_last_name, 
               c.contact_info AS courier_phone, c.available AS courier_available
        FROM Bookings b
        LEFT JOIN Shipments s ON b.booking_id = s.booking_id
        LEFT JOIN Couriers c ON b.courier_id = c.courier_id
        WHERE b.user_id = ? AND b.status NOT IN ('In Progress', 'Confirmed')
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
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/scheduledPickups.css">
</head>

<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="scheduled-pickups">
        <h1>Schedule Pickup > Your Scheduled Pickups</h1>

        <?php if (!empty($pickups)): ?>
            <div class="pickup-list">
                <?php foreach ($pickups as $pickup): ?>
                    <div class="pickup-item">
                        <h4>Pickup ID: <?= htmlspecialchars($pickup['booking_id']); ?></h4>
                        <p><strong>Pickup Date:</strong> <?= htmlspecialchars($pickup['pickup_date']); ?></p>
                        <p><strong>Pickup Time:</strong> <?= htmlspecialchars($pickup['pickup_time']); ?></p>
                        <p><strong>Pickup Location:</strong> <?= htmlspecialchars($pickup['pickup_location']); ?></p>
                        <p><strong>Status:</strong> <?= htmlspecialchars($pickup['status']); ?></p>
                        <p><strong>Weight:</strong> <?= htmlspecialchars($pickup['item_weight']); ?> kg</p>
                        <p><strong>Phone Number:</strong> <?= htmlspecialchars($pickup['phone_number']); ?></p>
                        <p><strong>Item Description:</strong> <?= htmlspecialchars($pickup['item_description']); ?></p>

                        <?php if ($pickup['tracking_number']): ?>
                            <p><strong>Tracking Number:</strong> <?= htmlspecialchars($pickup['tracking_number']); ?></p>
                            <p><strong>Shipment Status:</strong> <?= htmlspecialchars($pickup['current_status']); ?></p>
                        <?php endif; ?>

                        <?php if ($pickup['courier_first_name']): ?>
                            <h4>Courier Information</h4>
                            <p>Name: <?= htmlspecialchars($pickup['courier_first_name'] . ' ' . $pickup['courier_last_name']); ?></p>
                            <p>Phone: <?= htmlspecialchars($pickup['courier_phone']); ?></p>
                            <p>Available: <?= $pickup['courier_available'] ? 'Yes' : 'No'; ?></p>
                        <?php else: ?>
                            <p>No courier has been assigned yet.</p>
                        <?php endif; ?>

                        <div class="position-button">
                            <form action="editPickupDetails.php" method="GET">
                                <button type="submit" class="edit-btn" name="booking_id" value="<?= $pickup['booking_id'] ?>"
                                    aria-label="Edit pickup details for booking ID <?= htmlspecialchars($pickup['booking_id']); ?>">
                                    Edit
                                </button>
                            </form>

                            <form action="scheduledPickups.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this pickup?');">
                                <button type="submit" class="delete-btn" name="delete_booking_id" value="<?= $pickup['booking_id'] ?>"
                                    aria-label="Delete pickup ID <?= htmlspecialchars($pickup['booking_id']); ?>">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No scheduled pickups found.</p>
        <?php endif; ?>

        <?php
        // Display messages
        $messages = [
            'error' => $_SESSION['error_message'] ?? null,
            'success' => $_SESSION['success_message'] ?? null,
        ];
        foreach ($messages as $type => $message):
            if ($message):
                $class = $type === 'error' ? 'error-message' : 'success-message';
        ?>
                <div class="<?= $class ?>" id="<?= $type ?>Message" role="alert">
                    <?= htmlspecialchars($message); ?>
                </div>
        <?php
                unset($_SESSION[$type . '_message']);
            endif;
        endforeach;
        ?>
    </main>
    <script src="../js/scheduledPickups.js"></script>
</body>

</html>