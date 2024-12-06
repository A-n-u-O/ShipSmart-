<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Function to sanitize output
function sanitizeOutput($value)
{
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

        // Delete associated shipments first
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
        $_SESSION['error_message'] = "Error deleting pickup: " . $e->getMessage();
    }
}

// Fetch user's scheduled pickups with courier details
try {
    $stmt = $pdo->prepare("
        SELECT b.booking_id, b.pickup_date, b.pickup_time, b.pickup_location, b.status, 
               s.tracking_number, s.current_status, 
               c.first_name AS courier_first_name, c.last_name AS courier_last_name, 
               c.contact_info AS courier_phone, c.available AS courier_available
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


<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/scheduledPickups.css">
    <script src="../js/scheduledPickups.js" defer></script>
</head>

<body>
    <?php include '../Views/navbar.php'; ?>
    <header>Schedule Pickup > Your Scheduled Pickups</header>

    <main class="scheduled-pickups">

        $messages = [
            'error' => $_SESSION['error_message'] ?? null,
            'success' => $_SESSION['success_message'] ?? null,
        ];
        foreach ($messages as $type => $message):
            if ($message):
                $class = $type === 'error' ? 'error-message' : 'success-message';
        ?>
                <div class="<?= $class ?>" id="<?= $type ?>Message" role="alert">
                    <?= sanitizeOutput($message); ?>
                </div>
        <?php unset($_SESSION[$type . '_message']);
            endif;
        endforeach; ?>

                            <p>Tracking Number: <?= sanitizeOutput($pickup['tracking_number']) ?></p>
                            <p>Shipment Status: <?= sanitizeOutput($pickup['current_status']) ?></p>
                        <?php endif; ?>

                        <?php if ($pickup['courier_first_name']): ?>
                            <h4>Courier Information</h4>
                            <p>Name: <?= sanitizeOutput($pickup['courier_first_name'] . ' ' . $pickup['courier_last_name']); ?></p>
                            <p>Phone: <?= sanitizeOutput($pickup['courier_phone']); ?></p>
                            <p>Available: <?= $pickup['courier_available'] ? 'Yes' : 'No'; ?></p>
                        <?php else: ?>
                            <p>No courier has been assigned yet.</p>
                        <?php endif; ?>

                        <div class="position-button">
                            <form action="editPickupDetails.php" method="GET">
                                <button type="submit" class="edit-btn" name="booking_id" value="<?= $pickup['booking_id'] ?>"
                                    aria-label="Edit pickup details for booking ID <?= sanitizeOutput($pickup['booking_id']); ?>">
                                    Edit
                                </button>
                            </form>

                            <form action="scheduledPickups.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this pickup?');">
                                <button type="submit" class="cancel-btn" name="delete_booking_id" value="<?= $pickup['booking_id'] ?>"
                                    aria-label="Cancel pickup for booking ID <?= sanitizeOutput($pickup['booking_id']); ?>">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-pickups">
                <img src="../Assets/icons/emptyIcon.svg" alt="No pickups scheduled">
                <p>No scheduled pickups.</p>
            </div>
        <?php endif; ?>
    </main>
    <script src="../js/scheduledPickups.js" defer></script>
</body>

</html>
