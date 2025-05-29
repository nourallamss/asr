

    <style>
         .element {
            background-image: url("https://images.squarespace-cdn.com/content/v1/60dd579702d1a17b631cc350/8e0967c7-5602-4430-a19d-8aaabfe845ca/beach-villa_BoAo-Residential-Resort.jpg");
        }

        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #f97316;
            --light-bg: #f8fafc;
            --dark-text: #0f172a;
            --light-text: #64748b;
        }
        
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--dark-text);
            background-color: var(--light-bg);
        }
        
        /* Header */
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            background-color: white;
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        
        .nav-link {
            font-weight: 600;
            color: var(--dark-text);
            margin: 0 0.5rem;
        }
        
        /* Hero Section */
        .hero {
            background-size: cover;
            background-position: center;
            padding: 4rem 0;
            position: relative;
        }
        
        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3));
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            max-width: 600px;
        }
        
        /* Search Box */
        .search-box {
            background-color: white;
            border-radius: 8px;
            padding: 0.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .search-input {
            border: none;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            width: 100%;
            outline: none;
        }
        
        .search-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        
        .search-button:hover {
            background-color: var(--secondary-color);
        }
        
        /* Features Section */
        .features {
            padding: 4rem 0;
        }
        
        .feature-card {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            background-color: rgba(37, 99, 235, 0.1);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .feature-icon i {
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        
        /* Property Section */
        .properties {
            padding: 4rem 0;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title h2 {
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .section-title p {
            color: var(--light-text);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .property-card {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        .property-image {
            height: 200px;
            background-size: cover;
            background-position: center;
        }
        
        .property-content {
            padding: 1.5rem;
        }
        
        .property-price {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .property-address {
            color: var(--light-text);
            margin-bottom: 1rem;
        }
        
        .property-details {
            display: flex;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 1px solid rgba(0,0,0,0.1);
        }
        
        .property-detail {
            display: flex;
            align-items: center;
        }
        
        .property-detail i {
            margin-right: 0.5rem;
            color: var(--light-text);
        }
        
        /* CTA Section */
        .cta {
            padding: 4rem 0;
            background-color: var(--primary-color);
            color: white;
        }
        
        .cta h2 {
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .cta-button {
            background-color: white;
            color: var(--primary-color);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 4px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        
        .cta-button:hover {
            background-color: #f8fafc;
            transform: translateY(-2px);
        }
        
        /* User Welcome Box */
        .welcome-box {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Footer */
        footer {
            background-color: #0f172a;
            color: white;
            padding: 4rem 0 2rem;
        }
        
        .footer-logo {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .footer-links h5 {
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #94a3b8;
        }
        
        .social-links {
            margin-top: 1rem;
        }
        
        .social-links a {
            color: white;
            margin: 0 0.5rem;
            font-size: 1.25rem;
        }
    </style>
     <style>
        /* Property Slider Section */
        .property-slider-section {
            padding: 4rem 0;
            background-color: var(--light-bg);
        }
        
        .property-slider-container {
            position: relative;
            padding: 0 50px;
        }
        
        .property-slider {
            display: flex;
            overflow-x: hidden;
            scroll-behavior: smooth;
            gap: 20px;
            padding: 20px 0;
        }
        
        .property-slide {
            min-width: calc(25% - 15px);
            flex: 0 0 calc(25% - 15px);
            transition: transform 0.3s ease;
        }
        
        .property-card {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            background-color: white;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        .property-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .property-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: var(--accent-color);
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .property-content {
            padding: 1.5rem;
        }
        
        .property-price {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .property-address {
            color: var(--light-text);
            margin-bottom: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .property-details {
            display: flex;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 1px solid rgba(0,0,0,0.1);
        }
        
        .property-detail {
            display: flex;
            align-items: center;
        }
        
        .property-detail i {
            margin-right: 0.5rem;
            color: var(--light-text);
        }
        
        .slider-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: white;
            border-radius: 50%;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: background-color 0.3s ease;
        }
        
        .slider-control:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .slider-prev {
            left: 0;
        }
        
        .slider-next {
            right: 0;
        }
        
        .slider-indicators {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
            gap: 8px;
        }
        
        .slider-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #cbd5e1;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .slider-indicator.active {
            background-color: var(--primary-color);
        }
        
        /* Responsive adjustments */
        @media (max-width: 1199.98px) {
            .property-slide {
                min-width: calc(33.333% - 14px);
                flex: 0 0 calc(33.333% - 14px);
            }
        }
        
        @media (max-width: 991.98px) {
            .property-slide {
                min-width: calc(50% - 10px);
                flex: 0 0 calc(50% - 10px);
            }
        }
        
        @media (max-width: 575.98px) {
            .property-slide {
                min-width: 100%;
                flex: 0 0 100%;
            }
            
            .property-slider-container {
                padding: 0 30px;
            }
        }
    </style>
    <style>
        .property-slider-section {
            padding: 60px 0;
            background-color: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .section-title h2 {
            font-size: 32px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .section-title p {
            font-size: 16px;
            color: #777;
        }
        
        .property-slider-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .property-slider {
            display: flex;
            overflow-x: hidden;
            scroll-behavior: smooth;
            width: 100%;
        }
        
        .property-slide {
            flex: 0 0 33.333%;
            max-width: 33.333%;
            padding: 0 15px;
            transition: transform 0.3s ease;
        }
        
        .property-card {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        
        .property-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .property-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .property-card:hover .property-image img {
            transform: scale(1.05);
        }
        
        .property-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(0, 123, 255, 0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            z-index: 1;
        }
        
        .property-content {
            padding: 20px;
        }
        
        .property-price {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .property-address {
            font-size: 14px;
            color: #777;
            margin-bottom: 15px;
        }
        
        .property-details {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        
        .property-detail {
            display: flex;
            align-items: center;
            color: #555;
        }
        
        .property-detail i {
            margin-right: 5px;
            color: #007bff;
        }
        
        .slider-control {
            background-color: #fff;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #333;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background-color 0.3s ease;
            z-index: 2;
        }
        
        .slider-control:hover {
            background-color: #007bff;
            color: #fff;
        }
        
        .slider-indicators {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        
        .slider-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #ddd;
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .slider-indicator.active {
            background-color: #007bff;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .property-slide {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
        
        @media (max-width: 768px) {
            .property-slide {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
  <?php include "navbar.php";?>

<!-- Welcome Box -->
    <div class="container mt-4">
        <div class="welcome-box">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3>Welcome, <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>!</h3>
                    <p class="mb-0">Continue exploring properties or update your preferences to find your perfect match.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="form.php" class="btn btn-primary">Update Preferences</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero element">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 hero-content">
                    <h1>Find the right home at the right price</h1>
                    <p>Search homes across the country and get personalized recommendations based on your preferences.</p>
                    
                    <!-- Search Box -->
                    <div class="search-box d-flex align-items-center">
                        <div class="flex-grow-1">
                            <input type="text" class="search-input" placeholder="Enter an address, neighborhood, city, or ZIP code">
                        </div>
                        <div>
                            <button class="search-button">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h3>Find Your Home</h3>
                        <p>Browse thousands of listings to find the perfect home for you and your family.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h3>Get a Cash Offer</h3>
                        <p>Sell your home directly to us and skip the hassle of listing and showings.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h3>Connect with Agents</h3>
                        <p>Find top local agents who can help you buy or sell your home.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

  <section class="property-slider-section">
        <div class="container">
            <div class="section-title">
                <h2>Discover More Properties</h2>
                <p>Browse through our selection of handpicked properties just for you</p>
            </div>
            
            <div class="property-slider-container">
                <button class="slider-control slider-prev" id="sliderPrev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="property-slider" id="propertySlider">
                    <!-- Property 1 -->
                    <div class="property-slide">
                        <div class="property-card">
                            <div class="property-image">
                                <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=500&h=300&fit=crop" alt="Lakeview Drive Property">
                                <div class="property-label">New Listing</div>
                            </div>
                            <div class="property-content">
                                <div class="property-price">$729,000</div>
                                <div class="property-address">1214 Lakeview Drive, San Francisco, CA 94103</div>
                                <div class="property-details">
                                    <div class="property-detail">
                                        <i class="fas fa-bed"></i>
                                        <span>4 beds</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-bath"></i>
                                        <span>3 baths</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span>2,800 sqft</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Property 2 -->
                    <div class="property-slide">
                        <div class="property-card">
                            <div class="property-image">
                                <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=500&h=300&fit=crop" alt="Hillside Avenue Property">
                                <div class="property-label featured">Featured</div>
                            </div>
                            <div class="property-content">
                                <div class="property-price">$1,249,000</div>
                                <div class="property-address">87 Hillside Avenue, Seattle, WA 98101</div>
                                <div class="property-details">
                                    <div class="property-detail">
                                        <i class="fas fa-bed"></i>
                                        <span>5 beds</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-bath"></i>
                                        <span>4.5 baths</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span>3,650 sqft</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Property 3 -->
                    <div class="property-slide">
                        <div class="property-card">
                            <div class="property-image">
                                <img src="https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=500&h=300&fit=crop" alt="Maple Street Property">
                                <div class="property-label">Price Reduced</div>
                            </div>
                            <div class="property-content">
                                <div class="property-price">$499,000</div>
                                <div class="property-address">342 Maple Street, Austin, TX 78701</div>
                                <div class="property-details">
                                    <div class="property-detail">
                                        <i class="fas fa-bed"></i>
                                        <span>3 beds</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-bath"></i>
                                        <span>2 baths</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span>1,950 sqft</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Property 4 -->
                    <div class="property-slide">
                        <div class="property-card">
                            <div class="property-image">
                                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=500&h=300&fit=crop" alt="Ocean View Blvd Property">
                            </div>
                            <div class="property-content">
                                <div class="property-price">$875,000</div>
                                <div class="property-address">56 Ocean View Blvd, Miami, FL 33101</div>
                                <div class="property-details">
                                    <div class="property-detail">
                                        <i class="fas fa-bed"></i>
                                        <span>4 beds</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-bath"></i>
                                        <span>3 baths</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span>2,400 sqft</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Property 5 -->
                    <div class="property-slide">
                        <div class="property-card">
                            <div class="property-image">
                                <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=500&h=300&fit=crop" alt="Central Park West Property">
                                <div class="property-label luxury">Luxury</div>
                            </div>
                            <div class="property-content">
                                <div class="property-price">$2,395,000</div>
                                <div class="property-address">789 Central Park West, New York, NY 10023</div>
                                <div class="property-details">
                                    <div class="property-detail">
                                        <i class="fas fa-bed"></i>
                                        <span>6 beds</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-bath"></i>
                                        <span>5 baths</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span>4,800 sqft</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Property 6 -->
                    <div class="property-slide">
                        <div class="property-card">
                            <div class="property-image">
                                <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=500&h=300&fit=crop" alt="College Avenue Property">
                            </div>
                            <div class="property-content">
                                <div class="property-price">$438,500</div>
                                <div class="property-address">420 College Avenue, Boston, MA 02115</div>
                                <div class="property-details">
                                    <div class="property-detail">
                                        <i class="fas fa-bed"></i>
                                        <span>2 beds</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-bath"></i>
                                        <span>2 baths</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span>1,200 sqft</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Property 7 -->
                    <div class="property-slide">
                        <div class="property-card">
                            <div class="property-image">
                                <img src="https://photos.mandarinoriental.com/is/image/MandarinOriental/bodrum-villa-melisa-exterior?wid=4000&fmt=jpeg,rgb&qlt=63,0&op_sharpen=0&resMode=sharp2&op_usm=0,0,0,0&icc=sRGB%20IEC61966-2.1&iccEmbed=1&printRes=72&fit=wrap&qlt=45,0" alt="Mountain View Road Property">
                                <div class="property-label new-build">New Build</div>
                            </div>
                            <div class="property-content">
                                <div class="property-price">$639,000</div>
                                <div class="property-address">1521 Mountain View Road, Denver, CO 80202</div>
                                <div class="property-details">
                                    <div class="property-detail">
                                        <i class="fas fa-bed"></i>
                                        <span>4 beds</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-bath"></i>
                                        <span>3.5 baths</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span>2,350 sqft</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Property 8 -->
                    <div class="property-slide">
                        <div class="property-card">
                            <div class="property-image">
                                <img src="https://th.bing.com/th/id/R.e1b8b3d9a2007efea48d2c7073949d9a?rik=smzQDt4GPfRNmA&pid=ImgRaw&r=0" alt="Sunset Drive Property">
                            </div>
                            <div class="property-content">
                                <div class="property-price">$585,000</div>
                                <div class="property-address">325 Sunset Drive, Portland, OR 97201</div>
                                <div class="property-details">
                                    <div class="property-detail">
                                        <i class="fas fa-bed"></i>
                                        <span>3 beds</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-bath"></i>
                                        <span>2.5 baths</span>
                                    </div>
                                    <div class="property-detail">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span>2,100 sqft</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button class="slider-control slider-next" id="sliderNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="slider-indicators" id="sliderIndicators">
                <!-- Indicators will be generated by JavaScript -->
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('propertySlider');
            const prevBtn = document.getElementById('sliderPrev');
            const nextBtn = document.getElementById('sliderNext');
            const indicatorsContainer = document.getElementById('sliderIndicators');
            
            const slides = document.querySelectorAll('.property-slide');
            let currentIndex = 0;
            let slidesPerView = getSlidesPerView();
            let totalSlides = slides.length;
            let totalGroups = Math.ceil(totalSlides / slidesPerView);
            
            // Create indicators
            for (let i = 0; i < totalGroups; i++) {
                const indicator = document.createElement('div');
                indicator.classList.add('slider-indicator');
                if (i === 0) indicator.classList.add('active');
                indicator.dataset.index = i;
                indicator.addEventListener('click', () => {
                    goToSlide(i * slidesPerView);
                });
                indicatorsContainer.appendChild(indicator);
            }
            
            function getSlidesPerView() {
                if (window.innerWidth >= 1200) return 4;
                if (window.innerWidth >= 992) return 3;
                if (window.innerWidth >= 576) return 2;
                return 1;
            }
            
            function updateSlidesPerView() {
                slidesPerView = getSlidesPerView();
                totalGroups = Math.ceil(totalSlides / slidesPerView);
                
                // Recreate indicators
                indicatorsContainer.innerHTML = '';
                for (let i = 0; i < totalGroups; i++) {
                    const indicator = document.createElement('div');
                    indicator.classList.add('slider-indicator');
                    if (Math.floor(currentIndex / slidesPerView) === i) indicator.classList.add('active');
                    indicator.dataset.index = i;
                    indicator.addEventListener('click', () => {
                        goToSlide(i * slidesPerView);
                    });
                    indicatorsContainer.appendChild(indicator);
                }
                
                // Ensure current index is valid
                if (currentIndex > totalSlides - slidesPerView) {
                    goToSlide(totalSlides - slidesPerView);
                }
            }
            
            function goToSlide(index) {
                if (index < 0) index = 0;
                if (index > totalSlides - slidesPerView) index = totalSlides - slidesPerView;
                
                currentIndex = index;
                const slideWidth = slides[0].offsetWidth + 20; // 20px is the gap
                slider.scrollTo({
                    left: slideWidth * currentIndex,
                    behavior: 'smooth'
                });
                
                // Update indicators
                const indicators = document.querySelectorAll('.slider-indicator');
                indicators.forEach((indicator, i) => {
                    if (Math.floor(currentIndex / slidesPerView) === i) {
                        indicator.classList.add('active');
                    } else {
                        indicator.classList.remove('active');
                    }
                });
            }
            
            prevBtn.addEventListener('click', () => {
                goToSlide(currentIndex - slidesPerView);
            });
            
            nextBtn.addEventListener('click', () => {
                goToSlide(currentIndex + slidesPerView);
            });
            
            // Handle window resize
            window.addEventListener('resize', () => {
                updateSlidesPerView();
            });
            
            // Add touch support
            let touchStartX = 0;
            let touchEndX = 0;
            
            slider.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            slider.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
            
            function handleSwipe() {
                const swipeThreshold = 50;
                if (touchStartX - touchEndX > swipeThreshold) {
                    // Swipe left
                    goToSlide(currentIndex + slidesPerView);
                }
                
                if (touchEndX - touchStartX > swipeThreshold) {
                    // Swipe right
                    goToSlide(currentIndex - slidesPerView);
                }
            }
            
            // Auto slide (optional)
            let autoSlideInterval;
            
            function startAutoSlide() {
                autoSlideInterval = setInterval(() => {
                    const nextIndex = currentIndex + slidesPerView;
                    if (nextIndex >= totalSlides) {
                        // Reset to start if reached the end
                        goToSlide(0);
                    } else {
                        goToSlide(nextIndex);
                    }
                }, 5000); // Change slides every 5 seconds
            }
            
            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
            }
            
            // Start auto sliding
            startAutoSlide();
            
            // Pause auto sliding when user interacts
            slider.addEventListener('mouseenter', stopAutoSlide);
            slider.addEventListener('mouseleave', startAutoSlide);
            slider.addEventListener('touchstart', stopAutoSlide);
            slider.addEventListener('touchend', () => {
                setTimeout(startAutoSlide, 2000);
            });
        });
    </script>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container text-center">
            <h2>Ready to Make the Easy Move?</h2>
            <p class="mb-4">Get a cash offer today. See the ways we can help you sell your home.</p>
            <a href="form.php" class="btn cta-button">Get Started</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="footer-logo">HomeEasy</div>
                    <p>Making it simple to find and secure your perfect home.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>Services</h5>
                        <ul>
                            <li><a href="#">Buy a Home</a></li>
                            <li><a href="#">Sell a Home</a></li>
                            <li><a href="#">Rent a Home</a></li>
                            <li><a href="#">Mortgage</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>About</h5>
                        <ul>
                            <li><a href="#">Our Story</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Press</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="footer-links">
                        <h5>Stay Connected</h5>
                        <p>Subscribe to our newsletter for the latest updates.</p>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Your email">
                            <button class="btn btn-light" type="button">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 HomeEasy. All rights reserved.</p>
                <p>
                    <a href="#" class="text-decoration-none me-3">Terms & Conditions</a> | 
                    <a href="#" class="text-decoration-none">Privacy Policy</a>
                </p>
            </div>
        </div>
    </footer>