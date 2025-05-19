<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>High-Converting Landing Page</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .hero-image {
            width: 100%;
            height: auto;
            border-radius: 12px;
            object-fit: cover;
        }
        .cta-button {
            background-color: #3b82f6;
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .cta-button:hover {
            background-color: #2563eb;
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .footer {
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }
         /* Ensure the body takes up the full height */
    html, body {
      height: 100%;
      margin: 0;
    }

    /* Make the container scrollable */
    .scrollable-container {
      height: 100vh; /* Full viewport height */
      overflow-y: auto; /* Enable vertical scrolling */
      padding-bottom: 30px; /* Optional: Add some space at the bottom for footer */
    }

    /* Optional: Make sure the footer stays at the bottom */
    footer {
      position: relative;
      margin-top: 20px;
    }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.cta-button').addEventListener('click', function() {
                window.location.href = 'form.php';
            });
        });
    </script>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-80">
   <!-- Card Container -->
    <div class=" container card shadow-lg border-0 rounded-4 text-center" style="max-width: 500px; width: 60%;">
        <div class="card-body p-5">
            <h1 class="card-title mb-4">👋 Welcome, <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>!</h1>
            <p class="card-text mb-4">You're successfully logged in to your account.</p>
            <a href="logout.php" class="btn btn-danger btn-lg px-5">Logout</a>
        </div>
    </div>
  <!-- Scrollable container for content -->
  <div class="scrollable-container">
    <div class="container py-5">
      <h1 class="display-4 text-center mb-4">Discover the Power of AI Today</h1>
      <h2 class="h5 text-center text-muted mb-5">Unlock next-level productivity with our latest AI tools.</h2>

      <div class="text-center mb-5">
        <img src="https://th.bing.com/th/id/OIP._x0Odjy0zMYjcZQxM4TjdQHaFj?rs=1&pid=ImgDetMain" alt="Hero Image" class="img-fluid rounded">
      </div>

      <div class="text-center mb-5">
        <a href="./form.php" class="btn btn-primary btn-lg px-5">Get Started</a>
      </div>

      <div class="text-center mb-4 small">
        <p><strong>24/7</strong> Customer Support · User-Friendly Interface · Advanced Analytics</p>
        <p><em>"Best AI tool I've ever used!"</em> — Only <strong>5 licenses</strong> left. Offer ends in <strong>2 hours</strong>.</p>
      </div>

      <div class="row g-4 mb-5">
        <div class="col-md-6">
          <div class="bg-light p-4 rounded shadow-sm h-100">
            <h4 class="mb-3">Benefits & Features</h4>
            <ul class="list-unstyled">
              <li>✅ High-quality AI tools</li>
              <li>✅ Increased productivity</li>
              <li>✅ User-friendly interface</li>
            </ul>
          </div>
        </div>
        <div class="col-md-6">
          <div class="bg-light p-4 rounded shadow-sm h-100">
            <h4 class="mb-3">Social Proof</h4>
            <p><strong>“Amazing product!”</strong> Boosted my workflow immensely. – <em>Jane D.</em></p>
            <p><strong>“Game changer!”</strong> A new level of AI productivity. – <em>Mark P.</em></p>
          </div>
        </div>
      </div>

      <div class="bg-light p-4 rounded shadow-sm mb-4">
        <h4 class="mb-3">Urgency & Scarcity</h4>
        <p>⏳ Only <strong>5 licenses</strong> left. Offer ends in <strong>2 hours</strong>.</p>
      </div>

      <div class="bg-light p-4 rounded shadow-sm mb-4">
        <h4 class="mb-3">FAQ</h4>
        <p><strong>Is there a money-back guarantee?</strong> Yes, within 30 days.</p>
        <p><strong>What is the refund policy?</strong> Read our <a href="#" class="link-primary">Privacy Policy</a>.</p>
      </div>

    </div>
  </div>

  <!-- Footer stays at the bottom -->
  <footer class="text-center small text-muted pt-4">
    <p class="mb-1">&copy; 2025 Your Company. All rights reserved.</p>
    <p><a href="#" class="text-decoration-none me-3">Terms & Conditions</a> | <a href="#" class="text-decoration-none">Privacy Policy</a></p>
  </footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>