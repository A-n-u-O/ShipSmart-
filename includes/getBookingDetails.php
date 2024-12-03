<?php
include '../config/database.php';

if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);

    // Fetch booking details
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = :booking_id");
    $stmt->execute(['booking_id' => $booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($booking) {
        echo json_encode([
            'success' => true,
            'booking_id' => $booking['booking_id'],
            'pickup_location' => $booking['pickup_location'],
            'delivery_location' => $booking['delivery_location'],
            'pickup_date' => $booking['pickup_date'],
            'status' => $booking['status']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Booking not found.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No booking ID provided.']);
}
