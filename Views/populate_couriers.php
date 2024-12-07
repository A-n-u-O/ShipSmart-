<?php
require_once '../config/database.php';

try {
    // Prepare the insert statement with all required and optional fields
    $stmt = $pdo->prepare("
        INSERT INTO Couriers (first_name, last_name, available_time, is_available, available, rating, contact_info) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    // Sample data with all required and optional fields
    $couriers = [
        ['David', 'Mark', '09:00:00', 1, 1, 100.00, 'john.doe@example.com'],
        ['Berverly', 'Smith', '10:00:00', 1, 1, 500.00, 'jane.smith@example.com'],
        ['Michael', 'Jones', '11:00:00', 0, 1, 20.00, 'michael.brown@example.com'],
    ];

    foreach ($couriers as $courier) {
        $stmt->execute($courier);
    }

    echo "Sample couriers added successfully.";
} catch (PDOException $e) {
    // Log detailed error message
    error_log("Database error: " . $e->getMessage());
    echo "Error occurred while adding couriers: " . htmlspecialchars($e->getMessage());
}?>