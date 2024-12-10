<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the total cost from the query string
$totalCost = isset($_GET['totalCost']) ? (int)$_GET['totalCost'] : 0;

// Generate a random order ID
$orderId = bin2hex(random_bytes(8));

// Save the order ID and total cost in the session
$_SESSION['current_booking']['orderId'] = $orderId;
$_SESSION['current_booking']['totalCost'] = $totalCost;

// Display the payment confirmation page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | Payment | Payment Confirmation</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/paymentConfirmation.css">
</head>
<body>
    <?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->
    <main>
        <div class="payment-confirmation">
            <h1>Payment Confirmation</h1>
            <p><strong>Order ID:</strong> <?= htmlspecialchars($orderId); ?></p>
            <p><strong>Total Cost:</strong> ₦<?= number_format($totalCost, 2); ?></p>
            <p>Thank you for your payment. Your order has been successfully processed.</p>
            <a href="manageBookings.php">Go to My Bookings</a>
        </div>
    </main>
</body>
</html>