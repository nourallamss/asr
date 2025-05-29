<?php
session_start();

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Function to generate new CSRF token
function generateCSRFToken() {
    return bin2hex(random_bytes(32));
}

// Function to verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Initialize variables
$displayResult = false;
$data = [];
$message = '';
$uploadedImage = '';

// Check if user email exists in session
$email = isset($_SESSION["email"]) ? $_SESSION["email"] : null;
if (!$email) {
    // Regenerate session ID for security
    session_regenerate_id(true);
    die("Access denied. Please log in first.");
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format in session.");
}

// Database configuration with improved security
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'forfree';
$tableName = 'data';

// Create database connection with error reporting disabled in production
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    $conn->set_charset("utf8mb4"); // Use UTF-8 encoding
} catch (mysqli_sql_exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// Enhanced image upload function with better security
function handleImageUpload($file) {
    $uploadDir = 'uploads/properties/';
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    $uploadedFile = '';
    $error = '';
    
    // Create upload directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return ['file' => '', 'error' => 'Failed to create upload directory.'];
        }
    }
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];
        
        // Get file extension
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Validate file extension
        if (!in_array($fileExtension, $allowedExtensions)) {
            return ['file' => '', 'error' => "Invalid file extension. Only JPG, JPEG, PNG, and GIF files are allowed."];
        }
        
        // Additional MIME type validation
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($finfo, $fileTmpName);
        finfo_close($finfo);
        
        if (!in_array($detectedType, $allowedTypes)) {
            return ['file' => '', 'error' => "Invalid file type detected. Only image files are allowed."];
        }
        
        // Validate file size
        if ($fileSize > $maxFileSize) {
            return ['file' => '', 'error' => "File is too large. Maximum size is 5MB."];
        }
        
        // Validate image dimensions (optional - prevents extremely large images)
        $imageInfo = getimagesize($fileTmpName);
        if ($imageInfo === false) {
            return ['file' => '', 'error' => "Invalid image file."];
        }
        
        // Check image dimensions (max 4000x4000 pixels)
        if ($imageInfo[0] > 4000 || $imageInfo[1] > 4000) {
            return ['file' => '', 'error' => "Image dimensions too large. Maximum size is 4000x4000 pixels."];
        }
        
        // Generate secure filename
        $uniqueFileName = uniqid('prop_', true) . '_' . time() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $uniqueFileName;
        
        // Move uploaded file
        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            // Set proper file permissions
            chmod($uploadPath, 0644);
            $uploadedFile = $uploadPath;
        } else {
            return ['file' => '', 'error' => "Failed to upload file."];
        }
    } elseif ($file['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        
        $error = isset($uploadErrors[$file['error']]) ? $uploadErrors[$file['error']] : 'Unknown upload error';
        return ['file' => '', 'error' => $error];
    }
    
    return ['file' => $uploadedFile, 'error' => $error];
}

// Enhanced input validation and sanitization
function validateAndSanitizeInput($input, $type = 'string', $min = null, $max = null) {
    $input = trim($input);
    
    switch ($type) {
        case 'int':
            if (!filter_var($input, FILTER_VALIDATE_INT)) {
                return false;
            }
            $value = intval($input);
            if ($min !== null && $value < $min) return false;
            if ($max !== null && $value > $max) return false;
            return $value;
            
        case 'float':
            if (!filter_var($input, FILTER_VALIDATE_FLOAT)) {
                return false;
            }
            $value = floatval($input);
            if ($min !== null && $value < $min) return false;
            if ($max !== null && $value > $max) return false;
            return $value;
            
        case 'string':
            $value = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            if ($min !== null && strlen($value) < $min) return false;
            if ($max !== null && strlen($value) > $max) return false;
            return $value;
            
        case 'phone':
            // More comprehensive phone validation
            $cleanPhone = preg_replace('/[^\d\+\-\(\)\s]/', '', $input);
            if (!preg_match('/^[\d\+\-\(\)\s]{7,20}$/', $cleanPhone)) {
                return false;
            }
            return $cleanPhone;
            
        case 'select':
            // For dropdown values, check against allowed values
            $allowedValues = $max; // $max parameter contains allowed values array
            return in_array($input, $allowedValues) ? $input : false;
            
        default:
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF token first
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = "Security token mismatch. Please refresh the page and try again.";
    } else {
        // Define validation rules
        $validationRules = [
            'rooms' => ['type' => 'select', 'allowed' => [1, 2, 3, 4]],
            'bathrooms' => ['type' => 'int', 'min' => 0, 'max' => 10],
            'kitchen' => ['type' => 'int', 'min' => 0, 'max' => 5],
            'floor' => ['type' => 'select', 'allowed' => range(1, 10)],
            'reception' => ['type' => 'int', 'min' => 0, 'max' => 5],
            'view' => ['type' => 'select', 'allowed' => ['Internal', 'External']],
            'location' => ['type' => 'string', 'min' => 1, 'max' => 255],
            'space' => ['type' => 'float', 'min' => 1, 'max' => 10000],
            'sale' => ['type' => 'int', 'min' => 0, 'max' => 999999999],
            'phone' => ['type' => 'phone'],
            'description' => ['type' => 'string', 'min' => 0, 'max' => 1000]
        ];
        
        // Initialize data with defaults
        $data = [
            'rooms' => 1,
            'bathrooms' => 0,
            'kitchen' => 0,
            'floor' => 1,
            'reception' => 0,
            'view' => 'Internal',
            'location' => '',
            'space' => 0,
            'sale' => 0,
            'phone' => '',
            'description' => ''
        ];
        
        $validationErrors = [];
        
        // Validate and sanitize each field
        foreach ($validationRules as $field => $rules) {
            if (isset($_POST[$field]) && $_POST[$field] !== '') {
                if ($rules['type'] === 'select') {
                    $validatedValue = validateAndSanitizeInput($_POST[$field], 'select', null, $rules['allowed']);
                } else {
                    $min = isset($rules['min']) ? $rules['min'] : null;
                    $max = isset($rules['max']) ? $rules['max'] : null;
                    $validatedValue = validateAndSanitizeInput($_POST[$field], $rules['type'], $min, $max);
                }
                
                if ($validatedValue === false) {
                    $validationErrors[] = "Invalid value for " . ucfirst($field);
                } else {
                    $data[$field] = $validatedValue;
                }
            } elseif (in_array($field, ['location', 'phone'])) {
                // Required fields
                $validationErrors[] = ucfirst($field) . " is required";
            }
        }
        
        // Check for validation errors
        if (!empty($validationErrors)) {
            $message = "Validation errors: " . implode(", ", $validationErrors);
        } else {
            // Handle image upload
            $imageUploadResult = ['file' => '', 'error' => ''];
            if (isset($_FILES['property_image']) && !empty($_FILES['property_image']['name'])) {
                $imageUploadResult = handleImageUpload($_FILES['property_image']);
            }
            
            // Check for upload errors
            if (!empty($imageUploadResult['error'])) {
                $message = "Image upload error: " . $imageUploadResult['error'];
            } else {
                $uploadedImage = $imageUploadResult['file'];
                
                // Database operations with transactions
                try {
                    $conn->begin_transaction();
                    
                    // Check and add columns if they don't exist
                    $checkImagesColumn = $conn->query("SHOW COLUMNS FROM `$tableName` LIKE 'images'");
                    if ($checkImagesColumn->num_rows == 0) {
                        $conn->query("ALTER TABLE `$tableName` ADD COLUMN `images` TEXT");
                    }
                    
                    $checkDescriptionColumn = $conn->query("SHOW COLUMNS FROM `$tableName` LIKE 'description'");
                    if ($checkDescriptionColumn->num_rows == 0) {
                        $conn->query("ALTER TABLE `$tableName` ADD COLUMN `description` TEXT");
                    }
                    
                    // Prepare and execute insert statement
                    $sql = "INSERT INTO `$tableName` (`rooms`, `bathrooms`, `kitchen`, `floor`, `reception`, `view`, `location`, `space`, `sale`, `phone`, `email`, `images`, `description`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                    
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        throw new Exception("Failed to prepare statement: " . $conn->error);
                    }
                    
                    $stmt->bind_param("iiiiissdissss",
                        $data['rooms'],
                        $data['bathrooms'],
                        $data['kitchen'],
                        $data['floor'],
                        $data['reception'],
                        $data['view'],
                        $data['location'],
                        $data['space'],
                        $data['sale'],
                        $data['phone'],
                        $email,
                        $uploadedImage,
                        $data['description']
                    );
                    
                    if (!$stmt->execute()) {
                        throw new Exception("Failed to execute statement: " . $stmt->error);
                    }
                    
                    $conn->commit();
                    $stmt->close();
                    
                    $message = "Property listing saved successfully!";
                    if (!empty($uploadedImage)) {
                        $message .= " Image uploaded successfully.";
                    }
                    $displayResult = true;
                    
                    // Generate new CSRF token for next form submission
                    $_SESSION['csrf_token'] = generateCSRFToken();
                    
                } catch (Exception $e) {
                    $conn->rollback();
                    error_log("Database error: " . $e->getMessage());
                    $message = "Failed to save property listing. Please try again.";
                    
                    // Clean up uploaded file if database operation fails
                    if (!empty($uploadedImage) && file_exists($uploadedImage)) {
                        unlink($uploadedImage);
                    }
                }
            }
        }
    }
}

// Close connection
if (isset($conn)) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Secure Seller Form</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Roboto', sans-serif;
            color: #333;
        }

        .container {
            max-width: 900px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .form-select, .form-control {
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            border: 2px solid #e3e6f0;
            transition: all 0.3s ease;
        }

        .form-select:focus, .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.15);
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 15px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2e59d9 0%, #1a45c7 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.3);
        }

        .result-card {
            background: linear-gradient(135deg, #f1f5fb 0%, #e8f0ff 100%);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e3e6f0;
        }

        .result-card ul {
            list-style-type: none;
            padding: 0;
        }

        .result-card ul li {
            padding: 15px 0;
            border-bottom: 1px solid rgba(78, 115, 223, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .result-card ul li:last-child {
            border-bottom: none;
        }

        .result-card ul li strong {
            color: #4e73df;
            font-weight: 600;
        }

        .result-card h4 {
            color: #4e73df;
            margin-bottom: 25px;
            font-weight: 700;
            text-align: center;
        }

        .section-header {
            font-size: 2.2rem;
            font-weight: 700;
            text-align: center;
            color: #4e73df;
            margin-bottom: 40px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid #e3e6f0;
        }
        
        .alert {
            border-radius: 10px;
            margin-bottom: 25px;
            border: none;
            padding: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }
        
        /* Enhanced form styling */
        .phone-input-wrapper {
            position: relative;
        }
        
        .form-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
        }
        
        .is-invalid ~ .invalid-feedback {
            display: block;
        }
        
        .description-wrapper textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .char-counter {
            font-size: 0.8rem;
            color: #6c757d;
            text-align: right;
            margin-top: 5px;
            font-weight: 500;
        }
        
        .char-counter.warning {
            color: #ffc107;
        }
        
        .char-counter.danger {
            color: #dc3545;
        }
        
        /* Enhanced image upload styling */
        .image-upload-area {
            border: 3px dashed #4e73df;
            border-radius: 12px;
            padding: 50px 20px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f3ff 100%);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .image-upload-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(78, 115, 223, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .image-upload-area:hover {
            border-color: #2e59d9;
            background: linear-gradient(135deg, #f0f3ff 0%, #e8f0ff 100%);
            transform: translateY(-2px);
        }
        
        .image-upload-area:hover::before {
            left: 100%;
        }
        
        .image-upload-area.dragover {
            border-color: #2e59d9;
            background: linear-gradient(135deg, #e8f0ff 0%, #dae8ff 100%);
            transform: scale(1.02);
        }
        
        .upload-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.1));
        }
        
        .upload-text {
            color: #495057;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .upload-subtext {
            color: #6c757d;
            font-size: 0.95rem;
        }
        
        .image-preview-container {
            margin-top: 25px;
            text-align: center;
        }
        
        .image-preview {
            position: relative;
            display: inline-block;
            width: 220px;
            height: 220px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }
        
        .image-preview:hover {
            transform: scale(1.05);
        }
        
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        }
        
        .image-remove:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }
        
        .property-image {
            width: 250px;
            height: 250px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            margin: 0 auto;
        }
        
        .property-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .property-description {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            border-left: 5px solid #4e73df;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .property-description h6 {
            color: #4e73df;
            margin-bottom: 15px;
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .property-description p {
            margin: 0;
            line-height: 1.7;
            color: #495057;
        }
        
        /* Security indicator */
        .security-badge {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
            z-index: 1000;
        }
        
        .security-badge::before {
            content: "🔒 ";
            margin-right: 5px;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .section-content {
                padding: 20px;
            }
            
            .section-header {
                font-size: 1.8rem;
            }
            
            .upload-icon {
                font-size: 2.5rem;
            }
            
            .image-preview, .property-image {
                width: 180px;
                height: 180px;
            }
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <!-- Form Section -->
    <div class="section-content">
        <h2 class="section-header">🏠 Secure Property Listing Form</h2>
        
        <?php if (!empty($message)): ?>
            <div class="alert <?= strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-danger' ?>">
                <strong><?= strpos($message, 'successfully') !== false ? '✅ Success!' : '⚠️ Error!' ?></strong>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                🏡 Property Details Form
            </div>
            <div class="card-body p-4">
                <form method="POST" action="" id="apartmentForm" enctype="multipart/form-data" novalidate>
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    
                    <!-- Form fields with enhanced validation -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="rooms" class="form-label">🛏️ Rooms:</label>
                            <select name="rooms" id="rooms" class="form-select" required>
                                <?php for ($i = 1; $i <= 4; $i++): ?>
                                    <option value="<?= $i ?>" <?= isset($data['rooms']) && $data['rooms'] == $i ? 'selected' : '' ?>><?= $i ?> Room<?= $i > 1 ? 's' : '' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bathrooms" class="form-label">🚿 Bathrooms:</label>
                            <input type="number" name="bathrooms" id="bathrooms" class="form-control" 
                                   value="<?= $data['bathrooms'] ?? '' ?>" min="0" max="10" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kitchen" class="form-label">🍳 Kitchen:</label>
                            <input type="number" name="kitchen" id="kitchen" class="form-control" 
                                   value="<?= $data['kitchen'] ?? '' ?>" min="0" max="5" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="floor" class="form-label">🏢 Floor:</label>
                            <select name="floor" id="floor" class="form-select" required>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i ?>" <?= isset($data['floor']) && $data['floor'] == $i ? 'selected' : '' ?>><?= $i ?><?= $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) ?> Floor</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="reception" class="form-label">🛋️ Reception:</label>
                            <input type="number" name="reception" id="reception" class="form-control" 
                                   value="<?= $data['reception'] ?? '' ?>" min="0" max="5" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="view" class="form-label">🌅 View:</label>
                            <select name="view" id="view" class="form-select" required>
                                <option value="Internal" <?= isset($data['view']) && $data['view'] == 'Internal' ? 'selected' : '' ?>>🏢 Internal View</option>
                                <option value="External" <?= isset($data['view']) && $data['view'] == 'External' ? 'selected' : '' ?>>🌆 External View</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">📍 Location:</label>
                        <input type="text" name="location" id="location" class="form-control" 
                               value="<?= htmlspecialchars($data['location'] ?? '') ?>" 
                               placeholder="Enter full address or area name" 
                               maxlength="255" required>
                        <div class="form-text">Provide complete address or neighborhood details</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="space" class="form-label">📐 Space (m²):</label>
                            <input type="number" name="space" id="space" class="form-control" 
                                   value="<?= $data['space'] ?? '' ?>" 
                                   min="1" max="10000" step="0.1" required>
                            <div class="form-text">Total area in square meters</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sale" class="form-label">💰 Sale Price:</label>
                            <input type="number" name="sale" id="sale" class="form-control" 
                                   value="<?= $data['sale'] ?? '' ?>" 
                                   min="0" max="999999999" required>
                            <div class="form-text">Enter price in your local currency</div>
                        </div>
                    </div>

                    <div class="mb-3 phone-input-wrapper">
                        <label for="phone" class="form-label">📞 Phone Number:</label>
                        <input type="tel" name="phone" id="phone" 
                               class="form-control <?= !empty($message) && strpos($message, 'phone') !== false ? 'is-invalid' : '' ?>" 
                               value="<?= htmlspecialchars($data['phone'] ?? '') ?>" 
                               placeholder="+1 (123) 456-7890" 
                               pattern="[\d\+\-\(\)\s]{7,20}"
                               maxlength="20"
                               required>
                        <div class="form-text">Format: Country code (optional) followed by phone number. Example: +1 (123) 456-7890</div>
                        <div class="invalid-feedback">
                            Please enter a valid phone number (7-20 digits, may include +, -, (), and spaces)
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3 description-wrapper">
                        <label for="description" class="form-label">📝 Property Description:</label>
                        <textarea name="description" id="description" class="form-control" 
                                  placeholder="Describe your property in detail... (features, amenities, neighborhood, etc.)"
                                  maxlength="1000"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                        <div class="form-text">Provide a detailed description of your property to attract potential buyers (optional, max 1000 characters).</div>
                        <div class="char-counter" id="charCounter">0 / 1000 characters</div>
                    </div>

                    <!-- Enhanced Image Upload Section -->
                    <div class="mb-4 image-upload-wrapper">
                        <label class="form-label">📷 Property Image:</label>
                        <div class="image-upload-area" id="imageUploadArea">
                            <div class="upload-icon">🏠</div>
                            <div class="upload-text">Click to upload or drag and drop an image</div>
                            <div class="upload-subtext">Supports: JPEG, PNG, GIF (Max 5MB, 4000x4000px)</div>
                        </div>
                        <input type="file" name="property_image" id="propertyImage" 
                               class="form-control d-none" 
                               accept="image/jpeg,image/jpg,image/png,image/gif">
                        <div class="form-text">Upload one high-quality image of your property (optional but recommended).</div>
                        
                        <!-- Image Preview Container -->
                        <div class="image-preview-container" id="imagePreviewContainer"></div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            🚀 Submit Property Listing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Enhanced Display Result -->
    <?php if ($displayResult): ?>
        <div class="section-content mt-5">
            <div class="result-card">
                <h4>🎉 Property Listing Successfully Created!</h4>
                <div class="row">
                    <div class="col-md-8">
                        <ul>
                            <li><strong>Rooms:</strong> <span><?= $data['rooms'] ?> Room<?= $data['rooms'] > 1 ? 's' : '' ?></span></li>
                            <li><strong>Bathrooms:</strong> <span><?= $data['bathrooms'] ?></span></li>
                            <li><strong>Kitchen:</strong> <span><?= $data['kitchen'] ?></span></li>
                            <li><strong>Floor:</strong> <span><?= $data['floor'] ?><?= $data['floor'] == 1 ? 'st' : ($data['floor'] == 2 ? 'nd' : ($data['floor'] == 3 ? 'rd' : 'th')) ?> Floor</span></li>
                            <li><strong>Reception:</strong> <span><?= $data['reception'] ?></span></li>
                            <li><strong>View:</strong> <span><?= $data['view'] ?></span></li>
                            <li><strong>Location:</strong> <span><?= htmlspecialchars($data['location']) ?></span></li>
                            <li><strong>Space:</strong> <span><?= $data['space'] ?> m²</span></li>
                            <li><strong>Sale Price:</strong> <span>$<?= !empty($data['sale']) ? number_format((float)$data['sale']) : '0' ?></span></li>
                            <li><strong>Phone:</strong> <span><?= htmlspecialchars($data['phone']) ?></span></li>
                        </ul>
                    </div>
                    
                    <?php if (!empty($uploadedImage)): ?>
                        <div class="col-md-4 text-center">
                            <h6>📸 Property Image:</h6>
                            <div class="property-image">
                                <img src="<?= htmlspecialchars($uploadedImage) ?>" alt="Property Image" loading="lazy">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($data['description'])): ?>
                    <div class="property-description">
                        <h6>📄 Property Description:</h6>
                        <p><?= nl2br(htmlspecialchars($data['description'])) ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="text-center mt-4">
                    <button onclick="window.location.reload()" class="btn btn-primary">
                        ➕ Add Another Property
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Security Badge -->
<div class="security-badge">
    Secure Form Protected
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
// Enhanced client-side validation and security
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('apartmentForm');
    const phoneInput = document.getElementById('phone');
    const imageUploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('propertyImage');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const descriptionTextarea = document.getElementById('description');
    const charCounter = document.getElementById('charCounter');
    let selectedFile = null;
    
    // Character counter for description
    function updateCharCounter() {
        const currentLength = descriptionTextarea.value.length;
        const maxLength = 1000;
        charCounter.textContent = `${currentLength} / ${maxLength} characters`;
        
        // Change color based on usage
        if (currentLength > maxLength * 0.9) {
            charCounter.className = 'char-counter danger';
        } else if (currentLength > maxLength * 0.75) {
            charCounter.className = 'char-counter warning';
        } else {
            charCounter.className = 'char-counter';
        }
    }
    
    // Initialize character counter
    updateCharCounter();
    
    // Update character counter on input
    descriptionTextarea.addEventListener('input', updateCharCounter);
    
    // Enhanced form validation
    form.addEventListener('submit', function(event) {
        let isValid = true;
        const errors = [];
        
        // Phone validation
        const phoneValue = phoneInput.value.trim();
        const phonePattern = /^[\d\+\-\(\)\s]{7,20}$/;
        
        if (!phonePattern.test(phoneValue)) {
            phoneInput.classList.add('is-invalid');
            errors.push('Invalid phone number format');
            isValid = false;
        } else {
            phoneInput.classList.remove('is-invalid');
        }
        
        // Location validation
        const locationInput = document.getElementById('location');
        if (locationInput.value.trim().length < 1) {
            locationInput.classList.add('is-invalid');
            errors.push('Location is required');
            isValid = false;
        } else {
            locationInput.classList.remove('is-invalid');
        }
        
        // Space validation
        const spaceInput = document.getElementById('space');
        const spaceValue = parseFloat(spaceInput.value);
        if (isNaN(spaceValue) || spaceValue < 1 || spaceValue > 10000) {
            spaceInput.classList.add('is-invalid');
            errors.push('Space must be between 1 and 10,000 m²');
            isValid = false;
        } else {
            spaceInput.classList.remove('is-invalid');
        }
        
        // Sale price validation
        const saleInput = document.getElementById('sale');
        const saleValue = parseInt(saleInput.value);
        if (isNaN(saleValue) || saleValue < 0) {
            saleInput.classList.add('is-invalid');
            errors.push('Sale price must be a positive number');
            isValid = false;
        } else {
            saleInput.classList.remove('is-invalid');
        }
        
        if (!isValid) {
            event.preventDefault();
            alert('Please fix the following errors:\n• ' + errors.join('\n• '));
        }
    });
    
    // Real-time phone validation
    phoneInput.addEventListener('input', function() {
        const phoneValue = this.value.trim();
        const phonePattern = /^[\d\+\-\(\)\s]{7,20}$/;
        
        if (phoneValue.length > 0) {
            if (phonePattern.test(phoneValue)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
    });
    
    // Enhanced image upload handling
    imageUploadArea.addEventListener('click', function() {
        imageInput.click();
    });
    
    imageUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    imageUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    imageUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });
    
    imageInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFile(this.files[0]);
        }
    });
    
    function handleFile(file) {
        // Enhanced file validation
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        const maxFileSize = 5 * 1024 * 1024; // 5MB
        
        // Validate file type
        if (!allowedTypes.includes(file.type)) {
            showAlert('Please select only image files (JPEG, PNG, GIF).', 'danger');
            return;
        }
        
        // Validate file size
        if (file.size > maxFileSize) {
            showAlert(`File "${file.name}" is too large. Maximum size is 5MB.`, 'danger');
            return;
        }
        
        selectedFile = file;
        createImagePreview(file);
    }
    
    function createImagePreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreviewContainer.innerHTML = `
                <div class="image-preview">
                    <img src="${e.target.result}" alt="Preview" loading="lazy">
                    <button type="button" class="image-remove" onclick="removeImage()" title="Remove Image">×</button>
                </div>
                <div class="mt-2 text-muted">
                    <small>📁 ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }
    
    // Global function for removing image
    window.removeImage = function() {
        selectedFile = null;
        imagePreviewContainer.innerHTML = '';
        imageInput.value = '';
        showAlert('Image removed successfully.', 'info');
    };
    
    // Alert function
    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <strong>${type === 'danger' ? '⚠️ Error!' : type === 'success' ? '✅ Success!' : 'ℹ️ Info:'}</strong>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.section-content');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    // Form security enhancements
    
    // Prevent multiple form submissions
    let isSubmitting = false;
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        
        isSubmitting = true;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        submitButton.innerHTML = '⏳ Processing...';
        submitButton.disabled = true;
        
        // Re-enable after 10 seconds as a failsafe
        setTimeout(() => {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            isSubmitting = false;
        }, 10000);
    });
    
    // Auto-save form data to prevent data loss (using sessionStorage for security)
    const formInputs = form.querySelectorAll('input, select, textarea');
    
    // Load saved data on page load
    formInputs.forEach(input => {
        if (input.type !== 'file' && input.name !== 'csrf_token') {
            const savedValue = sessionStorage.getItem('form_' + input.name);
            if (savedValue && !input.value) {
                input.value = savedValue;
            }
        }
    });
    
    // Save data on input change
    formInputs.forEach(input => {
        if (input.type !== 'file' && input.name !== 'csrf_token') {
            input.addEventListener('input', function() {
                sessionStorage.setItem('form_' + this.name, this.value);
            });
        }
    });
    
    // Clear saved data on successful submission
    form.addEventListener('submit', function() {
        if (!this.querySelector('.is-invalid')) {
            formInputs.forEach(input => {
                if (input.name !== 'csrf_token') {
                    sessionStorage.removeItem('form_' + input.name);
                }
            });
        }
    });
    
    // Update character counter on page load
    updateCharCounter();
    
    // Add smooth animations
    const cards = document.querySelectorAll('.card, .result-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
});

// Additional security measures
document.addEventListener('contextmenu', function(e) {
    // Optionally disable right-click on sensitive areas
    if (e.target.closest('.security-badge')) {
        e.preventDefault();
    }
});

// Detect potential XSS attempts in form inputs
document.addEventListener('input', function(e) {
    const suspiciousPatterns = [
        /<script/i,
        /javascript:/i,
        /on\w+=/i,
        /<iframe/i
    ];
    
    const value = e.target.value;
    for (let pattern of suspiciousPatterns) {
        if (pattern.test(value)) {
            e.target.value = value.replace(pattern, '');
            console.warn('Potentially malicious input detected and sanitized');
        }
    }
});
</script>

</body>
</html>