<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch the user's upcoming bookings
try {
    $stmt = $pdo->prepare("
        SELECT booking_id, pickup_date, pickup_time, pickup_location 
        FROM Bookings 
        WHERE user_id = ? AND status IN ('Pending', 'Confirmed') 
        ORDER BY pickup_date, pickup_time
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    $bookings = [];
}

// Fetch available couriers for the user to choose
try {
    $courier_stmt = $pdo->prepare("SELECT courier_id, first_name, last_name, phone_number, available_time, rating FROM Couriers WHERE is_available = 1");
    $courier_stmt->execute();
    $couriers = $courier_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching couriers: " . $e->getMessage();
    $couriers = [];
}

// Handle booking update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $booking_id = $_POST['booking_id'];
        $new_date = $_POST['pickup_date'];
        $new_time = $_POST['pickup_time'];
        $new_address = $_POST['pickup_address'];
        $selected_courier = $_POST['courier_id'];

        // Validate input
        if (empty($new_date) || empty($new_time) || empty($new_address) || empty($selected_courier)) {
            throw new Exception('All fields are required');
        }

        // Update booking
        $stmt = $pdo->prepare("
            UPDATE Bookings 
            SET pickup_date = ?, pickup_time = ?, pickup_location = ?, courier_id = ? 
            WHERE booking_id = ? AND user_id = ?
        ");
        $stmt->execute([
            $new_date, 
            $new_time, 
            $new_address, 
            $selected_courier, 
            $booking_id, 
            $_SESSION['user_id']
        ]);

        $_SESSION['success_message'] = 'Booking updated successfully!';
        header('Location: scheduledPickups.php');
        exit();

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }
}
?>

<!-- HTML for editPickupDetails.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pickup Details</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/editPickup.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="edit-pickup">
        <h1>Edit Pickup Details</h1>
        
        <?php if (!empty($bookings)): ?>
            <form method="POST" action="">
                <div class="booking-select">
                    <label for="booking_id">Select Booking to Edit:</label>
                    <select name="booking_id" id="booking_id" required>
                        <?php foreach ($bookings as $booking): ?>
                            <option value="<?= htmlspecialchars($booking['booking_id']) ?>">
                                <?= htmlspecialchars($booking['pickup_date'] . ' at ' . $booking['pickup_time']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="pickup_date">New Pickup Date:</label>
                    <input type="date" name="pickup_date" id="pickup_date" required>
                </div>

                <div class="form-group">
                    <label for="pickup_time">New Pickup Time:</label>
                    <input type="time" name="pickup_time" id="pickup_time" required>
                </div>

                <div class="form-group">
                    <label for="pickup_address">New Pickup Address:</label>
                    <textarea name="pickup_address" id="pickup_address" required></textarea>
                </div>

                <div class="form-group">
                    <label for="courier_id">Select Courier:</label>
                    <select name="courier_id" id="courier_id" required>
                        <?php if (!empty($couriers)): ?>
                            <?php foreach ($couriers as $courier): ?>
                                <option value="<?= htmlspecialchars($courier['courier_id']) ?>">
                                    <?= htmlspecialchars($courier['first_name'] . ' ' . $courier['last_name']) ?> - <?= htmlspecialchars($courier['phone_number']) ?> (<?= htmlspecialchars($courier['rating']) ?> ★) Available at: <?= htmlspecialchars($courier['available_time']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No couriers available</option>
                        <?php endif; ?>
                    </select>
                </div>

                <button type="submit">Update Booking</button>
            </form>
        <?php else: ?>
            <p>No upcoming bookings to edit.</p>
        <?php endif; ?>
    </main>
</body>
</html>
