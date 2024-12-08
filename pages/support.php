<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShipSmart | Support</title>
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/support.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
  <?php include '../Views/navbar.php'; ?>

  <!-- Hero Section -->
  <header class="hero">
    <div class="hero-content">
      <h1>Welcome to ShipSmart Help & Support</h1>
      <p>Your one-stop destination for all assistance and guidance.</p>
    </div>
  </header>

  <!-- Help Center Section -->
  <section id="help-center" class="help-section">
    <h2>Help Center</h2>
    <div class="help-categories">
      <div class="card">
        <div class="bg"></div>
        <div class="blob"></div>
        <div class="help-item">
          <h3>Getting Started</h3>
          <p>Quick guides for new users.</p>
          <a href="#">Learn More</a>
        </div>
      </div>
      <div class="card">
        <div class="bg"></div>
        <div class="blob"></div>
        <div class="help-item">
          <h3>Using ShipSmart</h3>
          <p>Explore features like shipment tracking and courier comparisons.</p>
          <a href="#">Explore Features</a>
        </div>
      </div>
      <div class="card">
        <div class="bg"></div>
        <div class="blob"></div>
        <div class="help-item">
          <h3>Troubleshooting</h3>
          <p>Find solutions to common issues.</p>
          <a href="#">Get Help</a>
        </div>
      </div>
      <div class="card">
        <div class="bg"></div>
        <div class="blob"></div>
        <div class="help-item">
          <h3>Account Management</h3>
          <p>Manage your ShipSmart account.</p>
          <a href="#">Manage Account</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Us Section -->
  <section id="contact-us" class="contact-section">
    <div class="contact-content">
      <div class="contact-text">
        <h2>Contact Us</h2>
        <p class="catchy-text">We're here to help! Reach out to us anytime.
        <div class="face">
          <p class="v-index">II
          </p>
          <p class="h-index">II
          </p>
          <div class="hand">
            <div class="hand">
              <div class="hour"></div>
              <div class="minute"></div>
              <div class="second"></div>
            </div>
          </div>
        </div>
        </p>
      </div>
      <div class="contact-form-container">
        <div class="form-box"> <!-- Added a form box -->
          <form class="contact-form">
            <input type="text" placeholder="Your Name" required>
            <input type="email" placeholder="Your Email" required>
            <input type="text" placeholder="Subject" required>
            <textarea placeholder="Your Message" rows="5" required></textarea>
            <button type="submit">Send Message</button>
          </form>
          <div class="contact-info">
            <p><strong>Email:</strong> support@shipsmart.com</p>
            <p><strong>Phone:</strong> 1-800-555-0123</p>
            <p><strong>Address:</strong> 123 ShipSmart Lane, Logistics City, LS 54321</p>
          </div>
        </div> <!-- End of form box -->
      </div>
    </div>
  </section>

  <!-- FAQs Section -->
  <section id="faqs" class="faq-section">
    <h2>FAQs</h2>
    <div class="faq-item">
      <h3>What is ShipSmart?</h3>
      <p>ShipSmart is a reliable shipping service providing worldwide logistics solutions.</p>
    </div>
    <div class="faq-item">
      <h3>How do I track my shipment?</h3>
      <p>You can track your shipment using the "Track Shipment" button on the homepage.</p>
    </div>
    <div class="faq-item">
      <h3>What payment methods are accepted?</h3>
      <p>We accept credit/debit cards, PayPal, and direct bank transfers.</p>
    </div>
  </section>
  <script src="../js/landingPage.js"></script>

</body>


</html>