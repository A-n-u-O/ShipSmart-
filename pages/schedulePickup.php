<?php
session_start();  // Start the session to store error messages and other session data
require_once 'db_connection.php';  // Include the database connection file

$error_messages = [];  // Initialize an array to store error messages for validation

// Fetch delivery companies
try {
    $companies_stmt = $pdo->query("SELECT delivery_company_id, company_name, base_rate, weight_factor FROM deliverycompanies");
    $deliverycompanies = $companies_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching delivery companies: " . htmlspecialchars($e->getMessage());
    $deliverycompanies = [];
}

// Fetch active shipping ports from the database
try {
    $ports_stmt = $pdo->query("SELECT port_id, port_name, location FROM ShippingPorts WHERE active = TRUE");
    $shipping_ports = $ports_stmt->fetchAll(PDO::FETCH_ASSOC);  // Store the results in an array
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching shipping ports: " . htmlspecialchars($e->getMessage());
    $shipping_ports = [];
}

// Fetch available couriers along with their delivery company names
try {
    $courier_stmt = $pdo->prepare("
        SELECT c.courier_id, c.first_name, c.last_name, d.company_name 
        FROM Couriers c 
        JOIN deliverycompanies d ON c.fk_delivery_company_id = d.delivery_company_id 
        WHERE c.available = 1
    ");
    $courier_stmt->execute();
    $couriers = $courier_stmt->fetchAll(PDO::FETCH_ASSOC);  // Store couriers in an array
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching couriers: " . htmlspecialchars($e->getMessage());
    $couriers = [];
}

// Fetch destination zones from the database
$destination_zones = [];
try {
    $stmt = $pdo->query("SELECT zone_id, zone_name FROM destination_zones");
    $destination_zones = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Store zones in an array
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching destination zones: " . htmlspecialchars($e->getMessage());
}

// Handle form submission and validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form input values
    $pickup_date = trim($_POST['pickup_date'] ?? '');
    $pickup_time = trim($_POST['pickup_time'] ?? '');
    $pickup_location = trim($_POST['pickup_location'] ?? '');
    $delivery_location = trim($_POST['delivery_location'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $item_description = trim($_POST['item_description'] ?? '');
    $item_weight = trim($_POST['item_weight'] ?? '');
    $destination_zone = trim($_POST['destination_zone'] ?? '');
    $delivery_company_id = trim($_POST['fk_delivery_company_id'] ?? '');
    $courier_id = trim($_POST['courier_id'] ?? '');
    $shipping_port = trim($_POST['shipping_port'] ?? '');

    // Validate pickup date
    if (empty($pickup_date)) {
        $error_messages['pickup_date'] = 'Pickup date is required.';
    } else {
        $today = new DateTime();
        $selected_date = new DateTime($pickup_date);
        if ($selected_date < $today) {
            $error_messages['pickup_date'] = 'Pickup date cannot be in the past.';
        } elseif ($selected_date > (clone $today)->modify('+3 months')) {
            $error_messages['pickup_date'] = 'Pickup date cannot be more than 3 months in the future.';
        }
    }

    // Validate pickup time
    if (empty($pickup_time)) {
        $error_messages['pickup_time'] = 'Pickup time is required.';
    } else {
        $hour = (int)explode(':', $pickup_time)[0];
        if ($hour < 9 || $hour >= 17) {
            $error_messages['pickup_time'] = "Please select a time between 9:00 AM and 5:00 PM.";
        }
    }

    // Validate pickup location
    if (empty($pickup_location)) {
        $error_messages['pickup_location'] = 'Pickup address is required.';
    }

    // Validate delivery location
    if (empty($delivery_location)) {
        $error_messages['delivery_location'] = 'Delivery address is required.';
    }

    // Validate phone number
    if (empty($phone_number)) {
        $error_messages['phone_number'] = 'Phone number is required.';
    } elseif (!preg_match('/^(?:\+234|0)\d{10}$/', $phone_number)) {
        $error_messages['phone_number'] = 'Invalid phone number format. Use +234XXXXXXXXXX or 0XXXXXXXXXX.';
    }

    // Validate item description
    if (empty($item_description)) {
        $error_messages['item_description'] = 'Item description is required.';
    } elseif (str_word_count($item_description) < 3) {
        $error_messages['item_description'] = 'Item description must be at least 3 words.';
    }

    // Validate item weight
    if (empty($item_weight)) {
        $error_messages['item_weight'] = 'Item weight is required.';
    } elseif (!is_numeric($item_weight) || $item_weight <= 0) {
        $error_messages['item_weight'] = 'Item weight must be a positive number.';
    }

    // Validate destination zone selection
    if (empty($destination_zone)) {
        $error_messages['destination_zone'] = 'Destination zone is required.';
    }

    // Validate courier selection
    if (empty($courier_id)) {
        $error_messages['courier_id'] = 'Courier selection is required.';
    }

    // Validate shipping port selection
    if (empty($shipping_port)) {
        $error_messages['shipping_port'] = 'Shipping port is required.';
    }

    // Validate delivery company
    if (empty($delivery_company_id)) {
        $error_messages['fk_delivery_company_id'] = 'Delivery company is required.';
    }

    if (empty($error_messages)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO Bookings 
    (user_id, pickup_date, pickup_time, pickup_location, delivery_location, 
    phone_number, item_description, item_weight, destination_zone, 
    courier_id, shipping_port, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");

            $stmt->execute([
                $_SESSION['user_id'],
                $pickup_date,
                $pickup_time,
                $pickup_location,
                $delivery_location,
                $phone_number,
                $item_description,
                $item_weight,
                $destination_zone,
                $courier_id,
                $shipping_port
            ]);

            // Store booking details in session for future steps
            $_SESSION['current_booking'] = [
                'pickup_date' => $pickup_date,
                'pickup_time' => $pickup_time,
                'pickup_location' => $pickup_location,
                'delivery_location' => $delivery_location,
                'phone_number' => $phone_number,
                'item_description' => $item_description,
                'item_weight' => $item_weight,
                'destination_zone' => $destination_zone,
                'courier' => $courier_id,
                'shipping_port' => $shipping_port,
                'delivery_company_id' => $delivery_company_id
            ];

            $_SESSION['success_message'] = "Pickup scheduled successfully.";
            header('Location: confirmPickup.php');  // Redirect to confirm pickup page
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error processing your request: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Pickup</title>
    <link rel="stylesheet" href="../css/navbar.css"> <!-- Link to the navbar CSS -->
    <link rel="stylesheet" href="../css/schedulePickup.css"> <!-- Link to the schedule pickup CSS -->
</head>

<body>
    <?php include '../Views/navbar.php'; ?> <!-- Include the navbar view -->

    <main class="schedule-pickup-form">
        <h1>Schedule Pickup Details</h1>

        <!-- Display error message if any exist in the session -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message"><?= htmlspecialchars($_SESSION['error_message']); ?></div>
            <?php unset($_SESSION['error_message']); ?> <!-- Clear error message after displaying -->
        <?php endif; ?>

        <form class="scheduling-form" method="POST" action="schedulePickup.php">
            <div class="inputs">
                <div class="form-input">
                    <!-- Pickup date input -->
                    <div class="pickup-date-input">
                        <label for="pickup_date">Pickup Date</label>
                        <input type="date" name="pickup_date" id="pickup_date" value="<?= htmlspecialchars($pickup_date ?? '') ?>">
                        <?= isset($error_messages['pickup_date']) ? '<span class="error">' . $error_messages['pickup_date'] . '</span>' : '' ?>
                    </div>

                    <!-- Pickup time input -->
                    <div class="pickup-time-input">
                        <label for="pickup_time">Pickup Time</label>
                        <input type="time" name="pickup_time" id="pickup_time" value="<?= htmlspecialchars($pickup_time ?? '') ?>">
                        <?= isset($error_messages['pickup_time']) ? '<span class="error">' . $error_messages['pickup_time'] . '</span>' : '' ?>
                    </div>

                    <!-- Pickup location input -->
                    <div class="pickup-location-input">
                        <label for="pickup_location">Pickup Address</label>
                        <input type="text" name="pickup_location" id="pickup_location" value="<?= htmlspecialchars($pickup_location ?? '') ?>">
                        <?= isset($error_messages['pickup_location']) ? '<span class="error">' . $error_messages['pickup_location'] . '</span>' : '' ?>
                    </div>

                    <!-- Delivery location input -->
                    <div class="delivery-location-input">
                        <label for="delivery_location">Delivery Location</label>
                        <input type="text" name="delivery_location" id="delivery_location" value="<?= htmlspecialchars($delivery_location ?? '') ?>">
                        <?= isset($error_messages['delivery_location']) ? '<span class="error">' . $error_messages['delivery_location'] . '</span>' : '' ?>
                    </div>

                    <!-- Phone number input -->
                    <div class="phone-number-input">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" name="phone_number" id="phone_number" value="<?= htmlspecialchars($phone_number ?? '') ?>">
                        <?= isset($error_messages['phone_number']) ? '<span class="error">' . $error_messages['phone_number'] . '</span>' : '' ?>
                    </div>

                    <!-- Item description input -->
                    <div class="item-description-input">
                        <label for="item_description">Item Description</label>
                        <textarea name="item_description" id="item_description" rows="3"><?= htmlspecialchars($item_description ?? '') ?></textarea>
                        <?= isset($error_messages['item_description']) ? '<span class="error">' . $error_messages['item_description'] . '</span>' : '' ?>
                    </div>

                    <!-- Item weight input -->
                    <div class="item-weight-input">
                        <label for="item_weight">Item Weight (kg)</label>
                        <input type="number" name="item_weight" id="item_weight" value="<?= htmlspecialchars($item_weight ?? '') ?>">
                        <?= isset($error_messages['item_weight']) ? '<span class="error">' . $error_messages['item_weight'] . '</span>' : '' ?>
                    </div>

                    <!-- Destination zone input -->
                    <div class="destination-zone-input">
                        <label for="destination_zone">Destination Zone</label>
                        <select name="destination_zone" id="destination_zone">
                            <option value="">Select Zone</option>
                            <?php foreach ($destination_zones as $zone): ?>
                                <option value="<?= $zone['zone_id'] ?>" <?= isset($destination_zone) && $destination_zone == $zone['zone_id'] ? 'selected' : '' ?>><?= htmlspecialchars($zone['zone_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= isset($error_messages['destination_zone']) ? '<span class="error">' . $error_messages['destination_zone'] . '</span>' : '' ?>
                    </div>

                    <div class="delivery-company-input">
                        <label for="fk_delivery_company_id">Delivery Company</label>
                        <select name="fk_delivery_company_id" id="fk_delivery_company_id" onchange="fetchCouriers()">
                            <option value="">Select Delivery Company</option>
                            <?php foreach ($deliverycompanies as $company): ?>
                                <option value="<?= $company['delivery_company_id'] ?>"><?= htmlspecialchars($company['company_name']) ?> <span><?= htmlspecialchars($company['base_rate']) ?> $ per <?= htmlspecialchars($company['weight_factor']) ?></span></option>
                            <?php endforeach; ?>
                        </select>
                        <?= isset($error_messages['fk_delivery_company_id']) ? '<span class="error">' . $error_messages['fk_delivery_company_id'] . '</span>' : '' ?>
                    </div>

                    <div class="courier-input">
                        <label for="courier_id">Courier</label>
                        <select name="courier_id" id="courier_id" required>
                            <option value="">Select a Courier</option>
                            <?php foreach ($couriers as $courier): ?>
                                <option value="<?= $courier['courier_id'] ?>">
                                    <?= htmlspecialchars($courier['first_name'] . ' ' . $courier['last_name'] . ' (' . $courier['company_name'] . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= isset($error_messages['courier_id']) ? '<span class="error">' . $error_messages['courier_id'] . '</span>' : '' ?>
                    </div>

                    <!-- Shipping port input -->
                    <div class="shipping-port-input">
                        <label for="shipping_port">Shipping Port</label>
                        <select name="shipping_port" id="shipping_port">
                            <option value="">Select Port</option>
                            <?php foreach ($shipping_ports as $port): ?>
                                <option value="<?= $port['port_id'] ?>" <?= isset($shipping_port) && $shipping_port == $port['port_id'] ? 'selected' : '' ?>><?= htmlspecialchars($port['port_name']) ?> - <?= htmlspecialchars($port['location']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= isset($error_messages['shipping_port']) ? '<span class="error">' . $error_messages['shipping_port'] . '</span>' : '' ?>
                    </div>
                </div>

                <!-- Submit button -->
                <div class="form-buttons">
                    <button type="submit" class="btn submit-btn">Submit</button>
                </div>
            </div>
        </form>
    </main>
    <script src="../js/schedulePickup.js"></script>
</body>

</html>