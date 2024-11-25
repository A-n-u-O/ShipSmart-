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

    <script>
        // Retrieve pickup details from localStorage
        const pickupDetails = JSON.parse(localStorage.getItem("pickupDetails"));
        if (pickupDetails) {
            document.getElementById("scheduled-details").textContent = `Date: ${pickupDetails.date}, Time: ${pickupDetails.time}, Address: ${pickupDetails.address}`;
        }

        document.getElementById("edit-schedule-btn").addEventListener("click", () => {
            window.location.href = "schedulePickup.php"; // Redirect back to the scheduling page
        });
    </script>
</body>
</html>