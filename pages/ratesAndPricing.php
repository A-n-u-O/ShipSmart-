<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in and has a current booking
if (!isset($_SESSION['user_id']) || !isset($_SESSION['current_booking'])) {
    header('Location: login.php');
    exit();
}

// Fetch selected courier details from session
$courier_id = $_SESSION['current_booking']['courier_id'] ?? null;
$item_weight = $_SESSION['current_booking']['item_weight'] ?? 0; // Get item weight from session

if (!$courier_id) {
    header('Location: chooseCourier.php');
    exit();
}

try {
    // Fetch courier details from database
    $stmt = $pdo->prepare("SELECT * FROM Couriers WHERE courier_id = ?");
    $stmt->execute([$courier_id]);
    $courier = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$courier) {
        throw new Exception("Courier not found.");
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching courier details: " . htmlspecialchars($e->getMessage());
}

// Rates calculation logic based on courier's rates
$baseRate = 0;
$weightFactor = 0;

// Example base rates and weight factors (these should be fetched from your database or defined here)
switch ($courier['first_name']) { // Assuming first_name is used to determine rates for simplicity
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

$totalCost = $baseRate + ($item_weight * $weightFactor);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
      <p><strong>Item Weight:</strong> <?= htmlspecialchars($item_weight); ?> kg</p>
      <p><strong>Total Cost:</strong> $<?= number_format($totalCost, 2); ?></p>
  </div>

  <!-- Optionally add a button to confirm booking -->
  <form method="POST" action="confirmPickup.php">
      <button type="submit">Confirm Booking</button>
  </form>

<script src="../js/ratesAndPricing.js"></script> <!-- Include JavaScript file -->
</body>
</html>