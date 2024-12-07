<?php
// Get the current script name (e.g., "dashboard.php")
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<nav>
  <ul>
    <!-- Logo with image and text -->
    <li class="logo">
      <img src="../Assets/images/shipsmartLogoLight.png" alt="ShipSmart-Logo">
      <span>ShipSmart</span>
    </li>

    <!-- Home with sub-menu -->
    <li>
      <a href="../Views/dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
        <img src="../Assets/icons/dashboardIcon.svg" alt="Home Icon" class="nav-icon">
        Dashboard
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/overview.php" class="<?= $current_page == 'overview.php' ? 'active' : '' ?>">
            <img src="../Assets/icons/overviewIcon.svg" alt="Overview Icon" class="nav-icon">Overview</a></li>
        <li><a href="../pages/announcements.php" class="<?= $current_page == 'announcements.php' ? 'active' : '' ?>">
            <img src="../Assets/icons/announcementsIcon.svg" alt="Announcement Icon" class="nav-icon">Announcements</a></li>
        <li><a href="../pages/updates.php" class="<?= $current_page == 'updates.php' ? 'active' : '' ?>">
            <img src="../Assets/icons/updateIcon.svg" alt="Updates Icon" class="nav-icon">Updates</a></li>
      </ul>
    </li>

    <!-- Schedule Pickup with sub-menu -->
    <li>
      <a href="../pages/schedulePickup.php" class="<?= $current_page == 'schedulePickup.php' ? 'active' : '' ?>">
        <img src="../Assets/icons/scheduleIcon.svg" alt="Schedule Icon" class="nav-icon">
        Schedule Pickup
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/confirmPickup.php" class="<?= $current_page == 'confirmPickup.php' ? 'active' : '' ?>">
            <img src="../Assets/icons/confirmIcon.svg" alt="Confirm Icon" class="nav-icon">Confirm Pickup</a></li>
        <li><a href="../pages/scheduledPickups.php" class="<?= $current_page == 'scheduledPickups.php' ? 'active' : '' ?>">
            <img src="../Assets/icons/viewIcon.svg" alt="View Icon" class="nav-icon">View Scheduled Pickups</a></li>
        <li><a href="../pages/editPickupDetails.php" class="<?= $current_page == 'editPickupDetails.php' ? 'active' : '' ?>">
            <img src="../Assets/icons/editIcon.svg" alt="Edit Icon" class="nav-icon">Edit Pickup Details</a></li>
      </ul>
    </li>

    <!-- Track Shipment with sub-menu -->
    <li>
      <a href="../pages/trackShipment.php" class="<?= $current_page == 'trackShipment.php' ? 'active' : '' ?>">
        <img src="../Assets/icons/trackIcon.svg" alt="Track Icon" class="nav-icon">
        Track Shipment
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/realTimeTracking.php" class="<?= $current_page == 'realTimeTracking.php' ? 'active' : '' ?>">
            Real-Time Tracking</a></li>
        <li><a href="../pages/shipmentHistory.php" class="<?= $current_page == 'shipmentHistory.php' ? 'active' : '' ?>">
            Shipment History</a></li>
        <li><a href="../pages/alerts.php" class="<?= $current_page == 'alerts.php' ? 'active' : '' ?>">
            Tracking Alerts</a></li>
      </ul>
    </li>

    <!-- Available Couriers with sub-menu -->
    <li>
      <a href="../pages/couriers.php" class="<?= $current_page == 'couriers.php' ? 'active' : '' ?>">
        <img src="../Assets/icons/courier.svg" alt="Courier Icon" class="nav-icon">
        Available Couriers
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/chooseCourier.php" class="<?= $current_page == 'chooseCourier.php' ? 'active' : '' ?>">
            Choose Courier</a></li>
        <li><a href="../pages/courierProfiles.php" class="<?= $current_page == 'courierProfiles.php' ? 'active' : '' ?>">
            Courier Profiles</a></li>
      </ul>
    </li>

    <!-- Rates & Pricing with sub-menu -->
    <li>
      <a href="../pages/ratesAndPricing.php" class="<?= $current_page == 'ratesAndPricing.php' ? 'active' : '' ?>">
        <img src="../Assets/icons/priceTagIcon.svg" alt="Rates Icon" class="nav-icon">
        Rates & Pricing
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/domesticRates.php" class="<?= $current_page == 'domesticRates.php' ? 'active' : '' ?>">
            Domestic Rates</a></li>
        <li><a href="../pages/internationalRates.php" class="<?= $current_page == 'internationalRates.php' ? 'active' : '' ?>">
            International Rates</a></li>
        <li><a href="../pages/discountsAndOffers.php" class="<?= $current_page == 'discountsAndOffers.php' ? 'active' : '' ?>">
            Discounts & Offers</a></li>
        <li><a href="../pages/domesticRates.php" class="<?= $current_page == 'domesticRates.php' ? 'active' : '' ?>">Domestic Rates</a></li>
        <li><a href="../pages/internationalRates.php" class="<?= $current_page == 'internationalRates.php' ? 'active' : '' ?>">International Rates</a></li>
        <li><a href="../pages/discountsAndOffers.php" class="<?= $current_page == 'discountsAndOffers.php' ? 'active' : '' ?>">Discounts & Offers</a></li>
      </ul>
    </li>

    <!-- Manage Bookings with sub-menu -->
    <li>
      <a href="../pages/manageBookings.php" class="<?= $current_page == 'manageBookings.php' ? 'active' : '' ?>">
        <img src="../Assets/icons/manageIcon.svg" alt="Bookings Icon" class="nav-icon">
        Manage Bookings
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/viewBookings.php" class="<?= $current_page == 'viewBookings.php' ? 'active' : '' ?>">
            View Bookings</a></li>
        <li><a href="../pages/cancelBooking.php" class="<?= $current_page == 'cancelBooking.php' ? 'active' : '' ?>">
            Cancel Booking</a></li>
        <li><a href="../pages/modifyBooking.php" class="<?= $current_page == 'modifyBooking.php' ? 'active' : '' ?>">
            Modify Booking</a></li>
      </ul>
    </li>

    <!-- Support with sub-menu -->
    <li>
      <a href="../pages/support.php" class="<?= $current_page == 'support.php' ? 'active' : '' ?>">
        <img src="../Assets/icons/supportIcon.svg" alt="Support Icon" class="nav-icon">
        Support
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/contactUs.php" class="<?= $current_page == 'contactUs.php' ? 'active' : '' ?>">
            Contact Us</a></li>
        <li><a href="../pages/helpCenter.php" class="<?= $current_page == 'helpCenter.php' ? 'active' : '' ?>">
            Help Center</a></li>
        <li><a href="../pages/faqs.php" class="<?= $current_page == 'faqs.php' ? 'active' : '' ?>">
            FAQs</a></li>
      </ul>
    </li>

    <!-- About Us with sub-menu -->
    <li>
      <a href="../pages/aboutUs.php" class="<?= $current_page == 'aboutUs.php' ? 'active' : '' ?>">
        <img src="../Assets/icons/aboutIcon.svg" alt="About Us Icon" class="nav-icon">
        About Us
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/ourStory.php" class="<?= $current_page == 'ourStory.php' ? 'active' : '' ?>">
            Our Story</a></li>
        <li><a href="../pages/mission.php" class="<?= $current_page == 'mission.php' ? 'active' : '' ?>">
            Mission & Vision</a></li>
        <li><a href="../pages/team.php" class="<?= $current_page == 'team.php' ? 'active' : '' ?>">
            Meet the Team</a></li>
      </ul>
    </li>

    <!-- Account with sub-menu -->
    <li>
      <a href="../pages/account.php" class="<?= $current_page == 'account.php' ? 'active' : '' ?>">
        <img src="../Assets/icons/profileIcon.svg" alt="Account Icon" class="nav-icon">
        Account
        <span class="toggle-icon">▼</span>
      </a>
      <ul class="drop-down">
        <li><a href="../pages/profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">
            Profile</a></li>
        <li><a href="../pages/messages.php" class="<?= $current_page == 'editProfile.php' ? 'active' : '' ?>">
            Messages</a></li>
        <li><a href="../pages/notificationSettings.php" class="<?= $current_page == 'notificationSettings.php' ? 'active' : '' ?>">
            Notifications</a></li>
      </ul>
    </li>
  </ul>

  <!-- Logout section with a button -->
  <div class="logout">
    <a href="../Views/logoutPage.php" class="<?= $current_page == 'logoutPage.php' ? 'active' : '' ?>">
      <img src="../Assets/icons/logoutIcon.svg" alt="Logout Icon" class="nav-icon">Logout
    </a>
  </div>
</nav>

<script src="../js/navbar.js" defer></script>
