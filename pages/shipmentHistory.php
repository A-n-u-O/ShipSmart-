<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user's shipment history
try {
    $stmt = $pdo->prepare("SELECT * FROM ShipmentHistory WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $shipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching shipment history: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment History</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/shipmentHistory.css">
    <script src="../js/shipmentHistory.js" defer></script>
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="shipment-history">
        <h1>Your Shipment History</h1>

        <?php if (empty($shipments)): ?>
            <p>No shipment history found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Tracking ID</th>
                        <th>Status</th>
                        <th>Pickup Date</th>
                        <th>Delivery Date</th>
                        <th>Delivery Location</th>
                        <th>Courier</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($shipments as $shipment): ?>
                        <tr>
                            <td><?= htmlspecialchars($shipment['tracking_id']); ?></td>
                            <td><?= htmlspecialchars($shipment['shipment_status']); ?></td>
                            <td><?= htmlspecialchars($shipment['pickup_date']); ?></td>
                            <td><?= htmlspecialchars($shipment['delivery_date']); ?></td>
                            <td><?= htmlspecialchars($shipment['delivery_location']); ?></td>
                            <td><?= htmlspecialchars($shipment['courier_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>
