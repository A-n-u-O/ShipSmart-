<?php
// Include database connection
include '../includes/db.php';

header('Content-Type: application/json');

// Retrieve the POST data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['bookingId'], $data['status'])) {
    $bookingId = $data['bookingId'];
    $status = $data['status'];

    // Update booking status in the database
    $stmt = $pdo->prepare("UPDATE bookings SET status = :status WHERE id = :id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $bookingId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking status updated.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update booking status.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
