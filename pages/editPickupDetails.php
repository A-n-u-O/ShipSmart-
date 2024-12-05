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
    $courier_stmt = $pdo->prepare("SELECT courier_id, first_name, last_name, phone_number, available_time FROM Couriers WHERE available_time > NOW() AND is_available = 1");
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
        $phone_number = $_POST['phone_number']; // Added phone number field

        // Validate input
        if (empty($new_date) || empty($new_time) || empty($new_address) || empty($selected_courier) || empty($phone_number)) {
            throw new Exception('All fields are required');
        }

        // Validate Nigerian phone number (starts with 0, followed by 9 digits)
        if (!preg_match('/^0\d{9}$/', $phone_number)) {
            throw new Exception('Invalid Nigerian phone number. Must start with 0 and contain 10 digits.');
        }

        // Update booking
        $stmt = $pdo->prepare("
            UPDATE Bookings 
            SET pickup_date = ?, pickup_time = ?, pickup_location = ?, courier_id = ?, phone_number = ? 
            WHERE booking_id = ? AND user_id = ?
        ");
        $stmt->execute([
            $new_date, 
            $new_time, 
            $new_address, 
            $selected_courier, 
            $phone_number, 
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

// Fetch the selected booking details if editing a specific booking
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    try {
        $stmt = $pdo->prepare("
            SELECT booking_id, pickup_date, pickup_time, pickup_location, courier_id, phone_number 
            FROM Bookings 
            WHERE booking_id = ? AND user_id = ?
        ");
        $stmt->execute([$booking_id, $_SESSION['user_id']]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            $_SESSION['error_message'] = 'Booking not found.';
            header('Location: scheduledPickups.php');
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error fetching booking: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pickup Details</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/couriers.css">
    <script src="../js/editPickupDetails.js" defer></script>
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
                            <option value="<?= htmlspecialchars($booking['booking_id']) ?>" <?= isset($booking['booking_id']) && $booking['booking_id'] == $booking['booking_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($booking['pickup_date'] . ' at ' . $booking['pickup_time']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="pickup_date">New Pickup Date:</label>
                    <input type="date" name="pickup_date" id="pickup_date" value="<?= isset($booking['pickup_date']) ? htmlspecialchars($booking['pickup_date']) : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="pickup_time">New Pickup Time:</label>
                    <input type="time" name="pickup_time" id="pickup_time" value="<?= isset($booking['pickup_time']) ? htmlspecialchars($booking['pickup_time']) : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="pickup_address">New Pickup Address:</label>
                    <textarea name="pickup_address" id="pickup_address" required><?= isset($booking['pickup_location']) ? htmlspecialchars($booking['pickup_location']) : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number (Nigerian only):</label>
                    <input type="text" name="phone_number" id="phone_number" value="<?= isset($booking['phone_number']) ? htmlspecialchars($booking['phone_number']) : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="courier_id">Select Courier:</label>
                    <select name="courier_id" id="courier_id" required>
                        <?php if (!empty($couriers)): ?>
                            <?php foreach ($couriers as $courier): ?>
                                <option value="<?= htmlspecialchars($courier['courier_id']) ?>" <?= isset($booking['courier_id']) && $booking['courier_id'] == $courier['courier_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($courier['first_name'] . ' ' . $courier['last_name']) ?> - <?= htmlspecialchars($courier['phone_number']) ?> 
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
