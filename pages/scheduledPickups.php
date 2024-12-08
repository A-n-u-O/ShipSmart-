<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user's scheduled pickups with courier details
try {
    $stmt = $pdo->prepare("
        SELECT b.booking_id, b.pickup_date, b.pickup_time, b.pickup_location, b.status, 
               s.tracking_id, s.current_status,
               c.company_name AS company_name,
               c.available AS courier_available
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
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/scheduledPickups.css">
    <script src="../js/scheduledPickups.js"></script>
</head>
<body>
    <?php include '../Views/navbar.php'; ?>
    <header>Your Scheduled Pickups</header>

    <main class="scheduled-pickups">

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

        <?php if (!empty($pickups)): ?>
            <div class="pickup-list">
                <?php foreach ($pickups as $pickup): ?>
                    <div class="pickup-item">
                        <h4>Pickup ID: <?= htmlspecialchars($pickup['booking_id']); ?></h4>
                        <p><strong>Pickup Date:</strong> <?= htmlspecialchars($pickup['pickup_date']); ?></p>
                        <p><strong>Pickup Time:</strong> <?= htmlspecialchars($pickup['pickup_time']); ?></p>
                        <p><strong>Pickup Location:</strong> <?= htmlspecialchars($pickup['pickup_location']); ?></p>
                        <p><strong>Status:</strong> <?= htmlspecialchars($pickup['status']); ?></p>
                        <?php if ($pickup['tracking_id']): ?>
                            <p><strong>Tracking Number:</strong> <?= htmlspecialchars($pickup['tracking_id']); ?></p>
                            <p><strong>Shipment Status:</strong> <?= htmlspecialchars($pickup['status']); ?></p>
                        <?php endif; ?>

                        <?php if ($pickup['company_name']): ?>
                            <h4>Courier Information</h4>
                            <p>Name: <?= htmlspecialchars($pickup['company_name']); ?></p>
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

                            <form action="scheduledPickups.php" method="POST" onsubmit="return confirm(' Are you sure you want to delete this pickup?');">
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
    </main>
    <script src="../js/scheduledPickups.js"></script>
</body>
</html>