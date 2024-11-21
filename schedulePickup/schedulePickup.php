<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ShipSmart | Schedule Pickup</title>
    <link rel="stylesheet" href="../public/navbar.css">
    <link rel="stylesheet" href="./schedulePickup.css">
  </head>
  <body>
  <?php include '../public/navbar.php'; ?> <!-- Include the reusable navbar -->

  
   <!-- Page Content -->
   <div class="schedule-pickup-container">
      <h1>Schedule a Pickup</h1>
      <form class="pickup-form">
        <label for="pickup-location">Pickup Location</label>
        <input type="text" id="pickup-location" placeholder="Enter your address" required>

        <div class="pickup-details">
          <label for="pickup-date">Pickup Date</label>
          <input type="date" id="pickup-date" required>

          <label for="pickup-time">Pickup Time</label>
          <input type="time" id="pickup-time" required>
        </div>

        <label for="special-instructions">Special Instructions</label>
        <textarea id="special-instructions" placeholder="Any special instructions" rows="4"></textarea>

        <button type="submit" class="submit-btn">Schedule Pickup</button>
      </form>

      <div class="track-shipment">
        <a href="/ShipmentApp/pages/trackShipment.php">Track your shipment</a>
      </div>

      <p class="confirmation-note">You will receive a confirmation notification once your pickup is scheduled.</p>
    </div>
  </body>
</html>
