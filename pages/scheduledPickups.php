<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM Bookings WHERE user_id = ? AND status = 'Scheduled' ORDER BY pickup_date, pickup_time");
    $stmt->execute([$user_id]);
    $scheduled_pickups = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
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
    <script src="../js/scheduledPickups.js"></script>
</head>
<body>
    <?php include '../Views/navbar.php'; ?>
    <main>
        <h1>Scheduled Pickups</h1>
        <?php if (!empty($scheduled_pickups)): ?>
            <?php foreach ($scheduled_pickups as $pickup): ?>
                <div class="pickup-item">
                    <p><strong>Date:</strong> <?= htmlspecialchars($pickup['pickup_date']); ?></p>
                    <p><strong>Time:</strong> <?= htmlspecialchars($pickup['pickup_time']); ?></p>
                    <p><strong>Pickup:</strong> <?= htmlspecialchars($pickup['pickup_location']); ?></p>
                    <p><strong>Delivery:</strong> <?= htmlspecialchars($pickup['delivery_location']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No scheduled pickups.</p>
        <?php endif; ?>
    </main>
</body>
</html>