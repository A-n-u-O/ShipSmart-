<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Pickup</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/confirmPickup.css"> <!-- Add your CSS file for styling -->
</head>
<body>
    <?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->

    <main class="confirm-pickup">
        <h1>Confirm Your Pickup Details</h1>
        <div class="pickup-details">
            <p id="confirm-date">Date: Not selected</p>
            <p id="confirm-time">Time: Not selected</p>
            <p id="confirm-address">Pickup Address: Not selected</p>
        </div>
        <div class="buttons">
            <button id="confirm-btn">Confirm Pickup</button>
            <button id="edit-btn">Edit Pickup Details</button>
        </div>
        <div class="alert-message"></div>
    </main>

    <script src="../js/confirmPickup.js" defer></script>
</body>
</html>