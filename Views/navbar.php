<!-- Reusable navigation bar using php -->

<?php
// Get the current script name (e.g., "dashboard.php")
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<nav>
  <ul>
    <!-- Logo with image and text -->
    <li class="logo">
      <img src="../Assets/images/Shipsmart-icon-dark.png" alt="ShipSmart-Logo">
      <span>ShipSmart</span>
    </li>

    <!-- Home with sub-menu -->
    <li>
      <a href="../Views/dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
        Home
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/overview.php" class="<?= $current_page == 'overview.php' ? 'active' : '' ?>">Overview</a></li>
        <li><a href="../pages/announcements.php" class="<?= $current_page == 'announcements.php' ? 'active' : '' ?>">Announcements</a></li>
        <li><a href="../pages/updates.php" class="<?= $current_page == 'updates.php' ? 'active' : '' ?>">Updates</a></li>
      </ul>
    </li>

    <!-- Schedule Pickup with sub-menu -->
    <li>
      <a href="../pages/schedulePickup.php" class="<?= $current_page == 'schedulePickup.php' ? 'active' : '' ?>">
        Schedule Pickup
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/confirmPickup.php" class="<?= $current_page == 'confirmPickup.php' ? 'active' : '' ?>">Confirm Pickup</a></li>
        <li><a href="../pages/scheduledPickups.php" class="<?= $current_page == 'scheduledPickups.php' ? 'active' : '' ?>">View Scheduled Pickups</a></li>
        <li><a href="../pages/editPickupDetails.php" class="<?= $current_page == 'editPickupDetails.php' ? 'active' : '' ?>">Edit Pickup Details</a></li>
      </ul>
    </li>

    <!-- Track Shipment with sub-menu -->
    <li>
      <a href="../pages/trackShipment.php" class="<?= $current_page == 'trackShipment.php' ? 'active' : '' ?>">
        Track Shipment
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/realTimeTracking.php" class="<?= $current_page == 'realTimeTracking.php' ? 'active' : '' ?>">Real-Time Tracking</a></li>
        <li><a href="../pages/shipmentHistory.php" class="<?= $current_page == 'shipmentHistory.php' ? 'active' : '' ?>">Shipment History</a></li>
        <li><a href="../pages/alerts.php" class="<?= $current_page == 'alerts.php' ? 'active' : '' ?>">Tracking Alerts</a></li>
      </ul>
    </li>

    <!-- Available Couriers with sub-menu -->
    <li>

      <a href="../pages/couriers.php" class="<?= $current_page == 'couriers.php' ? 'active' : '' ?>">
        Available Couriers
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/courierProfiles.php" class="<?= $current_page == 'courierProfiles.php' ? 'active' : '' ?>">Courier Profiles</a></li>
        <li><a href="../pages/courierRatings.php" class="<?= $current_page == 'courierRatings.php' ? 'active' : '' ?>">Courier Ratings</a></li>
        <li><a href="../pages/applyAsCourier.php" class="<?= $current_page == 'applyAsCourier.php' ? 'active' : '' ?>">Apply as Courier</a></li>
      </ul>
    </li>

    <!-- Rates & Pricing with sub-menu -->
    <li>
      <a href="../pages/ratesAndPricing.php" class="<?= $current_page == 'ratesAndPricing.php' ? 'active' : '' ?>">
        Rates & Pricing
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/domesticRates.php" class="<?= $current_page == 'domesticRates.php' ? 'active' : '' ?>">Domestic Rates</a></li>
        <li><a href="../pages/internationalRates.php" class="<?= $current_page == 'internationalRates.php' ? 'active' : '' ?>">International Rates</a></li>
        <li><a href="../pages/discountsAndOffers.php" class="<?= $current_page == 'discountsAndOffers.php' ? 'active' : '' ?>">Discounts & Offers</a></li>
      </ul>
    </li>

    <!-- Manage Bookings with sub-menu -->
    <li>
      <a href="../pages/manageBookings.php" class="<?= $current_page == 'manageBookings.php' ? 'active' : '' ?>">
        Manage Bookings
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/viewBookings.php" class="<?= $current_page == 'viewBookings.php' ? 'active' : '' ?>">View Bookings</a></li>
        <li><a href="../pages/cancelBooking.php" class="<?= $current_page == 'cancelBooking.php' ? 'active' : '' ?>">Cancel Booking</a></li>
        <li><a href="../pages/modifyBooking.php" class="<?= $current_page == 'modifyBooking.php' ? 'active' : '' ?>">Modify Booking</a></li>
      </ul>
    </li>

    <!-- Account with sub-menu -->
    <li>
      <a href="../pages/account.php" class="<?= $current_page == 'account.php' ? 'active' : '' ?>">
        Account
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">Profile</a></li>
        <li><a href="../pages/editProfile.php" class="<?= $current_page == 'editProfile.php' ? 'active' : '' ?>">Edit Profile</a></li>
        <li><a href="../pages/notificationSettings.php" class="<?= $current_page == 'notificationSettings.php' ? 'active' : '' ?>">Notification Settings</a></li>
      </ul>
    </li>

    <!-- Support with sub-menu -->
    <li>
      <a href="../pages/support.php" class="<?= $current_page == 'support.php' ? 'active' : '' ?>">
        Support
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/contactUs.php" class="<?= $current_page == 'contactUs.php' ? 'active' : '' ?>">Contact Us</a></li>
        <li><a href="../pages/helpCenter.php" class="<?= $current_page == 'helpCenter.php' ? 'active' : '' ?>">Help Center</a></li>
        <li><a href="../pages/faqs.php" class="<?= $current_page == 'faqs.php' ? 'active' : '' ?>">FAQs</a></li>
      </ul>
    </li>

    <!-- About Us with sub-menu -->
    <li>
      <a href="../pages/aboutUs.php" class="<?= $current_page == 'aboutUs.php' ? 'active' : '' ?>">
        About Us
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/ourStory.php" class="<?= $current_page == 'ourStory.php' ? 'active' : '' ?>">Our Story</a></li>
        <li><a href="../pages/mission.php" class="<?= $current_page == 'mission.php' ? 'active' : '' ?>">Mission & Vision</a></li>
        <li><a href="../pages/team.php" class="<?= $current_page == 'team.php' ? 'active' : '' ?>">Meet the Team</a></li>
      </ul>
    </li>

    <!-- Logout -->
    <li>
      <a href="./logoutPage.php" class="<?= $current_page == 'logoutPage.php' ? 'active' : '' ?>">Logout</a>
    </li>
  </ul>
</nav>

<!-- JavaScript for toggling -->
<script src="../js/navbar.js"></script>