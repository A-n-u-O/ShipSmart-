<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShipSmart | Dashboard</title>
  <script src="../js/notification.js" defer></script>

  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/notification.css">

</head>

<body>
  <?php include './navbar.php'; ?> <!-- Include the reusable navbar -->

  <div class="container">
    <?php if (isset($_SESSION['login_message'])) { ?>
      <div class="notification" id="notification">
        <?= htmlspecialchars($_SESSION['login_message']); ?>
        <script>
          setTimeout(function() {
            document.getElementById("notification").classList.add("hide");
          }, 5000); // Hide the notification after 5 seconds
        </script>
      </div>
      <?php unset($_SESSION['login_message']); // Clear the message after displaying it 
      ?>
    <?php } ?>

    <main>
      <!-- Dashboard Content -->
      <div class="dashboard">
        <div class="overview">
          <h1>Welcome, <span><?= htmlspecialchars($_SESSION['user_email']); ?>!</span></h1>
          <h2>Your Dashboard</h2>
          <div class="stats">
            <div class="stat-item">Active Shipments: <strong>12</strong></div>
            <div class="stat-item">Upcoming Pickups: <strong>3</strong></div>
            <div class="stat-item">Recent Bookings: <strong>8</strong></div>
            <div class="stat-item">Pending Actions: <strong>2</strong></div>
          </div>
        </div>

        <div class="quick-actions">
          <h3>Quick Actions</h3>
          <ul>
            <li><a href="../pages/schedulePickup.php">Schedule Pickup</a></li>
            <li><a href="../pages/trackShipment.php">Track Shipment</a></li>
            <li><a href="../pages/manageBookings.php">Manage Bookings</a></li>
          </ul>
        </div>
      </div>
    </main>
    <!-- <a href="../logout.php">Logout</a> -->

</body>

</html>