<?php
require_once '../config/database.php';

try {
    $stmt = $pdo->prepare("INSERT INTO Couriers (first_name, last_name, phone_number, available_time) VALUES (?, ?, ?, ?)");
    
    // Sample data
    $couriers = [
        ['John', 'Doe', '1234567890', '09:00:00'],
        ['Jane', 'Smith', '0987654321', '10:00:00'],
        ['Michael', 'Brown', '5551234567', '11:00:00'],
    ];

    foreach ($couriers as $courier) {
        $stmt->execute($courier);
    }

    echo "Sample couriers added successfully.";
} catch (PDOException $e) {
    error_log($e->getMessage()); // Log error instead of displaying
    echo "Error occurred while adding couriers.";
}
