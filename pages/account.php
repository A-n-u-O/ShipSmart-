<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Account | Profile</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/account.css">
</head>
<body>
<?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->
<div class="profile-page">
  <div class="profile-header">
    <!-- Profile Picture and Info Section -->
    <div class="profile-info">
      <div class="profile-picture">
        <img src="user-image.jpg" alt="Profile Picture">
        <button class="edit-button">Edit</button>
      </div>
      <div class="user-details">
        <h1>User 1</h1>
        <p>Email: user1@gmail.com</p>
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

</div>
</body>
</html>