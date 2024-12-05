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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Pickups</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/scheduledPickups.css">
    <script src="../js/scheduledPickups.js" defer></script>
</head>

<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="scheduled-pickups">
        <h1>Your Scheduled Pickups</h1>

        <?php
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

        <?php if (!empty($pickups)): ?>
            <div class="pickup-list">
                <?php foreach ($pickups as $pickup): ?>
                    <div class="pickup-item">
                        <h3>Pickup on <?= sanitizeOutput($pickup['pickup_date']) ?></h3>
                        <p>Time: <?= sanitizeOutput($pickup['pickup_time']) ?></p>
                        <p>Address: <?= sanitizeOutput($pickup['pickup_location']) ?></p>
                        <p class="status <?= strtolower(sanitizeOutput($pickup['status'])) ?>">
                            Booking Status: <?= sanitizeOutput($pickup['status']) ?>
                        </p>

                        <?php if ($pickup['tracking_number']): ?>
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
                <svg width="256px" height="256px" viewBox="-51.2 -51.2 614.40 614.40" xmlns="http://www.w3.org/2000/svg" fill="#000000" stroke="#000000" stroke-width="1.536" transform="matrix(-1, 0, 0, 1, 0, 0)rotate(45)">

                    <g id="SVGRepo_bgCarrier" stroke-width="0" transform="translate(25.599999999999994,25.599999999999994), scale(0.9)">

                        <path transform="translate(-51.2, -51.2), scale(19.2)" d="M16,31.46570710465312C18.718554686186323,31.30999864517492,19.687566951886776,27.648951804981895,21.615004929481074,25.725473822610923C23.01717173309742,24.326187824451967,24.800721800073106,23.368202241423727,25.81765315666398,21.668224692810327C26.84369252020725,19.953021527215668,27.567753697061644,17.99410859222005,27.432810756377876,16C27.301252071952675,14.055901985949424,25.654930463508958,12.619628287364431,25.079351720701446,10.758033839985732C24.295372904307648,8.222410991333556,25.486552950916366,4.800495998752476,23.44553538202309,3.1039544287842755C21.503668912432506,1.4898295730270563,18.524828634058373,3.1725310433767313,16,3.211064202245325C13.519210061651986,3.2489252560707143,10.83543498810274,2.092561367216512,8.684528900543231,3.329232374439112C6.533482298009942,4.565984171239257,5.703300404120606,7.244138066390493,4.901419906954274,9.59223179565742C4.196648043545558,11.655968722645568,4.141238696989784,13.82998243965153,4.357426742557436,15.999999999999998C4.558231839210539,18.015609072187786,5.141957056592965,19.93074083763915,6.108725545039784,21.71072996919974C7.093903620031572,23.524614355268692,8.531264696353976,24.941263453586007,10.000936110038305,26.39068345526543C11.898158827370628,28.26176329565532,13.339703646140116,31.618078748718183,16,31.46570710465312" fill="#28bef0" strokewidth="0" />

                    </g>

                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

                    <g id="SVGRepo_iconCarrier">

                        <path fill="#000000" d="M388.656 21.406L76.876 72.063 80.56 95l311.813-50.656-3.72-22.938zM375.312 66.03l-18.437 3 16.25 100.282c-7.01 4.713-11.034 13.208-9.594 22.094.474 2.913 1.5 5.587 2.94 7.97 5.55-4.228 12.173-7.207 19.5-8.407h.06c2.25-.367 4.475-.546 6.69-.564.737-.006 1.485 0 2.217.03 4.273.182 8.436.995 12.375 2.345.62-2.72.724-5.61.25-8.53-1.433-8.843-7.885-15.59-15.968-17.875L375.314 66.03zM332.406 73L148.25 102.938c9.082 42.138 25.73 78.544 45.72 103.843 22.553 28.546 48.246 42.31 71.874 38.47 23.628-3.84 43.653-25.027 56.03-59.25 10.974-30.34 15.237-70.14 10.532-113zM123 107.03l-18.438 3 16.344 100.72c-6.837 4.75-10.734 13.14-9.312 21.906.457 2.82 1.446 5.43 2.812 7.75 5.593-4.288 12.283-7.297 19.688-8.5 2.25-.365 4.474-.545 6.687-.562.74-.006 1.488 0 2.22.03 4.293.183 8.482.983 12.438 2.345.56-2.633.644-5.403.187-8.22-1.456-8.977-8.087-15.82-16.344-18L123 107.03zm269.938 102.032c-1.288.012-2.592.13-3.907.344-14.024 2.28-23.4 15.275-21.124 29.313 2.276 14.036 15.257 23.403 29.28 21.124 14.026-2.28 23.402-15.275 21.127-29.313-1.992-12.282-12.182-20.98-24.094-21.436-.427-.017-.853-.035-1.283-.03zM141 250c-1.287.012-2.59.13-3.906.344-14.025 2.28-23.4 15.275-21.125 29.312 2.275 14.038 15.255 23.404 29.28 21.125 14.025-2.278 23.4-15.274 21.125-29.31-1.99-12.284-12.182-20.982-24.094-21.44-.424-.015-.85-.034-1.28-.03zm134.656 13.844c-2.244.054-4.472.265-6.687.625-23.63 3.84-43.654 25.057-56.033 59.28-11.165 30.868-15.416 71.534-10.312 115.25L387 409.062c-8.967-43.092-25.812-80.345-46.156-106.093-19.735-24.98-41.883-38.67-62.938-39.126-.752-.017-1.502-.018-2.25 0zm144.094 6.03c-5.567 4.238-12.213 7.213-19.563 8.407-7.386 1.2-14.665.472-21.312-1.81-.606 2.7-.72 5.57-.25 8.467 1.447 8.927 7.996 15.75 16.188 17.97l16.562 102.187 18.438-3-16.594-102.313c6.896-4.736 10.864-13.16 9.436-21.967-.47-2.896-1.477-5.567-2.906-7.938zm-252.063 41.032c-5.54 4.187-12.143 7.128-19.437 8.313-7.352 1.193-14.597.476-21.22-1.783-.666 2.79-.83 5.742-.343 8.75 1.427 8.8 7.816 15.528 15.844 17.844l16.532 102.064 18.438-3-16.53-101.875c7.103-4.692 11.2-13.24 9.75-22.19-.484-2.975-1.542-5.71-3.032-8.124zM452.844 417.28L141.063 467.94l3.687 22.937 311.813-50.656-3.72-22.94z" />

                    </g>

                </svg>
                <p>No scheduled pickups.</p>
            </div>
        <?php endif; ?>
    </main>
    <script src="../js/scheduledPickups.js" defer></script>
</body>

</html>