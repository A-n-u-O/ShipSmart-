<?php
session_start();
require_once 'db_connection.php';

// Fetch available couriers from the database
try {
    $courier_stmt = $pdo->prepare("SELECT courier_id, first_name, last_name, phone_number, available_time, rating, photo_url FROM Couriers WHERE is_available = 1");
    $courier_stmt->execute();
    $couriers = $courier_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching couriers: " . $e->getMessage();
    $couriers = [];
}
?>

<!-- HTML for displaying couriers -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Couriers</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/couriers.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="courier-list">
        <h1>Available Couriers</h1>
        
        <?php if (!empty($couriers)): ?>
            <?php foreach ($couriers as $courier): ?>
                <div class="courier-card" data-courier-id="<?= htmlspecialchars($courier['courier_id']) ?>" data-courier-info='<?= json_encode($courier) ?>'>
                    <img src="<?= htmlspecialchars($courier['photo_url']) ?>" alt="Courier Photo">
                    <h3><?= htmlspecialchars($courier['first_name'] . ' ' . $courier['last_name']) ?></h3>
                    <p>Rating: <?= htmlspecialchars($courier['rating']) ?> ★</p>
                    <button class="view-details-btn">View Details</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No couriers available.</p>
        <?php endif; ?>
    </main>

    <!-- Modal for Courier Details -->
    <div id="courierModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Courier Details</h2>
            <div id="courierDetails">
                <!-- Courier details will be displayed here -->
            </div>
        </div>
    </div>
    <script src="../js/couriers.js" defer ></script>
</body>
</html>