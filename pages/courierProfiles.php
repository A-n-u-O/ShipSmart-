<?php

session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$couriers = [
    'FedEx' => [
        'logo' => 'fedex_logo.png',
        'description' => 'FedEx is a global courier delivery services company offering express transportation, freight services, and logistics solutions.'
    ],
    'DHL Express' => [
        'logo' => 'dhl_logo.png',
        'description' => 'DHL Express is an international logistics company providing express mail services, specializing in global parcel delivery.'
    ],
    'GIG Logistics' => [
        'logo' => 'gig_logo.png',
        'description' => 'GIG Logistics is a Nigerian-based logistics company offering same-day delivery services across Africa.'
    ],
    'UPS' => [
        'logo' => 'ups_logo.png',
        'description' => 'UPS is an American multinational shipping and receiving logistics company offering parcel delivery services.'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Courier Profiles</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/courierProfiles.css">
</head>

<body>
    <?php include '../Views/navbar.php'; ?>

    <h1>Courier Profiles</h1>

    <div class="courier-container">
        <?php foreach ($couriers as $courierName => $courier): ?>
            <div class="courier-card">
                <img src="../Assets/images/<?= $courier['logo']; ?>" alt="<?= $courierName; ?> Logo" class="courier-logo">
                <h2><?= $courierName; ?></h2>
                <p><?= $courier['description']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="../js/courierProfiles.js"></script>
</body>

</html>
