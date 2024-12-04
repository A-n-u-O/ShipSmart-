<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id']) && isset($_POST['confirm'])) {
    try {
        $booking_id = $_POST['booking_id'];
        $user_id = $_SESSION['user_id'];

        // Verify the booking belongs to the logged-in user
        $stmt = $pdo->prepare("DELETE FROM Bookings WHERE booking_id = ? AND user_id = ?");
        $stmt->execute([$booking_id, $user_id]);

        $_SESSION['success_message'] = "Pickup canceled successfully.";
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }

    header("Location: scheduledPickups.php");
    exit();
}
?>
