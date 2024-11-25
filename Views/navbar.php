<!-- Reusable navigation bar using php -->

<?php
// Get the current script name (e.g., "dashboard.php")
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<nav>
  <ul>
    <!-- Logo with image and text -->
    <li class="logo">
      <img src="../Assets/images/Image.png" alt="ShipSmart-Logo">
      <span>ShipSmart</span>
    </li>

    <!-- menu items -->
    <li>
      <a href="../Views/dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
        Home
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li>
          <a href="../pages/overview.php" class="<?= $current_page == 'overview.php' ? 'active' : '' ?>">Overview</a>
        </li>
        <li><a href="../pages/announcements.php" class="<?= $current_page == 'announcements.php' ? 'active' : '' ?>">Announcements</a></li>
        <li><a href="../pages/updates.php" class="<?= $current_page == 'updates.php' ? 'active' : '' ?>">Updates</a></li>
      </ul>
    </li>
    <li>
      <a href="../pages/schedulePickup.php" class="<?= $current_page == 'schedulePickup.php' ? 'active' : '' ?>">Schedule Pickup <span class="toggle-icon">▼</span></a>
      <ul class="drop-down">
        <li><a href="../pages/confirmPickup.php" class="<?= $current_page == 'bookAPickup.php' ? 'active' : '' ?>">Confirm Pickup</a></li>
        <li><a href="../pages/scheduledPickups.php" class="<?= $current_page == 'scheduledPickups.php' ? 'active' : '' ?>">View Scheduled Pickups</a></li>
        <li><a href="../pages/editPickupDetails.php" class="<?= $current_page == 'editPickupDetails.php' ? 'active' : '' ?>">Edit Pickup Details</a></li>
      </ul>
    </li>
    <li>
      <a href="../pages/trackShipment.php" class="<?= $current_page == 'trackShipment.php' ? 'active' : '' ?>">Track Shipment</a>
    </li>
    <li>
      <a href="" class="<?= $current_page == 'couriers.php' ? 'active' : '' ?>">Available Couriers</a>
    </li>
    <li>
      <a href="../pages/ratesAndPricing.php" class="<?= $current_page == 'ratesAndPricing.php' ? 'active' : '' ?>">Rates & Pricing</a>
    </li>
    <li>
      <a href="../pages/manageBookings.php" class="<?= $current_page == 'manageBookings.php' ? 'active' : '' ?>">Manage Bookings</a>
    </li>
    <li>
      <a href="../pages/account.php" class="<?= $current_page == 'account.php' ? 'active' : '' ?>">Account <span class="toggle-icon">▼</span></a>
      <ul class="drop-down">
        <li><a href="" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">Profile</a></li>
        <li><a href="../pages/notificationSettings.php" class="<?= $current_page == 'notificationSettings.php' ? 'active' : '' ?>">Notification Settings</a></li>
      </ul>
    </li>
    <li>
      <a href="../pages/support.php" class="<?= $current_page == 'support.php' ? 'active' : '' ?>">Support <span class="toggle-icon">▼</span></a>
      <ul class="drop-down">
        <li><a href="../pages/contactUs.php" class="<?= $current_page == 'contactUs.php' ? 'active' : '' ?>">Contact Us</a></li>
      </ul>
    </li>
    <li>
      <a href="../pages/aboutUs.php" class="<?= $current_page == 'aboutUs.php' ? 'active' : '' ?>">About Us</a>
    </li>

    <li>
      <a href="../pages/logoutPage.php">Logout</a>
    </li>
  </ul>
</nav>

<!-- javascript for toggle -->
<script src="../js/navbar.js"></script>