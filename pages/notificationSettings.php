<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Account | Notification Settings</title>
    <link rel="stylesheet" href="../css/navbar.css">
</head>
<body>
<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Load PHPMailer
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
// customers information
$customerEmail = "oluwatomisinoyaniyi@gmail.com";
$orderId = "12345";
$customerName = "John Doe";
//composing email content
$subject = "Order Confirmation: $orderId";
$message = "Dear $customerName,\n\nThank you for your order! 
Your Order ID is $orderId.\n\nWe will notify you once the shipment
 is on its way.\n\nBest regards,\nYour Courier Service";
 //phpmailer instance 
 $mail = new PHPMailer(true);
 try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'oyaniyie@gmail.com'; // Your Gmail address
    $mail->Password = 'jurk ppqm apps qpvb';   // Your Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    // Email settings
    $mail->setFrom('oyaniyie@gmail.com', 'ORDER CONFIRMATION');
    $mail->addAddress($customerEmail, $customerName);
    $mail->isHTML(false); // Use plain text
    $mail->Subject = $subject;
    $mail->Body    = $message;
    // Send email
    $mail->SMTPDebug = 2; // 1 or 2 to enable debugging
    $mail->send();
    echo "Email sent successfully!";
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
 ?> 
 <?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->
</body>
</html>
