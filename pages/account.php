<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
  // Redirect to login page if the session data is missing
  header("Location: ../Views/login.php");
  exit;
}

// Include the database connection
include '../config/database.php';

// Get the current user's ID from the session
$user_id = $_SESSION['user_id']; ?>

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
        <img class="profile-picture" src="../Assets/icons/userIcon.svg" alt="Profile Picture">
      </div>
      <div class="user-details">
        <h1><?= htmlspecialchars($_SESSION['username']); ?></h1>
        <p><?= htmlspecialchars($_SESSION['user_email']); ?></p>
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
  </div>

  </div>
</body>

</html>