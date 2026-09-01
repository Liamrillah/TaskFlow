<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Perbaikan operator OR (menggunakan || murni)
    if (empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 5) {
        $error = "Username must be at least 5 characters.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $insert = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($insert->execute([$username, $email, $hashed_password])) {
                header("Location: login.php?registered=1");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - TaskFlow</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-body">
    <main class="auth-card">
        <h2>Create Account</h2>
        <p class="subtitle">Join TaskFlow to organize your daily work</p>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div id="error-message" class="alert alert-danger" style="display:none;"></div>

        <form action="register.php" method="POST" onsubmit="return validateRegisterForm(event)">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" class="form-control">
            </div>

            <div class="form-group">
                <label for="username">Username (min 5 chars)</label>
                <input type="text" id="username" name="username" class="form-control">
            </div>

            <div class="form-group">
                <label for="password">Password (min 8 chars)</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
        <p class="auth-footer">Already have an account? <a href="login.php">Login here</a></p>
    </main>
    <script src="../js/validation.js"></script>
</body>
</html>