<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Account | Profile</title>
    <link rel="stylesheet" href="../public/navbar.css">
    <link rel="stylesheet" href="./profile.css">
</head>
<body>
<?php include '../partials/navbar.php'; ?> <!-- Include the reusable navbar -->
<div class="profile-page">
  <div class="profile-header">
    <!-- Profile Picture and Info Section -->
    <div class="profile-info">
      <div class="profile-picture">
        <img src="user-image.jpg" alt="Profile Picture">
        <button class="edit-button">Edit</button>
      </div>
      <div class="user-details">
        <h1>John Doe</h1>
        <p>Email: john.doe@example.com</p>
        <p>Phone: 123-456-7890</p>
        <button class="edit-button">Edit Profile</button>
      </div>
    </div>
  </div>

  <div class="account-settings">
    <h2>Account Settings</h2>
    <div class="setting-item">
      <h3>Change Password</h3>
      <button>Edit</button>
    </div>
    <div class="setting-item">
      <h3>Notification Preferences</h3>
      <button>Edit</button>
    </div>
    <div class="setting-item">
      <h3>Privacy Settings</h3>
      <button>Edit</button>
    </div>
    <div class="setting-item">
      <h3>Theme & Appearance</h3>
      <button>Change</button>
    </div>
  </div>

  <div class="activity-section">
    <h2>Recent Activity</h2>
    <ul>
      <li>Logged in on November 19, 2024</li>
      <li>Updated profile picture</li>
      <li>Changed password on November 18, 2024</li>
    </ul>
  </div>

  <div class="footer">
    <button class="logout-button">Logout</button>
  </div>
</div>
</body>
</html>