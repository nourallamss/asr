<?php
$host = 'localhost';
$db = 'forfree';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $message = '';
    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        // Fetch record by ID
        $stmt = $pdo->prepare("SELECT * FROM data WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            die("Record not found.");
        }
    } else {
        die("No ID provided.");
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle single file upload
        $imagesPath = $row['images']; // Keep existing images by default
        
        if (isset($_FILES['images']) && $_FILES['images']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/properties/';
            
            // Create upload directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filename = $_FILES['images']['name'];
            $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                // Delete old images if it exists and a new one is being uploaded
                if (!empty($row['images']) && file_exists($row['images'])) {
                    unlink($row['images']);
                }
                
                $newFilename = uniqid() . '_' . $filename;
                $uploadPath = $uploadDir . $newFilename;
                
                if (move_uploaded_file($_FILES['images']['tmp_name'], $uploadPath)) {
                    $imagesPath = $uploadPath;
                } else {
                    $message = '<div style="color: red; padding: 10px; border: 1px solid red; margin: 10px 0;">Error uploading images.</div>';
                }
            } else {
                $message = '<div style="color: red; padding: 10px; border: 1px solid red; margin: 10px 0;">Invalid file type. Only JPG, JPEG, PNG, GIF, and WebP are allowed.</div>';
            }
        }
        
        // Update the record
        if (empty($message)) { // Only update if no upload errors
            $stmt = $pdo->prepare("UPDATE data SET email=?, rooms=?, bathrooms=?, kitchen=?, floor=?, reception=?, view=?, location=?, space=?, sale=?, phone=?, description=?, images=? WHERE id=?");
            $result = $stmt->execute([
                $_POST['email'],
                $_POST['rooms'],
                $_POST['bathrooms'],
                $_POST['kitchen'],
                $_POST['floor'],
                $_POST['reception'],
                $_POST['view'],
                $_POST['location'],
                $_POST['space'],
                $_POST['sale'],
                $_POST['phone'],
                $_POST['description'],
                $imagesPath,
                $_POST['id']
            ]);
            
            if ($result) {
                $message = '<div style="color: green; padding: 10px; border: 1px solid green; margin: 10px 0;">Property updated successfully!</div>';
                // Refresh the data
                $stmt = $pdo->prepare("SELECT * FROM data WHERE id = ?");
                $stmt->execute([$id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $message = '<div style="color: red; padding: 10px; border: 1px solid red; margin: 10px 0;">Error updating property.</div>';
            }
        }
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        
        textarea {
            height: 100px;
            resize: vertical;
        }
        
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
        }
        
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        
        .current-images {
            margin-top: 10px;
            text-align: center;
        }
        
        .current-images img {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid #ddd;
            cursor: pointer;
        }
        
        .current-images img:hover {
            border-color: #007bff;
            transform: scale(1.05);
            transition: all 0.3s ease;
        }
        
        .images-error {
            width: 200px;
            height: 150px;
            background-color: #f8f9fa;
            border: 2px dashed #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc3545;
            font-size: 14px;
            text-align: center;
            border-radius: 5px;
            margin: 0 auto;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
            padding: 8px 15px;
            border: 1px solid #007bff;
            border-radius: 5px;
        }
        
        .back-link:hover {
            background-color: #007bff;
            color: white;
        }
        
        .row {
            display: flex;
            gap: 20px;
        }
        
        .col {
            flex: 1;
        }
        
        @media (max-width: 600px) {
            .row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="javascript:history.back()" class="back-link">← Back to Listings</a>
        
        <?php echo $message; ?>
        
        <h2>Edit Property</h2>
        
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="rooms">Rooms:</label>
                        <input type="number" id="rooms" name="rooms" value="<?= htmlspecialchars($row['rooms']) ?>" min="1" required>
                    </div>
                </div>
                
                <div class="col">
                    <div class="form-group">
                        <label for="bathrooms">Bathrooms:</label>
                        <input type="number" id="bathrooms" name="bathrooms" value="<?= htmlspecialchars($row['bathrooms']) ?>" min="1" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="kitchen">Kitchen:</label>
                        <input type="number" id="kitchen" name="kitchen" value="<?= htmlspecialchars($row['kitchen']) ?>" min="0" required>
                    </div>
                </div>
                
                <div class="col">
                    <div class="form-group">
                        <label for="floor">Floor:</label>
                        <input type="number" id="floor" name="floor" value="<?= htmlspecialchars($row['floor']) ?>" min="0" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="reception">Reception:</label>
                <input type="number" id="reception" name="reception" value="<?= htmlspecialchars($row['reception']) ?>" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="view">View:</label>
                <input type="text" id="view" name="view" value="<?= htmlspecialchars($row['view']) ?>" placeholder="e.g., City view, Garden view, Sea view">
            </div>
            
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?= htmlspecialchars($row['location']) ?>" required>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="space">Space (sq ft):</label>
                        <input type="number" id="space" name="space" value="<?= htmlspecialchars($row['space']) ?>" min="1" required>
                    </div>
                </div>
                
                <div class="col">
                    <div class="form-group">
                        <label for="sale">Sale Price ($):</label>
                        <input type="number" id="sale" name="sale" value="<?= htmlspecialchars($row['sale']) ?>" min="1" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($row['phone']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" placeholder="Provide a detailed description of the property..."><?= htmlspecialchars($row['description'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="images">Property images:</label>
                <input type="file" id="images" name="images" accept="images/*">
                <small style="color: #666; display: block; margin-top: 5px;">
                    Select an images (JPG, PNG, GIF, WebP). Maximum file size: 5MB.
                </small>
                
                <?php if (!empty($row['images'])): ?>
                    <div class="current-images">
                        <strong>Current images:</strong><br>
                        <?php 
                        $images = trim($row['images']);
                        $imagesUrl = './' . ltrim($images, './');
                        
                        if (file_exists($images)):
                        ?>
                            <img src="<?= htmlspecialchars($imagesUrl) ?>" 
                                 alt="Property images" 
                                 title="<?= htmlspecialchars($images) ?>"
                                 onclick="window.open('<?= htmlspecialchars($imagesUrl) ?>', '_blank')"
                                 onerror="this.parentElement.innerHTML='<div class=&quot;images-error&quot;>Failed to load images<br><?= htmlspecialchars(basename($images)) ?></div>'">
                        <?php else: ?>
                            <div class="images-error">
                                images not found<br>
                                <?= htmlspecialchars(basename($images)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <input type="submit" value="Update Property">
        </form>
    </div>
</body>
</html>