<?php
namespace ShipSmart\Alerts;

require_once 'db_connection.php';

class TrackingAlertManager {
    private $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Create a new tracking alert for a user
     * 
     * @param int $userId User ID
     * @param string $message Alert message
     * @param string $type Alert type (optional)
     * @return bool Success status
     */
    public function createAlert($userId, $message, $type = 'general') {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO TrackingAlerts (user_id, alert_message, alert_type, is_read, alert_date) 
                 VALUES (?, ?, ?, 0, NOW())"
            );
            return $stmt->execute([$userId, $message, $type]);
        } catch (\Exception $e) {
            error_log("Alert Creation Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch alerts for a specific user
     * 
     * @param int $userId User ID
     * @param bool $onlyUnread Whether to fetch only unread alerts
     * @return array Alerts
     */
    public function getUserAlerts($userId, $onlyUnread = false) {
        try {
            $query = "SELECT * FROM TrackingAlerts WHERE user_id = ?";
            if ($onlyUnread) {
                $query .= " AND is_read = 0";
            }
            $query .= " ORDER BY alert_date DESC";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            error_log("Alert Fetch Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mark a specific alert as read
     * 
     * @param int $alertId Alert ID
     * @return bool Success status
     */
    public function markAlertAsRead($alertId) {
        try {
            $stmt = $this->pdo->prepare(
                "UPDATE TrackingAlerts SET is_read = 1 WHERE alert_id = ?"
            );
            return $stmt->execute([$alertId]);
        } catch (\Exception $e) {
            error_log("Alert Read Mark Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark all alerts for a user as read
     * 
     * @param int $userId User ID
     * @return bool Success status
     */
    public function markAllAlertsAsRead($userId) {
        try {
            $stmt = $this->pdo->prepare(
                "UPDATE TrackingAlerts SET is_read = 1 WHERE user_id = ?"
            );
            return $stmt->execute([$userId]);
        } catch (\Exception $e) {
            error_log("Mark All Alerts Read Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete old alerts
     * 
     * @param int $userId User ID
     * @param int $daysOld Number of days to keep alerts
     * @return bool Success status
     */
    public function cleanupOldAlerts($userId, $daysOld = 30) {
        try {
            $stmt = $this->pdo->prepare(
                "DELETE FROM TrackingAlerts 
                 WHERE user_id = ? AND is_read = 1 
                 AND alert_date < DATE_SUB(NOW(), INTERVAL ? DAY)"
            );
            return $stmt->execute([$userId, $daysOld]);
        } catch (\Exception $e) {
            error_log("Alert Cleanup Error: " . $e->getMessage());
            return false;
        }
    }
}

// Usage example
// $alertManager = new TrackingAlertManager($pdo);
// $alertManager->createAlert($userId, "Your package #12345 is out for delivery");
// $unreadAlerts = $alertManager->getUserAlerts($userId, true);
?>