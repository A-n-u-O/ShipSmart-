<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
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

// Handle courier selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['courier_id'])) {
    try {
        // Store selected courier ID in session
        $_SESSION['current_booking']['courier_id'] = $_POST['courier_id'];

        // Redirect to rates and pricing page
        header('Location: ratesAndPricing.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error selecting courier: " . htmlspecialchars($e->getMessage());
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
    <link rel="stylesheet" href="../css/chooseCourier.css">
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
                        <div class="courier-item">
                            <input type="radio" name="courier_id" id="courier_<?= htmlspecialchars($courier['courier_id']) ?>" value="<?= htmlspecialchars($courier['courier_id']) ?>" required>
                            <label for="courier_<?= htmlspecialchars($courier['courier_id']) ?>">
                                <?= htmlspecialchars($courier['first_name'] . ' ' . $courier['last_name']) ?> - <?= htmlspecialchars($courier['phone_number']) ?>
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