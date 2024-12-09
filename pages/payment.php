<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    try {
        $stmt = $pdo->prepare("
            UPDATE Bookings 
            SET status = 'In Progress' 
            WHERE booking_id = ? AND user_id = ?
        ");
        $stmt->execute([$booking_id, $_SESSION['user_id']]);

        $_SESSION['success_message'] = "Payment confirmed! Booking status updated to 'In Progress'.";
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Failed to update booking status: " . $e->getMessage();
    }

    header('Location: manageBookings.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | Payment Page</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/payment.css">
</head>

<body>
    <?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->
    <main>
        <div class="payment-container">
            <div class="payment-header">
                <h2>Payment Details</h2>
                <p>Complete your secure payment</p>
            </div>
            <form id="paymentForm" class="payment-form">
                <div class="form-group">
                    <label for="cardName">Cardholder Name</label>
                    <input type="text" id="cardName" name="cardName" required
                        placeholder="Enter name as on card">
                    <div id="cardNameError" class="error"></div>
                </div>

                <div class="form-group">
                    <label for="cardNumber">Card Number</label>
                    <input type="text" id="cardNumber" name="cardNumber" required
                        placeholder="1234 5678 9012 3456" maxlength="19">
                    <div id="cardNumberError" class="error"></div>
                </div>

                <div class="card-details">
                    <div class="form-group">
                        <label for="expiryDate">Expiry Date</label>
                        <input type="text" id="expiryDate" name="expiryDate" required
                            placeholder="MM/YY" maxlength="5">
                        <div id="expiryError" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" required
                            placeholder="123" maxlength="3">
                        <div id="cvvError" class="error"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="amount">Payment Amount</label>
                    <input type="number" id="amount" name="amount" required
                        placeholder="Enter amount">
                    <div id="amountError" class="error"></div>
                </div>

                <form action="paymentConfirmation.php" method="POST"  class="payment-btn">
                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking['booking_id']); ?>">
                    <button type="submit">Make Payment</button>
                </form>

            </form>
        </div>
    </main>
    <script src="../js/payment.js"></script>
</body>

</html>