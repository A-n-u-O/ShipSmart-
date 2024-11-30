<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShipSmart Landing Page</title>
    <link rel="stylesheet" href="../css/landingPage.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="header-company">
            <img src="../Assets/images/Shipsmart-icon-light.png" alt="ShipSmart Logo" class="footer-logo">
            <h1>ShipSmart</h1>
        </div>
        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#pricing">Pricing</a></li>
            <li><a href="#company">Company</a></li>
            <li><a href="#blog">Blog</a></li>
            <li><a href="#contact">Contact Us</a></li>
        </ul>
        <a href="./login.php" class="login-button">Login</a>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-content">
            <h1>Fastest and most reliable delivery service</h1>
            <p>Experience unparalleled speed and reliability in shipping your packages worldwide.</p>
            <button class="track-button"> <a href="./signup.php">Track Shipment</a> </button>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="features">
        <h2>Our Services</h2>
        <div class="feature-container">
            <div class="feature">
                <div class="feature-text">
                    <h3><b>Shipment Scheduling and Pickup</b></h3>
                    <p>Easily book pickup slots with real-time calendar updates, avoiding double bookings.</p>
                    <a href="#" class="learn-more">Learn More</a>
                </div>
                <img src="https://th.bing.com/th/id/R.57dfcddf35fc4bcc3b0fe9690c56fc20?rik=7CA6CGodpu2erQ&pid=ImgRaw&r=0"
                    alt="Feature 1" />
            </div>
            <div class="feature">
                <div class="feature-text">
                    <h3><b>Real-Time Shipment</b></h3>
                    <p>Track shipment status live, viewing location and delivery progress for transparency.</p>
                    <a href="#" class="learn-more">Learn More</a>
                </div>
                <img src="https://thumbs.dreamstime.com/b/aerial-drone-view-container-cargo-ship-sea-aerial-drone-view-container-cargo-ship-sea-ai-generated-293458778.jpg"
                    alt="Feature 2" />
            </div>
            <div class="feature">
                <div class="feature-text">
                    <h3><b>Courier and Rate Comparison</b></h3>
                    <p>Compare couriers by ratings and rates for domestic and international shipping.</p>
                    <a href="#" class="learn-more">Learn More</a>
                </div>
                <img src="https://thumbs.dreamstime.com/b/truck-road-aerial-view-freight-transporter-straight-roadway-plain-countryside-landscape-drone-pov-143816295.jpg"
                    alt="Feature 3" />
            </div>

        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <h2>Hear From Our Awesome Users</h2>
        <div class="testimonial-container">
            <div class="testimonial">
                <img src="../Assets/images/WhatsApp Image 2024-11-25 at 16.13.20_131445a3.jpg" alt="User  1">
                <h3>Luffy Richard</h3>
                <p>"ShipSmart's services are incredibly reliable. My shipments always arrive on time, and the support is
                    top-notch!"</p>
            </div>
            <div class="testimonial">
                <img src="data:image/jpeg;base64,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"
                    alt="User  2">
                <h3>Michelle Spring</h3>
                <p>"The real-time tracking feature is a game-changer. I always know where my shipments are, and the
                    process is so seamless."</p>
            </div>
            <div class="testimonial">
                <img src="data:image/jpeg;base64,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"
                    alt="User   3">
                <h3>Tobi Rama</h3>
                <p>"Comparing couriers has never been this easy. I love the transparency and simplicity ShipSmart
                    provides!"</p>
            </div>
            <div class="testimonial">
                <img src="https://th.bing.com/th/id/OIP.zIfcsTIm5mhnC7DakUUf7wHaHa?w=168&h=180&c=7&r=0&o=5&pid=1.7"
                    alt="User   3">
                <h3>Isaac Tarka</h3>
                <p>"I love ShipSmart !"</p>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta">
        <h1>Ready to get started?</h1>
        <p>Join ShipSmart today to streamline your shipping process and enjoy hassle-free logistics.</p>
        <button class="cta-button">Get Started</button>
    </section>
</body>
<footer class="footer">
    <div class="container">
        <!-- Subscribe Section -->
        <div class="subscribe-section">
            <p>Subscribe to our <span class="highlight">newsletter</span></p>
            <form class="subscribe-form">
                <input type="email" placeholder="Input your email" class="subscribe-input" required>
                <button type="submit" class="subscribe-button">Subscribe</button>
            </form>
        </div>

        <!-- Main Footer Content -->
        <div class="row footer-content">
            <!-- Footer Taskbar -->
            <div class="footer-taskbar">
                <div class="footer-company">
                    <img src="../Assets/images/Shipsmart-icon-dark.png" alt="ShipSma Logo" class="footer-logo">
                    <span>ShipSmart</span>
                </div>
                <ul class="nav">
                    <li><a href="#">Pricing</a></li>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Contact us</a></li>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>

            <!-- Privacy Section -->
            <div class="footer-legal">
                <p>
                    © 2024 • <a href="#">Privacy</a> • <a href="#">Terms</a> • <a href="#">Sitemap</a>
                </p>
            </div>

            <!-- Social Icons -->

            <ul class="social-links">
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                <li><a href="#"><i class="fab fa-youtube"></i></a></li>
            </ul>
        </div>
    </div>
</footer>
<!-- The defer attribute in a <script> tag is used to indicate that the script should be executed after the HTML document has been completely 
    parsed. -->
<script src="../js/landingPage.js" defer></script>


</html>