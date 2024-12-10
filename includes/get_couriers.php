<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_GET['delivery_company_id'])) {
    echo json_encode([]);
    exit;
}

$delivery_company_id = $_GET['delivery_company_id'];

try {
    $stmt = $pdo->prepare("SELECT 
        courier_id, 
        first_name, 
        last_name, 
        rating 
    FROM Couriers 
    WHERE fk_delivery_company_id = ? AND available = 1");
    
    $stmt->execute([$delivery_company_id]);
    $couriers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($couriers);
} catch (Exception $e) {
    error_log("Error fetching couriers: " . $e->getMessage());
    echo json_encode([]);
}