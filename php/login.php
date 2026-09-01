<?php
require_once './config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password =$_POST['password'] ?? '';

    if (empty($email) || empty($password)) {$error = "All fields are required.";
    } else {
        $stmt =$pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user =$stmt->fetch();

        if ($user && password_verify($password,$user['password'])) {
            $_SESSION['user_id'] =$user['id'];
            $_SESSION['username'] =$user['username'];
            
            setcookie("remember_user", $user['username'], time() + 1, "/");
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - TodoFlow</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-body">
    <main class="auth-card">
        <h2>Welcome Back</h2>
        <p class="subtitle">Log in to manage your tasks</p>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div id="error-message" class="alert alert-danger" style="display:none;"></div>

        <form action="login.php" method="POST" onsubmit="return validateLoginForm(event)">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <p class="auth-footer">Don't have an account? <a href="register.php">Register here</a></p>
    </main>
    <script src="../js/validation.js"></script>
</body>
</html>