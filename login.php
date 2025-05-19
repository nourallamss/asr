<?php
require 'db.php';
session_start();

// Handle "Remember Me" via cookie
if (isset($_COOKIE['remember_user'])) {
    $_SESSION['username'] = $_COOKIE['remember_user'];
    header("Location: index.php");
    exit;
}

$errors = [];
$username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';
    $remember = isset($_POST["remember"]);

    // Login attempt limiter
    if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;

    if ($_SESSION['login_attempts'] >= 5) {
        $errors[] = "Too many login attempts. Please wait 5 minutes.";
    } elseif (empty($username) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $id;
                $_SESSION["username"] = $username;

                // "Remember Me" cookie (1 week)
                if ($remember) {
                    setcookie("remember_user", $username, time() + (7 * 24 * 60 * 60), "/");
                }

                $_SESSION['login_attempts'] = 0; // reset on success
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Incorrect password.";
                $_SESSION['login_attempts']++;
            }
        } else {
            $errors[] = "Username not found.";
            $_SESSION['login_attempts']++;
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | AI Apartment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card p-4 shadow" style="width: 100%; max-width: 420px;">
        <h2 class="text-center mb-3">Welcome Back</h2>
        <p class="text-center text-muted mb-4">Please log in to your account</p>

        <!-- Error messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username" id="username" class="form-control" required
                           value="<?= htmlspecialchars($username) ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group" id="passwordWrapper">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="remember" id="remember" class="form-check-input" required>
                <label for="remember" class="form-check-label">Remember Me</label>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>

        <div class="text-center">
            <small>Don't have an account? <a href="register.php">Register here</a></small>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility
    document.getElementById("togglePassword").addEventListener("click", function () {
        const passwordInput = document.getElementById("password");
        const icon = this.querySelector("i");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        }
    });
</script>
</body>
</html>
