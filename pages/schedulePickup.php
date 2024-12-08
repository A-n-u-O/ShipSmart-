<?php
session_start();
require_once 'db_connection.php';

// Initialize error messages array
$error_messages = [];

// Fetch shipping ports from the database
try {
    $ports_stmt = $pdo->query("SELECT port_id, port_name, location FROM ShippingPorts WHERE active = TRUE");
    $shipping_ports = $ports_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching shipping ports: " . htmlspecialchars($e->getMessage());
    $shipping_ports = [];
}

// Fetch couriers from the database
try {
    $courier_stmt = $pdo->prepare("SELECT courier_id, company_name AS name FROM Couriers WHERE available = 1");
    $courier_stmt->execute();
    $couriers = $courier_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching couriers: " . htmlspecialchars($e->getMessage());
    $couriers = [];
}

// Handle courier selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['courier_id'])) {
    try {
        // Store selected courier ID in session
        $_SESSION['current_booking']['courier_id'] = $_POST['courier_id'];

        // Redirect to rates and pricing page
        header('Location: ratesAndPricing.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error selecting courier: " . htmlspecialchars($e->getMessage());
    }
}

// Fetch destination zones (countries) from the database
$destination_zones = [];
try {
    $stmt = $pdo->query("SELECT zone_id, zone_name FROM destination_zones"); // Assuming you have a destination_zones table
    $destination_zones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching destination zones: " . htmlspecialchars($e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup_date = trim($_POST['pickup_date'] ?? '');
    $pickup_time = trim($_POST['pickup_time'] ?? '');
    $pickup_location = trim($_POST['pickup_location'] ?? '');
    $delivery_location = trim($_POST['delivery_location'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $item_description = trim($_POST['item_description'] ?? '');
    $item_weight = trim($_POST['item_weight'] ?? '');
    $destination_zone = trim($_POST['destination_zone'] ?? ''); // Destination zone from the form
    $courier = trim($_POST['courier'] ?? ''); // Courier from the form

    // Validate inputs
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

    if (empty($pickup_time)) {
        $error_messages['pickup_time'] = 'Pickup time is required.';
    } else {
        $hour = (int)explode(':', $pickup_time)[0];
        if ($hour < 9 || $hour >= 17) {
            $error_messages['pickup_time'] = "Please select a time between 9:00 AM and 5:00 PM.";
        }
    }

    if (empty($pickup_location)) {
        $error_messages['pickup_location'] = 'Pickup address is required.';
    }

    if (empty($delivery_location)) {
        $error_messages['delivery_location'] = 'Delivery address is required.';
    }

    if (empty($phone_number)) {
        $error_messages['phone_number'] = 'Phone number is required.';
    } elseif (!preg_match('/^(?:\+234|0)\d{10}$/', $phone_number)) {
        $error_messages['phone_number'] = 'Invalid phone number format. Use +234XXXXXXXXXX or 0XXXXXXXXXX.';
    }

    if (empty($item_description)) {
        $error_messages['item_description'] = 'Item description is required.';
    } elseif (str_word_count($item_description) < 3) {
        $error_messages['item_description'] = 'Item description must be at least 3 words.';
    }

    if (empty($item_weight)) {
        $error_messages['item_weight'] = 'Item weight is required.';
    } elseif (!is_numeric($item_weight) || $item_weight <= 0) {
        $error_messages['item_weight'] = 'Item weight must be a positive number.';
    }

    if (empty($destination_zone)) {
        $error_messages['destination_zone'] = 'Destination zone is required.';
    }

    if (empty($courier)) {
        $error_messages['courier'] = 'Courier selection is required.';
    }
    $shipping_port = trim($_POST['shipping_port'] ?? '');

    if (empty($shipping_port)) {
        $error_messages['shipping_port'] = 'Shipping port is required.';
    }
    // If no errors, process the form and insert data into the database
    if (empty($error_messages)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO Bookings (user_id, pickup_date, pickup_time, pickup_location, delivery_location, phone_number, item_description, item_weight, destination_zone, courier,  shipping_port, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
            $stmt->execute([$_SESSION['user_id'], $pickup_date, $pickup_time, $pickup_location, $delivery_location, $phone_number, $item_description, $item_weight, $destination_zone, $courier, $shipping_port]);

            // Redirect or process further
            $_SESSION['current_booking'] = [
                'pickup_date' => $pickup_date,
                'pickup_time' => $pickup_time,
                'pickup_location' => $pickup_location,
                'delivery_location' => $delivery_location,
                'phone_number' => $phone_number,
                'item_description' => $item_description,
                'item_weight' => $item_weight,
                'destination_zone' => $destination_zone,
                'courier' => $courier,
                'shipping_port' => $shipping_port,
            ];
            $_SESSION['current_booking']['shipping_port'] = $shipping_port;
            header('Location: ratesAndPricing.php');
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
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/schedulePickup.css">
    <script src="../js/schedulePickup.js" defer></script>

</head>

<body>
    <?php include '../Views/navbar.php'; ?>
    <header>
        <p>Schedule Pickup</p>
    </header>

    <main class="schedule-pickup-form">
        <?php if (!empty($error_messages)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($error_messages as $message): ?>
                        <li><?= htmlspecialchars($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="schedulePickup.php" method="POST" class="scheduling-form">
            <div class="inputs">
                <!-- Input fields for pickup details -->
                <div id="pickup_date_input" class="form-input">
                    <label for="pickup_date">Pickup Date</label>
                    <input type="date" id="pickup_date" name="pickup_date" required>
                    <span id="error_date" class="error-message"></span>
                </div>

                <div id="pickup_time_input" class="form-input">
                    <label for="pickup_time">Pickup Time</label>
                    <input type="time" id="pickup_time" name="pickup_time" required>
                    <span id="error_time" class="error-message"></span>
                </div>

                <div id="pickup_address_input" class="form-input">
                    <label for="pickup_location">Pickup Address</label>
                    <textarea id="pickup_location" name="pickup_location" required></textarea>
                    <span id="error_pickup_address" class="error-message"></span>
                </div>

                <div id="delivery_location_input" class="form-input">
                    <label for="delivery_location">Delivery Address</label>
                    <textarea id="delivery_location" name="delivery_location" required></textarea>
                    <span id="error_delivery_location" class="error-message"></span>
                </div>

                <div id="phone_number_input" class="form-input">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" required placeholder="+234XXXXXXXXXX">
                    <span id="error_phone_number" class="error-message"></span>
                </div>

                <div id="item_weight_input" class="form-input">
                    <label for="item_weight">Item Weight (kg)</label>
                    <input type="number" id="item_weight" name="item_weight" required step=".1">
                    <span id='error_item_weight' class='error-message'></span>
                </div>

                <div id="destination_zone_input" class="form-input">
                    <label for="destination_zone">Destination Zone</label>
                    <select id="destination_zone" name="destination_zone" required>
                        <option value="">Select Zone</option>
                        <?php foreach ($destination_zones as $zone): ?>
                            <option value="<?= htmlspecialchars($zone['zone_id']) ?>"><?= htmlspecialchars($zone['zone_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span id="error_destination_zone" class="error-message"></span>
                </div>

                <div id="courier_input" class="form-input">
                    <label for="courier">Select Courier</label>
                    <select id="courier" name="courier" required>
                        <option value="">Select Courier</option>
                        <?php foreach ($couriers as $courier): ?>
                            <option value="<?= htmlspecialchars($courier['courier_id']) ?>"><?= htmlspecialchars($courier['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span id="error_courier" class="error-message"></span>
                </div>
                <div id="shipping_port_input" class="form-input">
                    <label for="shipping_port">Select Shipping Port</label>
                    <select id="shipping_port" name="shipping_port" required>
                        <option value="">Select Shipping Port</option>
                        <?php foreach ($shipping_ports as $port): ?>
                            <option value="<?= htmlspecialchars($port['port_id']) ?>">
                                <?= htmlspecialchars($port['port_name']) ?> (<?= htmlspecialchars($port['location']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span id="error_shipping_port" class="error-message"></span>
                </div>
                <div id='item_description_input' class='form-input'>
                    <label for='item_description'>Item Description</label>
                    <input type='text' id='item_description' name='item_description' required placeholder='At least 3 words'>
                    <span id='error_item_description' class='error-message'></span> <!-- Error message for item description -->
                </div>
            </div>

            <div id="submit_button_input" class="form-input">
                <button type="submit" class="submit-button">Submit</button>
            </div>
        </form>
    </main>

</body>

</html>