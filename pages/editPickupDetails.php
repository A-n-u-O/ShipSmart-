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

// Handle booking update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $booking_id = $_POST['booking_id'];
        $new_date = $_POST['pickup_date'];
        $new_time = $_POST['pickup_time'];
        $new_address = $_POST['pickup_address'];

        // Validate input
        if (empty($new_date) || empty($new_time) || empty($new_address)) {
            throw new Exception('All fields are required');
        }

        // Update booking
        $stmt = $pdo->prepare("
            UPDATE Bookings 
            SET pickup_date = ?, pickup_time = ?, pickup_location = ? 
            WHERE booking_id = ? AND user_id = ?
        ");
        $stmt->execute([
            $new_date, 
            $new_time, 
            $new_address, 
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

                <button type="submit">Update Booking</button>
            </form>
        <?php else: ?>
            <p>No upcoming bookings to edit.</p>
        <?php endif; ?>
    </main>
</body>
</html>