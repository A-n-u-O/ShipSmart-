<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShipSmart | Schedule Pickup</title>
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/schedulePickup.css">
</head>

<body>
  <?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  ?>
  <?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->

  <!-- Page Content -->
  <main class="schedule-pickup">

    <div class="company-info">
      <div class="company-profile">
        <img src="../Assets/images/Image.png" alt="company logo" class="company-logo">
        <h2>ShipSmart Shipping</h2>
        <div class="company-details">
          <p>Logistics support</p>
          <p>Mon-Fri, 8:30-11:00 AM</p>
        </div>
      </div>
    </div>

    <div class="schedule-pickup-container">
      <div class="detail-choosing">
        <div class="calendar-container">
          <div class="calendar-header">
            <button class="nav-btn">‹</button>
            <span> Month year</span>
            <button class="nav-btn">›</button>
          </div>
          <table class="calendar">
            <thead>
              <tr>
                <th>MON</th>
                <th>TUE</th>
                <th>WED</th>
                <th>THU</th>
                <th>FRI</th>
                <th>SAT</th>
                <th>SUN</th>
              </tr>
            </thead>
            <tbody>
              <!-- Calendar days will be populated by JavaScript -->
            </tbody>
          </table>
        </div>

        <div class="time-slots">
          <h3>Select Pickup Time</h3>
          <div class="slots">
            <button class="time-btn">8:30 AM</button>
            <button class="time-btn">8:45 AM</button>
            <button class="time-btn">9:00 AM</button>
            <button class="time-btn">9:15 AM</button>
            <button class="time-btn">9:30 AM</button>
            <button class="time-btn">9:45 AM</button>
            <button class="time-btn">10:00 AM</button>
            <button class="time-btn">10:15 AM</button>
            <button class="time-btn">10:30 AM</button>
            <button class="time-btn">10:45 AM</button>
            <button class="time-btn">11:00 AM</button>
          </div>
        </div>

        <div class="pickup-address">
          <h3>Pickup Address</h3>
          <input type="text" id="pickup-address-input" placeholder="Enter pickup address" autocomplete="off" />
          <button id="submit-address-btn">Submit</button> <!-- Moved the button here -->
        </div>
      </div>

      <div class="pickup-details">
        <h3>Pickup Details: </h3>
        <div class="selected-details">
          <p id="selected-date">Date</p>
          <p id="selected-time">Time</p>
          <p id="selected-options">Address: no address</p>
        </div>
        <button class="confirm-pickup-btn">Confirm Pickup</button>
      </div>

      <div class="alert-message"></div>
    </div>
  </main>
</body>
<script src="../js/schedulePickup.js" defer></script>

</html>