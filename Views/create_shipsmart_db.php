<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "shipsmart";

try {
    // Connect to the MySQL server
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Use the existing database
    $pdo->exec("USE $dbname");

    // Check if the 'phone_number' column exists in the 'bookings' table
    $stmt = $pdo->query("SHOW COLUMNS FROM bookings LIKE 'phone_number'");
    if ($stmt->rowCount() == 0) {
        // Add the 'phone_number' column if it doesn't exist
        $pdo->exec("ALTER TABLE bookings ADD COLUMN phone_number VARCHAR(20)");
    }

    // Check if the 'item_description' column exists in the 'bookings' table
    $stmt = $pdo->query("SHOW COLUMNS FROM bookings LIKE 'item_description'");
    if ($stmt->rowCount() == 0) {
        // Add the 'item_description' column if it doesn't exist
        $pdo->exec("ALTER TABLE bookings ADD COLUMN item_description TEXT");
    }

    // Check if the 'item_weight' column exists in the 'bookings' table
    $stmt = $pdo->query("SHOW COLUMNS FROM bookings LIKE 'item_weight'");
    if ($stmt->rowCount() == 0) {
        // Add the 'item_weight' column if it doesn't exist
        $pdo->exec("ALTER TABLE bookings ADD COLUMN item_weight DECIMAL(10,2)");
    }

    // Check if the 'rating' column exists in the 'couriers' table
    $stmt = $pdo->query("SHOW COLUMNS FROM couriers LIKE 'rating'");
    if ($stmt->rowCount() == 0) {
        // Add the 'rating' column if it doesn't exist
        $pdo->exec("ALTER TABLE couriers ADD COLUMN rating DECIMAL(3,2) DEFAULT 0.0");
    }

    // Check if the 'contact_info' column exists in the 'couriers' table
    $stmt = $pdo->query("SHOW COLUMNS FROM couriers LIKE 'contact_info'");
    if ($stmt->rowCount() == 0) {
        // Add the 'contact_info' column if it doesn't exist
        $pdo->exec("ALTER TABLE couriers ADD COLUMN contact_info TEXT");
    }

    // Check if the 'available' column exists in the 'couriers' table
    $stmt = $pdo->query("SHOW COLUMNS FROM couriers LIKE 'available'");
    if ($stmt->rowCount() == 0) {
        // Add the 'available' column if it doesn't exist
        $pdo->exec("ALTER TABLE couriers ADD COLUMN available TINYINT(1) DEFAULT 1");
    }

    // Check if the 'courier_id' column exists in the 'notifications' table
    $stmt = $pdo->query("SHOW COLUMNS FROM notifications LIKE 'courier_id'");
    if ($stmt->rowCount() == 0) {
        // Add the 'courier_id' column if it doesn't exist
        $pdo->exec("ALTER TABLE notifications ADD COLUMN courier_id INT AFTER shipment_id");
        $pdo->exec("ALTER TABLE notifications ADD FOREIGN KEY (courier_id) REFERENCES couriers(courier_id)");
    }

    echo "Database schema updated successfully.<br>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
