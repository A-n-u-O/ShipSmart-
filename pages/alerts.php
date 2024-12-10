<?php
session_start();
require_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user’s tracking alerts
try {
    $stmt = $pdo->prepare("SELECT * FROM TrackingAlerts WHERE user_id = ? ORDER BY alert_date DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching alerts: " . htmlspecialchars($e->getMessage()));
}

// Mark alert as read
if (isset($_GET['mark_read'])) {
    $alert_id = $_GET['mark_read'];
    try {
        $update_stmt = $pdo->prepare("UPDATE TrackingAlerts SET is_read = 1 WHERE alert_id = ?");
        $update_stmt->execute([$alert_id]);
        header('Location: trackingAlerts.php');
        exit();
    } catch (Exception $e) {
        die("Error marking alert as read: " . htmlspecialchars($e->getMessage()));
    }
}
function addTrackingAlert($pdo, $userId, $alertMessage)
{
    // Insert the alert into the database
    $stmt = $pdo->prepare("INSERT INTO TrackingAlerts (user_id, alert_message, is_read) VALUES (?, ?, 0)");
    $stmt->execute([$userId, $alertMessage]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Alerts</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/alerts.css">
    <script src="../js/alerts.js" defer></script>
</head>
<body>
    <?php include '../Views/navbar.php'; ?>

    <main class="tracking-alerts">
        <h1>Your Tracking Alerts</h1>

        <?php if (empty($alerts)): ?>
            <p>No tracking alerts available.</p>
        <?php else: ?>
            <div class="alert-list">
                <?php foreach ($alerts as $alert): ?>
                    <div class="alert-item <?= $alert['is_read'] ? 'read' : 'unread' ?>">
                        <p><strong>Alert:</strong> <?= htmlspecialchars($alert['alert_message']); ?></p>
                        <p><em>Received on: <?= htmlspecialchars($alert['alert_date']); ?></em></p>
                        <?php if (!$alert['is_read']): ?>
                            <form action="trackingAlerts.php" method="GET">
                                <button type="submit" class="mark-read-btn" name="mark_read" value="<?= $alert['alert_id'] ?>">
                                    Mark as Read
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
