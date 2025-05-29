        
        <?php
// Database connection
$host = 'localhost';
$dbname = 'forfree';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

 $user_id = $_SESSION['user_id']; // or however you get the user ID
    
    $query = "SELECT profile_photo FROM users WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);
    

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $profile_photo = $result['profile_photo'];
} else {
    echo "User not found";
}
?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

 <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">HomeEasy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="?page=buy">Properties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=rent">Rent</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=sell">Sell</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=feedback">FeedBack</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=agent">Find an Agent</a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link" href="?page=admin_reports">Admin Reports</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Profile Photo Dropdown -->
                        <div class="dropdown me-3">
                            <a class="dropdown-toggle d-flex align-items-center text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="<?php  echo "" . $profile_photo; ?>" 
                                     alt="Profile" 
                                     class="rounded-circle me-2" 
                                     width="40" 
                                     height="40">
                                <span class="d-none d-lg-inline text-dark">
                                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="?page=profile">
                                    <i class="bi bi-person me-2"></i>View Profile
                                </a></li>
                                <li><a class="dropdown-item" href="?page=settings">
                                    <i class="bi bi-gear me-2"></i>Settings
                                </a></li>
                                <li><a class="dropdown-item" href="?page=buy">
                                    <i class="bi bi-house me-2"></i>My Properties
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="?action=logout">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="?page=login" class="btn btn-outline-primary">Login</a>
                        <a href="?page=register" class="btn btn-primary ms-2">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>