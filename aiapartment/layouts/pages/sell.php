<?php
// Start session at the very top
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Handle AJAX requests for like and report actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    $host = 'localhost';
    $db = 'forfree';
    $user = 'root';
    $pass = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        $property_id = intval($_POST['property_id']);
        $user_email = $_SESSION['email'] ?? null;
        
        if (!$user_email) {
            echo json_encode(['success' => false, 'message' => 'Please login to perform this action']);
            exit;
        }
        
        if ($_POST['action'] === 'like') {
            // Check if user already liked this property
            $stmt = $pdo->prepare("SELECT id FROM property_likes WHERE property_id = ? AND user_email = ?");
            $stmt->execute([$property_id, $user_email]);
            
            if ($stmt->fetch()) {
                // Unlike - remove the like
                $stmt = $pdo->prepare("DELETE FROM property_likes WHERE property_id = ? AND user_email = ?");
                $stmt->execute([$property_id, $user_email]);
                $action = 'unliked';
            } else {
                // Like - add the like
                $stmt = $pdo->prepare("INSERT INTO property_likes (property_id, user_email, created_at) VALUES (?, ?, NOW())");
                $stmt->execute([$property_id, $user_email]);
                $action = 'liked';
            }
            
            // Get updated like count
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM property_likes WHERE property_id = ?");
            $stmt->execute([$property_id]);
            $like_count = $stmt->fetch()['count'];
            
            echo json_encode(['success' => true, 'action' => $action, 'like_count' => $like_count]);
            
        } elseif ($_POST['action'] === 'report') {
            $reason = $_POST['reason'] ?? 'No reason provided';
            
            // Check if user already reported this property
            $stmt = $pdo->prepare("SELECT id FROM property_reports WHERE property_id = ? AND user_email = ?");
            $stmt->execute([$property_id, $user_email]);
            
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'You have already reported this property']);
            } else {
                // Add report
                $stmt = $pdo->prepare("INSERT INTO property_reports (property_id, user_email, reason, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$property_id, $user_email, $reason]);
                
                echo json_encode(['success' => true, 'message' => 'Property reported successfully']);
            }
        }
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error occurred']);
        error_log("Database error: " . $e->getMessage());
    }
    exit;
}
?>

<!DOCTYPE html>
<!--[if IE]><html class="ie" lang="en"><![endif]-->
<!--[if !IE]><!--><html lang="en"><!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings | HomeEasy</title>
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .property-card {
            margin-bottom: 30px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
            display: flex;
            flex-direction: column;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .property-card-img-container {
            height: 200px;
            width: 100%;
            overflow: hidden;
            position: relative;
        }
        
        .property-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .property-card:hover .property-card-img {
            transform: scale(1.03);
        }
        
        .card-body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        
        .card-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        .card-text {
            flex-grow: 1;
            font-size: 0.9rem;
        }
        
        .property-feature {
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 5px;
        }
        
        .property-feature i {
            color: var(--primary-color);
            margin-right: 5px;
        }
        
        .price-tag {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--success-color);
            margin: 10px 0;
        }
        
        .action-buttons {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }
        
        .like-report-buttons {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .like-btn {
            transition: all 0.3s ease;
        }
        
        .like-btn.liked {
            color: #dc3545 !important;
            transform: scale(1.1);
        }
        
        .like-btn:hover {
            transform: scale(1.05);
        }
        
        .report-btn:hover {
            color: var(--warning-color) !important;
        }
        
        .like-count {
            font-size: 0.85rem;
            color: var(--secondary-color);
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }
        
        .form-select {
            max-width: 200px;
            display: inline-block;
        }
        
        /* Loading animation */
        .btn-loading {
            pointer-events: none;
            opacity: 0.6;
        }
        
        .btn-loading .spinner-border {
            width: 1rem;
            height: 1rem;
        }
        
        /* IE10+ specific fixes */
        @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
            .property-card-img {
                width: 100% !important;
                height: auto !important;
            }
            .property-card-img-container {
                display: block;
            }
        }
        
        /* Fallback for very old browsers */
        .no-flexbox .property-card {
            display: block;
            height: auto;
        }
    </style>
</head>
<body>
    <!--[if lt IE 10]>
    <div class="alert alert-warning text-center">
        You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.
    </div>
    <![endif]-->
    <?php include "navbar.php";?>

    <div class="container mt-4 mt-md-5">
        <div class="d-flex justify-content-between align-items-center mb-4 mb-md-5 flex-column flex-md-row">
            <h1 class="h2 mb-3 mb-md-0">Property Listings</h1>
            <div class="d-flex align-items-center">
                <select class="form-select me-2" aria-label="Sort properties">
                    <option selected>Sort by</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="date_desc">Newest First</option>
                    <option value="date_asc">Oldest First</option>
                    <option value="likes_desc">Most Liked</option>
                </select>
                <a href="seller.php" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Property
                </a>
            </div>
        </div>

        <div class="row g-4">
            <?php
            $host = 'localhost';
            $db = 'forfree';
            $user = 'root';
            $pass = '';

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                // Updated query to include like counts
                $stmt = $pdo->query("
                    SELECT d.*, 
                           COALESCE(like_counts.like_count, 0) as like_count
                    FROM data d
                    LEFT JOIN (
                        SELECT property_id, COUNT(*) as like_count 
                        FROM property_likes 
                        GROUP BY property_id
                    ) like_counts ON d.id = like_counts.property_id
                    ORDER BY d.created_at DESC
                ");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Get user's liked properties if logged in
                $user_likes = [];
                if (isset($_SESSION['email'])) {
                    $stmt = $pdo->prepare("SELECT property_id FROM property_likes WHERE user_email = ?");
                    $stmt->execute([$_SESSION['email']]);
                    $user_likes = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'property_id');
                }

                if (count($rows) > 0) {
                    foreach ($rows as $row) {
                        $images = htmlspecialchars($row['images'] ?? '');
                        $image_tag = '';
                        
                        if (!empty($images) && file_exists($images)) {
                            $image_tag = "<div class='property-card-img-container'>
                                            <img src='$images' class='property-card-img' alt='Property in {$row['location']}'>
                                         </div>";
                        } else {
                            $image_tag = "<div class='property-card-img-container bg-light d-flex align-items-center justify-content-center'>
                                            <i class='fas fa-home fa-3x text-secondary'></i>
                                          </div>";
                        }

                        // Format data for display
                        $formatted_price = number_format(floatval($row['sale']), 2);
                        $date = new DateTime($row['created_at']);
                        $formatted_date = $date->format('M j, Y');
                        $phone = !empty($row['phone']) ? htmlspecialchars($row['phone']) : 'N/A';
                        $email = !empty($row['email']) ? htmlspecialchars($row['email']) : 'N/A';
                        
                        // Check if user liked this property
                        $is_liked = in_array($row['id'], $user_likes);
                        $like_class = $is_liked ? 'liked' : '';
                        $like_icon = $is_liked ? 'fas fa-heart' : 'far fa-heart';

                        echo "<div class='col-12 col-md-6 col-lg-4'>
                                <div class='card property-card h-100'>
                                    $image_tag
                                    <div class='card-body'>
                                        <h5 class='card-title'>{$row['location']}</h5>
                                        
                                        <div class='mb-2'>
                                            <span class='property-feature'><i class='fas fa-bed'></i> {$row['rooms']} beds</span>
                                            <span class='property-feature'><i class='fas fa-bath'></i> {$row['bathrooms']} baths</span>
                                            <span class='property-feature'><i class='fas fa-ruler-combined'></i> {$row['space']} m²</span>
                                        </div>
                                        
                                        <div class='price-tag'>\$$formatted_price</div>
                                        
                                        <p class='card-text'>
                                            <small class='text-muted'><i class='fas fa-phone me-1'></i> $phone</small><br>
                                            <small class='text-muted'><i class='fas fa-envelope me-1'></i> $email</small><br>
                                            <small class='text-muted'><i class='fas fa-calendar-alt me-1'></i> $formatted_date</small>
                                        </p>
                                        
                                        <div class='action-buttons'>";
                                                
                              if (isset($_SESSION['email']) && $_SESSION['email'] !== $row['email']) { 

                        // Like and Report buttons (show for all users, but require login)
                        echo "<div class='like-report-buttons d-flex justify-content-between align-items-center'>
                                <div class='d-flex align-items-center'>
                                    <button onclick='refreshMultipleTimes(2)' href='#' class='btn btn-sm btn-outline-danger like-btn $like_class me-2' data-property-id='{$row['id']}'>
                                        <i class='$like_icon'></i>
                                        <span class='like-count'>{$row['like_count']}</span>
                                    </button>
                                </div>
                                <button href='#' class='btn btn-sm btn-outline-warning report-btn' data-property-id='{$row['id']}'>
                                    <i class='fas fa-flag'></i> Report
                                </button>
                              </div>";
                              }    
                        // Always show View button
                        echo "<div class='d-flex gap-2 mb-2'>
                                <a href='?page=view&id={$row['id']}' class='btn btn-primary btn-sm flex-fill'>
                                    <i class='fas fa-eye me-1'></i> View Details
                                </a>
                              </div>";
                        
                        // Check if user is logged in and their email matches the property email
                        if (isset($_SESSION['email']) && $_SESSION['email'] === $row['email']) {
                            echo "<div class='d-flex gap-2'>
                                    <a href='?page=edit&id={$row['id']}' class='btn btn-outline-primary btn-sm flex-fill'>
                                        <i class='fas fa-edit me-1'></i> Edit
                                    </a>
                                    <a href='?page=delete&id={$row['id']}' class='btn btn-outline-danger btn-sm flex-fill' onclick=\"return confirm('Delete this property?');\">
                                        <i class='fas fa-trash me-1'></i> Delete
                                    </a>
                                  </div>";
                        }
                        
                        echo "</div></div></div></div>";
                    }
                } else {
                    echo "<div class='col-12 empty-state'>
                            <i class='fas fa-home'></i>
                            <h3 class='h4'>No Properties Found</h3>
                            <p class='text-muted'>There are currently no properties listed.</p>
                             <a href='seller.php' class='btn btn-primary'>
                   Add Property
                </a>
                        </div>";
                }

            } catch (PDOException $e) {
                echo "<div class='col-12'>
                        <div class='alert alert-danger'>
                            <i class='fas fa-exclamation-triangle me-2'></i> 
                            <strong>Error:</strong> Unable to load properties. Please try again later.
                        </div>
                    </div>";
                error_log("Database error: " . $e->getMessage());
            }
            ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Property pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report Property</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reportForm">
                        <div class="mb-3">
                            <label for="reportReason" class="form-label">Reason for reporting:</label>
                            <select class="form-select" id="reportReason" required>
                                <option value="">Select a reason</option>
                                <option value="inappropriate_content">Inappropriate Content</option>
                                <option value="spam">Spam</option>
                                <option value="fake_listing">Fake Listing</option>
                                <option value="incorrect_information">Incorrect Information</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3" id="otherReasonDiv" style="display: none;">
                            <label for="otherReason" class="form-label">Please specify:</label>
                            <textarea class="form-control" id="otherReason" rows="3"></textarea>
                        </div>
                        <input type="hidden" id="reportPropertyId" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button  type="button" class="btn btn-danger" id="submitReport">Submit Report</button>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Bootstrap JS -->
    
    <!-- Polyfills for older browsers -->
    <!--[if lt IE 10]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/classlist/1.2.20171210/classList.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flexibility/2.0.1/flexibility.js"></script>
    <script>
        flexibility(document.documentElement);
    </script>
    <![endif]-->
    
    <script>
        // Initialize functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Sort functionality
            var select = document.querySelector('.form-select');
            if (select) {
                select.addEventListener('change', function() {
                    var sortValue = this.value;
                    if (sortValue) {
                        console.log('Sorting by:', sortValue);
                        // You can implement actual sorting here
                    }
                });
            }
            
            // Like button functionality
            document.querySelectorAll('.like-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var propertyId = this.getAttribute('data-property-id');
                    var likeBtn = this;
                    var icon = likeBtn.querySelector('i');
                    var countSpan = likeBtn.querySelector('.like-count');
                    
                    // Add loading state
                    likeBtn.classList.add('btn-loading');
                    
                    // Send AJAX request
                    fetch(window.location.href, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=like&property_id=' + propertyId
                    })
                    .then(response => response.json())
                    .then(data => {
                        likeBtn.classList.remove('btn-loading');
                        
                        if (data.success) {
                            if (data.action === 'liked') {
                                likeBtn.classList.add('liked');
                                icon.className = 'fas fa-heart';
                            } else {
                                likeBtn.classList.remove('liked');
                                icon.className = 'far fa-heart';
                            }
                            countSpan.textContent = data.like_count;
                        } else {
                            showToast(data.message || 'Please login to like properties', 'warning');
                        }
                    })
                    .catch(error => {
                        likeBtn.classList.remove('btn-loading');
                        showToast('An error occurred. Please try again.', 'danger');
                        console.error('Error:', error);
                    });
                });
            });
            
            // Report button functionality
            document.querySelectorAll('.report-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var propertyId = this.getAttribute('data-property-id');
                    document.getElementById('reportPropertyId').value = propertyId;
                    
                    var reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
                    reportModal.show();
                });
            });
            
            // Report reason change handler
            document.getElementById('reportReason').addEventListener('change', function() {
                var otherDiv = document.getElementById('otherReasonDiv');
                if (this.value === 'other') {
                    otherDiv.style.display = 'block';
                } else {
                    otherDiv.style.display = 'none';
                }
            });
            
            // Submit report
            document.getElementById('submitReport').addEventListener('click', function() {
                var propertyId = document.getElementById('reportPropertyId').value;
                var reason = document.getElementById('reportReason').value;
                var otherReason = document.getElementById('otherReason').value;
                
                if (!reason) {
                    showToast('Please select a reason for reporting', 'warning');
                    return;
                }
                
                var finalReason = reason === 'other' ? otherReason : reason;
                
                // Send AJAX request
                fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=report&property_id=' + propertyId + '&reason=' + encodeURIComponent(finalReason)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        bootstrap.Modal.getInstance(document.getElementById('reportModal')).hide();
                        document.getElementById('reportForm').reset();
                    } else {
                        showToast(data.message, 'warning');
                    }
                })
                .catch(error => {
                    showToast('An error occurred. Please try again.', 'danger');
                    console.error('Error:', error);
                });
            });
            
            // Confirm before delete
            var deleteLinks = document.querySelectorAll('[onclick*="confirm"]');
            if (deleteLinks) {
                deleteLinks.forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        if (!confirm(this.getAttribute('data-confirm') || 'Are you sure?')) {
                            e.preventDefault();
                        }
                    });
                });
            }
        });
        
        // Toast notification function
        function showToast(message, type = 'info') {
            var toast = document.getElementById('actionToast');
            var toastBody = toast.querySelector('.toast-body');
            var toastIcon = toast.querySelector('.toast-header i');
            
            // Set message
            toastBody.textContent = message;
            
            // Set icon based on type
            toastIcon.className = 'fas me-2';
            switch(type) {
                case 'success':
                    toastIcon.classList.add('fa-check-circle', 'text-success');
                    break;
                case 'warning':
                    toastIcon.classList.add('fa-exclamation-triangle', 'text-warning');
                    break;
                case 'danger':
                    toastIcon.classList.add('fa-exclamation-circle', 'text-danger');
                    break;
                default:
                    toastIcon.classList.add('fa-info-circle', 'text-primary');
            }
            
            // Show toast
            var bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
    </script>
    
   <script>
    // Save current count in sessionStorage
    function refreshMultipleTimes(times) {
      sessionStorage.setItem('refreshCount', times);
      sessionStorage.setItem('scrollPos', window.scrollY);
      location.reload();
    }

    window.addEventListener('load', () => {
      const count = parseInt(sessionStorage.getItem('refreshCount'), 10);
      const scrollPos = sessionStorage.getItem('scrollPos');

      if (scrollPos !== null) {
        window.scrollTo(0, parseInt(scrollPos, 10));
      }

      if (!isNaN(count) && count > 1) {
        sessionStorage.setItem('refreshCount', count - 1);
        setTimeout(() => {
          location.reload();
        }, 1000); // 1 second delay between refreshes
      } else {
        sessionStorage.removeItem('refreshCount');
        sessionStorage.removeItem('scrollPos');
      }
    });
  </script>
</body>
</html>