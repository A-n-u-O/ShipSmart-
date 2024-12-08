<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "shipsmart";

try {
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("USE $dbname");

    // Columns to check and their definitions
    $columns = [
        "bookings" => [
            "phone_number" => "VARCHAR(20)",
            "item_description" => "TEXT",
            "item_weight" => "DECIMAL(10,2)",
            "courier_id" => "INT",
            "status" => "ENUM('Pending', 'Confirmed', 'Shipped', 'Pickup', 'Delivered') DEFAULT 'Pending'"
        ],
        "couriers" => [
            "rating" => "DECIMAL(3,2) DEFAULT 0.0",
            "contact_info" => "TEXT",
            "available" => "TINYINT(1) DEFAULT 1"
        ]
    ];

    // Iterate over tables and columns
    foreach ($columns as $table => $columnDefinitions) {
        foreach ($columnDefinitions as $column => $definition) {
            // Check if the column exists
            $stmt = $pdo->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
            if ($stmt->rowCount() == 0) {
                try {
                    // Add the column if it doesn't exist
                    $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
                    echo "Column `$column` added to `$table` table.<br>";
                } catch (PDOException $e) {
                    echo "Error adding column `$column` to `$table`: " . $e->getMessage() . "<br>";
                }
            } else {
                echo "Column `$column` already exists in `$table` table.<br>";
            }
        }
    }

    echo "Database schema updated successfully.<br>";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}

// List of countries with shipping ports (could be sourced from a file, API, or manual list)
$countries = [
    'United States', 'China', 'Germany', 'Japan', 'Netherlands', 'South Korea', 'India', 
    'Brazil', 'Italy', 'Russia', 'United Kingdom', 'Canada', 'France', 'Australia', 
    'Spain', 'Belgium', 'Singapore', 'Turkey', 'Mexico', 'UAE', 'Sweden'
];

try {
    // Assuming you already have a PDO connection in $pdo
    // Prepare SQL query to insert country names into the table
    $stmt = $pdo->prepare("INSERT INTO destination_zones (zone_name) VALUES (:zone_name)");

    // Insert each country into the table
    foreach ($countries as $country) {
        $stmt->execute(['zone_name' => $country]);
    }

    echo "Data inserted successfully!";
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}

?>
