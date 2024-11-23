<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Account | Profile</title>
    <link rel="stylesheet" href="../public/css/profile.css">
    <link rel="stylesheet" href="../public/css/profile2.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
</head>
<body>
    <!-- Include the reusable navbar -->
    <?php include '../includes/navbar.php'; ?>

    <div class="profile-page">
        <!-- Profile Header Section -->
        <header class="profile-header">
            <div class="profile-info">
                <!-- Profile Picture and Info Section -->
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
        </header>

        <!-- Account Settings Section -->
        <section class="account-settings">
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
        </section>

        <!-- Recent Activity Section -->
        <section class="activity-section">
            <h2>Recent Activity</h2>
            <ul>
                <li>Logged in on November 19, 2024</li>
                <li>Updated profile picture</li>
                <li>Changed password on November 18, 2024</li>
            </ul>
        </section>

        <!-- Footer Section -->
        <footer class="footer">
            <button class="logout-button">Logout</button>
        </footer>
    </div>
</body>
</html>
