<?php
/**
 * Property Details View Page
 * Displays detailed information about a specific property
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$host = 'localhost';
$db = 'forfree';
$user = 'root';
$pass = '';

// Initialize variables
$property = null;
$error = null;

// Check if property ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $error = "Property ID is required.";
} else {
    $property_id = intval($_GET['id']);
    
    try {
        // Connect to database
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        // Fetch property details
        $stmt = $pdo->prepare("SELECT * FROM data WHERE id = ?");
        $stmt->execute([$property_id]);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$property) {
            $error = "Property not found.";
        }
        
    } catch (PDOException $e) {
        $error = "Database error: Unable to load property details.";
        error_log("Database error: " . $e->getMessage());
    }
}

// Handle add to cart action
if (isset($_POST['add_to_cart']) && $property) {
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if property is already in cart
    $already_in_cart = false;
    foreach ($_SESSION['cart'] as $item) {
        if ($item['id'] == $property['id']) {
            $already_in_cart = true;
            break;
        }
    }
    
    if (!$already_in_cart) {
        // Add property to cart
        $_SESSION['cart'][] = [
            'id' => $property['id'],
            'location' => $property['location'],
            'price' => $property['sale'],
            'image' => $property['images']
        ];
        $cart_success = "Property added to cart successfully!";
    } else {
        $cart_error = "This property is already in your cart.";
    }
}

// If there's an error, display error page
if ($error) {
    $content = "
    <div class='row justify-content-center'>
        <div class='col-md-8'>
            <div class='alert alert-danger text-center'>
                <i class='fas fa-exclamation-triangle fa-3x mb-3'></i>
                <h4>Error Loading Property</h4>
                <p>$error</p>
                <a href='?page=buy' class='btn btn-primary mt-3'>
                    <i class='fas fa-arrow-left me-2'></i>Back to Properties
                </a>
            </div>
        </div>
    </div>";
    
    renderPage("Property Not Found | HomeEasy", $content);
    exit;
}

// Format property data for display
$formatted_price = number_format(floatval($property['sale']), 2);
$date = new DateTime($property['created_at']);
$formatted_date = $date->format('F j, Y \a\t g:i A');
$phone = !empty($property['phone']) ? htmlspecialchars($property['phone']) : 'Not provided';
$email = !empty($property['email']) ? htmlspecialchars($property['email']) : 'Not provided';
$location = htmlspecialchars($property['location']);
$rooms = intval($property['rooms']);
$bathrooms = intval($property['bathrooms']);
$space = floatval($property['space']);
$description = !empty($property['description']) ? htmlspecialchars($property['description']) : '';

// Handle image display
$images = htmlspecialchars($property['images'] ?? '');
$has_image = !empty($images) && file_exists($images);

// Check if current user owns this property
$is_owner = isset($_SESSION['email']) && $_SESSION['email'] === $property['email'];

// Check if property is in cart
$in_cart = false;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if ($item['id'] == $property['id']) {
            $in_cart = true;
            break;
        }
    }
}

// Build the content
ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="?page=sell">Sell</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $location; ?></li>
    </ol>
</nav>

<!-- Cart Messages -->
<?php if (isset($cart_success)): ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $cart_success; ?>
            <a href="?page=cart" class="alert-link ms-2">View Cart</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (isset($cart_error)): ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $cart_error; ?>
            <a href="?page=cart" class="alert-link ms-2">View Cart</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <!-- Property Image -->
    <div class="col-lg-8 mb-4">
        <?php if ($has_image): ?>
            <div class="property-main-image">
                <img src="<?php echo $images; ?>" 
                     class="img-fluid rounded shadow" 
                     alt="Property in <?php echo $location; ?>"
                     style="width: 100%; height: 400px; object-fit: cover;">
            </div>
        <?php else: ?>
            <div class="property-placeholder bg-light rounded shadow d-flex align-items-center justify-content-center"
                 style="width: 100%; height: 400px;">
                <div class="text-center text-muted">
                    <i class="fas fa-image fa-3x mb-3"></i>
                    <p>No image available</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Property Details -->
    <div class="col-lg-4">
        <div class="property-details-card bg-white rounded shadow p-4 h-100">
            <h1 class="h3 mb-3 text-primary"><?php echo $location; ?></h1>
            
            <div class="price-display mb-4">
                <span class="h2 text-success fw-bold">$<?php echo $formatted_price; ?></span>
            </div>
            
            <!-- Property Features -->
            <div class="property-features mb-4">
                <div class="row g-3">
                    <div class="col-4 text-center">
                        <div class="feature-item p-3 bg-light rounded">
                            <i class="fas fa-bed fa-2x text-primary mb-2"></i>
                            <div class="fw-bold"><?php echo $rooms; ?></div>
                            <small class="text-muted">Bedrooms</small>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="feature-item p-3 bg-light rounded">
                            <i class="fas fa-bath fa-2x text-primary mb-2"></i>
                            <div class="fw-bold"><?php echo $bathrooms; ?></div>
                            <small class="text-muted">Bathrooms</small>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="feature-item p-3 bg-light rounded">
                            <i class="fas fa-ruler-combined fa-2x text-primary mb-2"></i>
                            <div class="fw-bold"><?php echo number_format($space); ?></div>
                            <small class="text-muted">m²</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="contact-info mb-4">
                <h5 class="mb-3">Contact Information</h5>
                <div class="contact-item mb-2">
                    <i class="fas fa-phone text-primary me-2"></i>
                    <span><?php echo $phone; ?></span>
                </div>
                <div class="contact-item mb-2">
                    <i class="fas fa-envelope text-primary me-2"></i>
                    <span><?php echo $email; ?></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                    <span>Listed on <?php echo $formatted_date; ?></span>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <?php if ($is_owner): ?>
                    <!-- Owner Actions -->
                    <div class="d-grid gap-2">
                        <a href="?page=edit&id=<?php echo $property['id']; ?>" 
                           class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Property
                        </a>
                        <a href="?page=delete&id=<?php echo $property['id']; ?>" 
                           class="btn btn-outline-danger"
                           onclick="return confirm('Are you sure you want to delete this property?');">
                            <i class="fas fa-trash me-2"></i>Delete Property
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Visitor Actions -->
                    <div class="d-grid gap-2">
                        <?php if ($in_cart): ?>
                            <a href="?page=cart" class="btn btn-success">
                                <i class="fas fa-shopping-cart me-2"></i>View in Cart
                            </a>
                        <?php else: ?>
                            <form method="post">
                                <button type="submit" name="add_to_cart" class="btn btn-success w-100">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </form>
                        <?php endif; ?>
                        <a href="tel:<?php echo $phone; ?>" 
                           class="btn btn-primary call-button" >
                            <i class="fas fa-phone me-2"></i>Call Now
                        </a>
                        <a href="mailto:<?php echo $email; ?>?subject=Inquiry about <?php echo urlencode($location); ?>" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </a>
                        <button class="btn btn-outline-secondary" onclick="navigator.share({title: 'Property in <?php echo $location; ?>', url: window.location.href}) || copyToClipboard()">
                            <i class="fas fa-share me-2"></i>Share Property
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Property Description -->
<?php if (!empty($description)): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="property-description-card bg-white rounded shadow p-4">
            <h3 class="mb-3">
                <i class="fas fa-align-left text-primary me-2"></i>Property Description
            </h3>
            <div class="description-content">
                <p class="mb-0"><?php echo nl2br($description); ?></p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Additional Property Information -->
<div class="row mt-4">
    <div class="col-12">
        <div class="property-info-card bg-white rounded shadow p-4">
            <h3 class="mb-4">
                <i class="fas fa-info-circle text-primary me-2"></i>Property Information
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="info-group mb-3">
                        <h6 class="text-primary">Location</h6>
                        <p class="mb-0"><?php echo $location; ?></p>
                    </div>
                    <div class="info-group mb-3">
                        <h6 class="text-primary">Property Type</h6>
                        <p class="mb-0">Residential Property</p>
                    </div>
                    <div class="info-group mb-3">
                        <h6 class="text-primary">Floor Area</h6>
                        <p class="mb-0"><?php echo number_format($space); ?> square meters</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-group mb-3">
                        <h6 class="text-primary">Bedrooms</h6>
                        <p class="mb-0"><?php echo $rooms; ?> room<?php echo $rooms > 1 ? 's' : ''; ?></p>
                    </div>
                    <div class="info-group mb-3">
                        <h6 class="text-primary">Bathrooms</h6>
                        <p class="mb-0"><?php echo $bathrooms; ?> bathroom<?php echo $bathrooms > 1 ? 's' : ''; ?></p>
                    </div>
                    <div class="info-group mb-3">
                        <h6 class="text-primary">Price</h6>
                        <p class="mb-0 h5 text-success">$<?php echo $formatted_price; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back to Listings -->
<div class="row mt-4">
    <div class="col-12">
        <a href="?page=sell" class="btn btn-outline-primary me-2">
            <i class="fas fa-arrow-left me-2"></i>Back to All Properties
        </a>
        <?php if (!$is_owner && isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <a href="?page=cart" class="btn btn-primary float-end">
                <i class="fas fa-shopping-cart me-2"></i>View Cart (<?php echo count($_SESSION['cart']); ?>)
            </a>
        <?php endif; ?>
    </div>
</div>

<style>
.property-details-card {
    position: sticky;
    top: 20px;
}

.feature-item {
    transition: transform 0.2s ease;
}

.feature-item:hover {
    transform: translateY(-2px);
}

.contact-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.contact-item:last-child {
    border-bottom: none;
}

.info-group h6 {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.property-info-card,
.property-description-card {
    border: 1px solid #e9ecef;
}

.description-content {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #495057;
}

.property-description-card h3 {
    border-bottom: 2px solid #f8f9fa;
    padding-bottom: 0.5rem;
}

@media (max-width: 991px) {
    .property-details-card {
        position: static;
    }
}
</style>

<script>
function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Property link copied to clipboard!');
    }).catch(function() {
        alert('Unable to copy link');
    });
}
</script>
<script>
  function handleCallClick() {
    const phoneNumber = <?php echo $phone; ?>; // Replace with your phone number

    // Simple mobile device check
    const isMobile = /Mobi|Android|iPhone/i.test(navigator.userAgent);

    if (isMobile) {
      // Open dialer on mobile
      window.location.href = `tel:${phoneNumber}`;
    } else {
      // On desktop, show a message or open Skype (if available)
      window.location.href = `tel:${phoneNumber}`;
      alert("This will try to call using your default phone app (like Skype).");
    }
  }
</script>
<?php
$content = ob_get_clean();

// Render the complete page
renderPage("Property Details: $location | HomeEasy", $content);