<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // Redirect to login page if the session data is missing
    header("Location: ../Views/login.php");
    exit;
}

// Include the database connection
include '../config/database.php';

// Get the current user's ID from the session
$user_id = $_SESSION['user_id'];

// Fetch active shipments for the current user
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM shipments 
    WHERE booking_id IN (
        SELECT booking_id 
        FROM bookings 
        WHERE user_id = :user_id
    ) 
    AND current_status != 'Delivered'
");
$stmt->execute(['user_id' => $user_id]);
$active_shipments = $stmt->fetchColumn();

// Fetch recent bookings (limit to 5)
$stmt = $pdo->prepare("
    SELECT booking_id, pickup_location, delivery_location, pickup_date, pickup_time, status 
    FROM bookings 
    WHERE user_id = :user_id 
    ORDER BY created_at DESC 
    LIMIT 5
");
$stmt->execute(['user_id' => $user_id]);
$recent_bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/dashBoard.css">
    <link rel="stylesheet" href="../css/notification.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>

<body>
    <?php include '../Views/navbar.php'; ?><!-- Include the reusable navbar -->

    <div class="container">
        <?php if (isset($_SESSION['login_message'])) { ?>
            <div class="notification" id="notification">
                <?= htmlspecialchars($_SESSION['login_message']); ?> <script>
                    setTimeout(function() {
                        document.getElementById("notification").classList.add("hide");
                    }, 5000); // Hide the notification after 5 seconds
                </script>
            </div>
        <?php unset($_SESSION['login_message']); // Clear the message after displaying it 
        } ?>

        <!-- HTML to Display Dynamic Data -->
        <header>
            <div class="user-info">
                <div class="greet-user">
                    <img id="greeting-icon" src="" alt="Greeting Icon"> <!-- Placeholder for the icon -->
                    <span id="greeting"></span>
                    <span><?= htmlspecialchars($_SESSION['username']); ?>!</span>
                </div>
                <img src="../Assets/icons/userIcon.svg" alt="User  Avatar" class="user-icon">
            </div>
        </header>

        <main>
            <div class="dashboard">
                <div class="overview">
                    <h1>Welcome to your ShipSmart Dashboard! </h1>
                    <div class="stats">
                        <div class="stat-item">Active Shipments: <strong><?= htmlspecialchars($active_shipments) ?></strong></div>
                        <div class="stat-item">Upcoming Pickups: <strong>3</strong></div>
                        <div class="stat-item">Recent Bookings: <strong><?= htmlspecialchars(count($recent_bookings)) ?></strong></div>
                    </div>
                </div>

                <div class="recent-bookings">
                    <h3>Recent Bookings</h3>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Pickup Location</th>
                                    <th>Delivery Location</th>
                                    <th>Pickup Date</th>
                                    <th>Pickup Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_bookings as $booking): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($booking['booking_id']); ?></td>
                                        <td><?= htmlspecialchars($booking['pickup_location']); ?></td>
                                        <td><?= htmlspecialchars($booking['delivery_location']); ?></td>
                                        <td><?= htmlspecialchars($booking['pickup_date']); ?></td>
                                        <td><?= htmlspecialchars($booking['pickup_time']); ?></td>
                                        <td><?= htmlspecialchars($booking['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <script src="../js/dashboard.js" defer></script>
    </div>
</body>

</html>