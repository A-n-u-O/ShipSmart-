<?php
namespace ShipSmart\Notifications;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailNotifier {
    private $mail;

    public function __construct() {
        // Load PHPMailer
        require_once '../phpmailer/src/Exception.php';
        require_once '../phpmailer/src/PHPMailer.php';
        require_once '../phpmailer/src/SMTP.php';

        $this->mail = new PHPMailer(true);
        $this->configureSMTP();
    }

    private function configureSMTP() {
        try {
            // SMTP Configuration - use environment variables in production
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'oyaniyie@gmail.com'; // Use environment variable
            $this->mail->Password = 'jurk ppqm apps qpvb'; // Use environment variable
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;
        } catch (Exception $e) {
            error_log("SMTP Configuration Error: " . $e->getMessage());
        }
    }

    public function sendOrderConfirmation($customerEmail, $customerName, $orderId, $orderDetails = []) {
        try {
            // Reset previous email configuration
            $this->mail->clearAllRecipients();
            $this->mail->clearAttachments();

            // Email settings
            $this->mail->setFrom('oyaniyie@gmail.com', 'ShipSmart Order Confirmation');
            $this->mail->addAddress($customerEmail, $customerName);
            $this->mail->isHTML(true); // Allow HTML emails

            // Compose email subject
            $subject = "Order Confirmation: #$orderId";
            $this->mail->Subject = $subject;

            // Create HTML email body
            $body = $this->createOrderConfirmationBody($customerName, $orderId, $orderDetails);
            $this->mail->Body = $body;

            // Send email
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email Send Error: " . $e->getMessage());
            return false;
        }
    }

    private function createOrderConfirmationBody($customerName, $orderId, $orderDetails) {
        $html = "
        <html>
        <body>
            <h2>Dear $customerName,</h2>
            <p>Thank you for your order with ShipSmart!</p>
            
            <h3>Order Details:</h3>
            <p><strong>Order ID:</strong> $orderId</p>";
        
        // Add additional order details if provided
        if (!empty($orderDetails)) {
            $html .= "<ul>";
            foreach ($orderDetails as $key => $value) {
                $html .= "<li><strong>$key:</strong> $value</li>";
            }
            $html .= "</ul>";
        }
        
        $html .= "
            <p>We will notify you once your shipment is on its way.</p>
            <p>Best regards,<br>ShipSmart Team</p>
        </body>
        </html>";
        
        return $html;
    }

    // Additional methods for different types of notifications can be added here
}

// Usage example
// $notifier = new EmailNotifier();
// $notifier->sendOrderConfirmation('customer@example.com', 'John Doe', '12345', [
//     'Shipping Method' => 'Express',
//     'Total Cost' => '$50.00'
// ]);
?>