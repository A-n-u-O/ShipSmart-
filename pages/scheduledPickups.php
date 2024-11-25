<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Pickups</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/scheduledPickups.css"> <!-- Add your CSS file for styling -->
</head>
<body>
    <?php include '../partials/navbar.php'; ?> <!-- Include the reusable navbar -->

    <main class="scheduled-pickups">
        <h1>Your Scheduled Pickups</h1>
        <div class="pickup-list">
            <!-- This section will be populated with scheduled pickups -->
            <p id="scheduled-details">No pickups scheduled yet.</p>
        </div>
        <button id="edit-schedule-btn">Edit Pickup Details</button>
    </main>

    <script src="../js/scheduledPickups.js" defer></script>
</body>
</html>