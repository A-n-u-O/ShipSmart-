<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "shipsmart";

try {
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the new database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "Database 'shipsmart' created successfully.<br>";

    // Switch to the new database
    $pdo->exec("USE $dbname");

    // Create the Users table
    $createUsersTable = "CREATE TABLE IF NOT EXISTS Users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        address TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($createUsersTable);
    echo "Table 'Users' created successfully.<br>";

    // Create the Couriers table
    $createCouriersTable = "CREATE TABLE IF NOT EXISTS Couriers (
        courier_id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        phone_number VARCHAR(15) NOT NULL,
        available_time TIME NOT NULL,
        is_available BOOLEAN DEFAULT TRUE, -- Availability column
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($createCouriersTable);
    echo "Table 'Couriers' created successfully.<br>";

    // Create the Bookings table
    $createBookingsTable = "CREATE TABLE IF NOT EXISTS Bookings (
        booking_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        courier_id INT,
        pickup_location TEXT NOT NULL,
        delivery_location TEXT NOT NULL,
        pickup_date DATE NOT NULL,
        pickup_time TIME NOT NULL,
        status ENUM('Pending', 'Confirmed', 'In Transit', 'Delivered', 'Cancelled') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES Users(user_id),
        FOREIGN KEY (courier_id) REFERENCES Couriers(courier_id)
    )";
    $pdo->exec($createBookingsTable);
    echo "Table 'Bookings' created successfully.<br>";

    // Create the Shipments table
    $createShipmentsTable = "CREATE TABLE IF NOT EXISTS Shipments (
        shipment_id INT AUTO_INCREMENT PRIMARY KEY,
        booking_id INT,
        tracking_number VARCHAR(50) UNIQUE NOT NULL,
        current_status ENUM('Pending', 'Picked Up', 'In Transit', 'Out for Delivery', 'Delivered', 'Delayed') DEFAULT 'Pending',
        current_location TEXT,
        estimated_delivery DATETIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES Bookings(booking_id)
    )";
    $pdo->exec($createShipmentsTable);
    echo "Table 'Shipments' created successfully.<br>";

    // Create the Rates table
    $createRatesTable = "CREATE TABLE IF NOT EXISTS Rates (
        rate_id INT AUTO_INCREMENT PRIMARY KEY,
        courier_id INT,
        weight_min DECIMAL(10,2),
        weight_max DECIMAL(10,2),
        base_rate DECIMAL(10,2) NOT NULL,
        additional_rate DECIMAL(10,2),
        FOREIGN KEY (courier_id) REFERENCES Couriers(courier_id)
    )";
    $pdo->exec($createRatesTable);
    echo "Table 'Rates' created successfully.<br>";

    // Create the Notifications table
    $createNotificationsTable = "CREATE TABLE IF NOT EXISTS Notifications (
        notification_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        shipment_id INT,
        type ENUM('Booking', 'Shipment Status', 'Delivery', 'Support') NOT NULL,
        message TEXT NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES Users(user_id),
        FOREIGN KEY (shipment_id) REFERENCES Shipments(shipment_id)
    )";
    $pdo->exec($createNotificationsTable);
    echo "Table 'Notifications' created successfully.<br>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
