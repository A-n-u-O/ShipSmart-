<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and has a current booking
if (!isset($_SESSION['user_id']) || !isset($_SESSION['current_booking'])) {
    header('Location: login.php');
    exit();
}

// Fetch the booking details from session
$courier_id = $_SESSION['current_booking']['courier_id'] ?? null;
$item_weight = $_SESSION['current_booking']['item_weight'] ?? 0; // Get item weight from session

if (!$courier_id) {
    header('Location: confirmPickup.php');
    exit();
}

// Fetch courier details from the database
try {
    $stmt = $pdo->prepare("SELECT c.*, d.company_name 
                           FROM Couriers c
                           JOIN DeliveryCompanies d ON d.delivery_company_id = c.fk_delivery_company_id
                           WHERE c.courier_id = ?");
    $stmt->execute([$courier_id]);
    $courier = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$courier) {
        throw new Exception("Courier not found.");
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching courier details: " . htmlspecialchars($e->getMessage());
    header('Location: confirmPickup.php');
    exit();
}

// Rates calculation logic based on delivery company
$baseRate = 0;
$weightFactor = 0;
$exchangeRate = 780; // Example exchange rate for 1 USD to NGN (update dynamically if possible)

// Example base rates and weight factors (these should be fetched from your database or defined here)
switch ($courier['company_name']) {
    case 'USPS':
        $baseRate = 5.00;
        $weightFactor = 0.50;
        break;
    case 'FedEx':
        $baseRate = 7.50;
        $weightFactor = 0.75;
        break;
    case 'UPS':
        $baseRate = 8.00;
        $weightFactor = 1.00;
        break;
    default:
        $baseRate = 10.00; // Default base rate if no match found
        $weightFactor = 1.00; // Default weight factor
        break;
}

$totalCostUSD = $baseRate + ($item_weight * $weightFactor);
$totalCostNGN = $totalCostUSD * $exchangeRate;

// Fetch port details (if required in booking)
$shipping_port_id = $_SESSION['current_booking']['shipping_port'] ?? null; // Use null coalescing operator

try {
    if ($shipping_port_id) {
        $port_stmt = $pdo->prepare("SELECT port_name, location FROM ShippingPorts WHERE port_id = ?");
        $port_stmt->execute([$shipping_port_id]);
        $port_details = $port_stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $port_details = ['port_name' => 'Unknown', 'location' => 'Unknown'];
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching port details: " . htmlspecialchars($e->getMessage());
    $port_details = ['port_name' => 'Unknown', 'location' => 'Unknown'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Rates and Pricing</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/ratesAndPricing.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->

    <h1>Shipping Rates Calculator</h1>

    <div id="results">
        <h2>Shipping Details</h2>
        <p><strong>Courier:</strong> <?= htmlspecialchars($courier['first_name'] . ' ' . $courier['last_name']); ?></p>
        <p><strong>Delivery Company:</strong> <?= htmlspecialchars($courier['company_name']); ?></p>
        <p><strong>Item Weight:</strong> <?= htmlspecialchars($item_weight); ?> kg</p>
        
        <h3>Cost Breakdown</h3>
        <ul>
            <li><strong>Base Rate:</strong> $<?= number_format($baseRate, 2); ?></li>
            <li><strong>Weight Factor:</strong> $<?= number_format($weightFactor, 2); ?> per kg</li>
            <li><strong>Weight of Item:</strong> <?= htmlspecialchars($item_weight); ?> kg</li>
            <li><strong>Weight Cost:</strong> $<?= number_format($item_weight * $weightFactor, 2); ?></li>
        </ul>
        
        <p><strong>Total Cost:</strong> 
            $<?= number_format($totalCostUSD, 2); ?> (₦<?= number_format($totalCostNGN, 2); ?>)
        </p>
        
        <h3>Shipping Port Details</h3>
        <p><strong>Shipping Port:</strong> <?= htmlspecialchars($port_details['port_name'] ?? 'Unknown'); ?> 
            (<?= htmlspecialchars($port_details['location'] ?? 'Unknown'); ?>)
        </p>
    </div>

    <!-- Button to proceed to payment -->
    <form method="POST" action="payment.php">
        <button type="submit">Proceed to Payment</button>
    </form>

    <script src="../js/ratesAndPricing.js"></script> <!-- Include JavaScript file -->
</body>
</html>
