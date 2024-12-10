<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Track shipment if tracking ID is provided
$shipment = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tracking_id'])) {
    try {
        $tracking_id = $_POST['tracking_id'];

        $stmt = $pdo->prepare("SELECT * FROM Shipments WHERE tracking_id = ? AND user_id = ?");
        $stmt->execute([$tracking_id, $_SESSION['user_id']]);
        $shipment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$shipment) {
            $_SESSION['error_message'] = "Shipment not found with the provided tracking ID.";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error fetching shipment details: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Shipment</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/trackShipment.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="track-shipment">
        <h1>Track Your Shipment</h1>

        <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="error-message" role="alert">
                <?= htmlspecialchars($_SESSION['error_message']); ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <form action="trackShipment.php" method="POST">
            <div class="form-group">
                <label for="tracking_id">Enter Your Tracking ID:</label>
                <input type="text" id="tracking_id" name="tracking_id" required placeholder="Tracking ID">
            </div>

            <button type="submit" class="submit-btn">Track Shipment</button>
        </form>

        <?php if ($shipment): ?>
            <div class="shipment-info">
                <h2>Shipment Details</h2>
                <p><strong>Tracking ID:</strong> <?= htmlspecialchars($shipment['tracking_id']); ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($shipment['current_status']); ?></p>
                <p><strong>Pickup Location:</strong> <?= htmlspecialchars($shipment['pickup_location']); ?></p>
                <p><strong>Delivery Location:</strong> <?= htmlspecialchars($shipment['delivery_location']); ?></p>
                <p><strong>Estimated Delivery Date:</strong> <?= htmlspecialchars($shipment['estimated_delivery_date']); ?></p>
            </div>
        <?php endif; ?>
    </main>
    <script src="../js/trackShipment.js" defer></script>
</body>
</html>
