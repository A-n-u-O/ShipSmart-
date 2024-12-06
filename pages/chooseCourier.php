<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and has a current booking
if (!isset($_SESSION['user_id']) || !isset($_SESSION['current_booking'])) {
    header('Location: schedulePickup.php');
    exit();
}

// Fetch available couriers
try {
    $stmt = $pdo->prepare("
        SELECT courier_id, first_name, last_name, contact_info, available_time 
        FROM Couriers 
        WHERE available = 1 
        ORDER BY first_name, last_name
    ");
    $stmt->execute();
    $couriers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching couriers: " . htmlspecialchars($e->getMessage());
}

// Handle courier selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['courier_id'])) {
    try {
        // Update the booking with selected courier
        $courier_id = $_POST['courier_id'];
        $booking_id = $_SESSION['current_booking']['booking_id'];

        // Update Booking with selected Courier ID and change status to Confirmed
        $stmt = $pdo->prepare("
            UPDATE Bookings 
            SET courier_id = ?, status = 'Confirmed' 
            WHERE booking_id = ? AND user_id = ?
        ");
        $stmt->execute([$courier_id, $booking_id, $_SESSION['user_id']]);

        // Store courier details in session
        $_SESSION['current_booking']['courier_id'] = $courier_id;

        // Redirect to confirmation page
        header('Location: confirmPickup.php');
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error selecting courier: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Choose Courier</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/chooseCourier.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="choose-courier">
        <h1>Select Courier</h1>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message" role="alert">
                <?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div class="courier-list">
            <?php if (!empty($couriers)): ?>
                <form method="POST">
                    <div class="courier-options">
                        <?php foreach ($couriers as $courier): ?>
                            <div class="courier-item">
                                <input type="radio" name="courier_id" id="courier_<?= htmlspecialchars($courier['courier_id']) ?>" value="<?= htmlspecialchars($courier['courier_id']) ?>" required>
                                <label for="courier_<?= htmlspecialchars($courier['courier_id']) ?>">
                                    <strong><?= htmlspecialchars($courier['first_name'] . ' ' . $courier['last_name']) ?></strong>
                                    <p>Phone: <?= htmlspecialchars($courier['contact_info']) ?></p>
                                    <p>Available Time: <?= htmlspecialchars($courier['available_time']) ?></p>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="select-courier-btn">Select Courier</button>
                </form>
            <?php else: ?>
                <p class="no-courier-message">No available couriers at this time.</p>
            <?php endif; ?>
        </div>

        <div class="actions">
            <a href="schedulePickup.php" class="back-btn">Back to Schedule</a>
        </div>
    </main>
</body>
</html>