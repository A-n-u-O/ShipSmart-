<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart | Overview</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/overview.css">
</head>

<body>
    <?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->
    <div class="container">
        <h1>Welcome to ShipSmart</h1>
        <p>Your ultimate solution for hassle-free shipping and tracking. Explore the features that make us the smart choice for your logistics needs.</p>

        <section class="features">
            <div class="feature-card">
                <h3>Real-Time Tracking</h3>
                <p>Monitor your shipments every step of the way.</p>
                <a href="trackShipment.php">Learn More</a>
            </div>
            <div class="feature-card">
                <h3>Easy Scheduling</h3>
                <p>Set up pickups effortlessly with a few clicks.</p>
                <a href="schedulePickup.php">Schedule Now</a>
            </div>
            <div class="feature-card">
                <h3>Transparent Pricing</h3>
                <p>Get clear, competitive rates for all your shipments.</p>
                <a href="ratesAndPricing.php">View Rates</a>
            </div>
        </section>

        <section class="testimonials">
            <h2>What Our Users Say</h2>
            <div class="testimonial">
                <p>"ShipSmart made it incredibly easy to schedule and track my shipments. Highly recommend!"</p>
                <small>- Jane Doe</small>
            </div>
            <div class="testimonial">
                <p>"Fantastic service and user-friendly platform. Saved me so much time and hassle."</p>
                <small>- John Smith</small>
            </div>
        </section>
    </div>
</body>

</html>