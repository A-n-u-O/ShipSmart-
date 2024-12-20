<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

try {
    $courier_stmt = $pdo->prepare("SELECT courier_id, company_name, rating FROM Couriers WHERE available = 1");
    $courier_stmt->execute();
    $couriers = $courier_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching couriers: " . htmlspecialchars($e->getMessage());
    $couriers = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['courier_id'])) {
    try {
        $_SESSION['current_booking']['courier_id'] = $_POST['courier_id'];

        header('Location: ratesAndPricing.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error selecting courier: " . htmlspecialchars($e->getMessage());
    }
}

function getCourierLogo($courierName) {
    switch (strtolower($courierName)) {
        case 'fedex':
            return 'fedex.png';
        case 'dhl express':
            return 'dhl_express.png';
        case 'gig logistics':
            return 'gig_logistics.png';
        case 'ups':
            return 'ups.png';
        default:
            return 'default_logo.png';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Courier</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/couriers.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="choose-courier">
        <h1>Select Courier</h1>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message" role="alert">
                <?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="courier-options">
                <?php if (!empty($couriers)): ?>
                    <?php foreach ($couriers as $courier): ?>
                        <?php
                            $courierLogo = getCourierLogo($courier['company_name']);
                        ?>
                        <div class="courier-item">
                            <input type="radio" name="courier_id" id="courier_<?= htmlspecialchars($courier['courier_id']) ?>" value="<?= htmlspecialchars($courier['courier_id']) ?>" required>
                            <label for="courier_<?= htmlspecialchars($courier['courier_id']) ?>" class="courier-item">
                                <img src="../images/logos/<?= htmlspecialchars($courierLogo) ?>" alt="<?= htmlspecialchars($courier['company_name']) ?> Logo" class="courier-logo">
                                <div class="courier-info">
                                    <h3><?= htmlspecialchars($courier['company_name']) ?></h3>
                                    <p><?= htmlspecialchars($courier['rating']) ?></p>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No available couriers at this time.</p>
                <?php endif; ?>
            </div>

            <button type="submit" class="select-courier-btn">Select Courier</button>
        </form>

        <div class="actions">
            <a href="schedulePickup.php" class="back-btn">Back to Schedule</a>
        </div>
    </main>
</body>
</html>
