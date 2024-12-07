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
    $_SESSION['error_message'] = "Error fetching bookings: " . htmlspecialchars($e->getMessage());
    $bookings = [];
}

// Fetch available couriers for the user to choose
try {
    $courier_stmt = $pdo->prepare("SELECT courier_id, first_name, last_name, phone_number FROM Couriers WHERE available = 1");
    $courier_stmt->execute();
    $couriers = $courier_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching couriers: " . htmlspecialchars($e->getMessage());
    $couriers = [];
}

// Handle booking update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $booking_id = $_POST['booking_id'];
        
        $stmt = $pdo->prepare("
            SELECT * FROM Bookings 
            WHERE booking_id = ? AND user_id = ?
        ");
        $stmt->execute([$booking_id, $_SESSION['user_id']]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            throw new Exception('Booking not found.');
        }

        $new_date = $_POST['pickup_date'];
        $new_time = $_POST['pickup_time'];
        $new_address = $_POST['pickup_address'];
        $selected_courier = $_POST['courier_id'];
        $phone_number = $_POST['phone_number'];

        // Validate input
        if (empty($new_date) || empty($new_time) || empty($new_address) || empty($selected_courier) || empty($phone_number)) {
            throw new Exception('All fields are required');
        }

        $today = new DateTime();
        $maxDate = (clone $today)->modify('+3 months');
        $selectedDate = new DateTime($new_date);

        // Check if the selected date is a weekend
        if ($selectedDate->format('N') >= 6) {
            throw new Exception('Pickup date cannot be on a weekend.');
        } elseif ($selectedDate < $today) {
            throw new Exception('Pickup date cannot be in the past.');
        } elseif ($selectedDate > $maxDate) {
            throw new Exception('Pickup date cannot be more than 3 months in the future.');
        }

        // Validate working hours (9 AM to 5 PM)
        $time = DateTime::createFromFormat('H:i', $new_time);
        if ($time < DateTime::createFromFormat('H:i', '09:00') || $time > DateTime::createFromFormat('H:i', '17:00')) {
            throw new Exception('Pickup time must be between 9 AM and 5 PM.');
        }

        if (!preg_match('/^(?:\+234|0)\d{10}$/', $phone_number)) {
            throw new Exception('Invalid Nigerian phone number format. Use +234XXXXXXXXXX or 0XXXXXXXXXX.');
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
        header('Location: scheduledPick ups.php');
        exit();

    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error updating booking: " . htmlspecialchars($e->getMessage());
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
        $_SESSION['error_message'] = "Error fetching booking: " . htmlspecialchars($e->getMessage());
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
    <link rel="stylesheet" href="../css/editPickupDetails.css">
    <script src="../js/validatePickup.js" defer></script>
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="edit-pickup">
        <h1>Edit Pickup Details</h1>
        
        <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="error-message" role="alert">
                <?= htmlspecialchars($_SESSION['error_message']); ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php elseif (!empty($_SESSION['success_message'])): ?>
            <div class="success-message" role="alert">
                <?= htmlspecialchars($_SESSION['success_message']); ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($bookings)): ?>
            <form method="POST" action="">
                <div class="booking-select">
                    <label for="booking_id">Select Booking to Edit:</label>
                    <select name="booking_id" id="booking_id" required onchange="this.form.submit()">
                        <option value="">Select a booking</option>
                        <?php foreach ($bookings as $b): ?>
                            <option value="<?= htmlspecialchars($b['booking_id']) ?>" <?= isset($booking) && ($booking['booking_id'] == $b['booking_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($b['pickup_date'] . ' at ' . date('H:i', strtotime($b['pickup_time']))) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if (isset($booking)): ?>
                    <div class="form-group">
                        <label for="pickup_date">New Pickup Date:</label>
                        <input type="date" name="pickup_date" id="pickup_date" value="<?= htmlspecialchars($booking['pickup_date']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="pickup_time">New Pickup Time:</label>
                        <input type="time" name="pickup_time" id="pickup_time" value="<?= htmlspecialchars($booking['pickup_time']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="pickup_address">New Pickup Address:</label>
                        <textarea name="pickup_address" id="pickup_address" required><?= htmlspecialchars($booking['pickup_location']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Phone Number (Nigerian only):</label>
                        <input type="text" name="phone_number" id="phone_number" value="<?= htmlspecialchars($booking['phone_number']) ?>" required placeholder="e.g., +234XXXXXXXXXX">
                    </div>

                    <div class="form-group">
                        <label for="courier_id">Select Courier:</label>
                        <select name="courier_id" id="courier_id" required>
                            <?php if (!empty($couriers)): ?>
                                <?php foreach ($couriers as $courier): ?>
                                    <option value="<?= htmlspecialchars($courier['courier_id']) ?>" <?= isset($booking) && ($booking['courier_id'] == $courier['courier_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($courier['first_name'] . ' ' . $courier['last_name']) ?>
                                        <?= htmlspecialchars($courier['contact_info']) ?> 
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No couriers available</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <button type="submit">Update Booking</button>
                <?php endif; ?>
            </form>
        <?php else: ?>
            <p>No upcoming bookings to edit.</p>
        <?php endif; ?>
        
    </main>
    <script src="../js/editPickupDetails.js" defer></script>
</body>
</html>