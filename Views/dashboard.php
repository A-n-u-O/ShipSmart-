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
  <title>ShipSmart | Home</title>
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/dashBoard.css">
</head>

<body>
  <?php include './navbar.php'; ?> <!-- Include the reusable navbar -->
  <main>
    <!-- Dashboard Content -->
    <div class="dashboard">
      <div class="overview">
        <h1>Welcome!,
          <span><?= htmlspecialchars($_SESSION['user_email']); ?>!</span> <!--placeholder for username-->

          <p>You are now logged in.</p>
        </h1>
        <h2>Dashboard</h2>
        <div class="stats">
          <div class="stat-item">Active Shipments: 12</div>
          <div class="stat-item">Upcoming Pickups: 3</div>
          <div class="stat-item">Recent Bookings: 8</div>
          <div class="stat-item">Pending Actions: 2</div>
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
  <a href="../logout.php">Logout</a>


</body>

</html>