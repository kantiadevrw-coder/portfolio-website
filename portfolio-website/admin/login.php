<?php
require_once '../config/database.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit();
}

$error_message = get_flash('error_message');
$success_message = get_flash('success_message');
if (isset($_GET['timeout'])) {
    $error_message = 'Your session expired. Please log in again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Portfolio</title>
    <link rel="stylesheet" href="assets/css/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h2>Admin Login</h2>
            <p>Access your dashboard</p>
        </div>
        <?php if ($error_message): ?><div class="alert alert-error"><?php echo e($error_message); ?></div><?php endif; ?>
        <?php if ($success_message): ?><div class="alert alert-success"><?php echo e($success_message); ?></div><?php endif; ?>

        <form action="../actions/login_action.php" method="POST" class="login-form">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Username</label>
                <input type="text" name="username" id="username" maxlength="50" autocomplete="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" id="password" autocomplete="current-password" required>
            </div>
            <button type="submit" name="login" class="btn-login"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
        <div class="login-footer">
            <p><i class="fas fa-shield-alt"></i> Secure Admin Access</p>
            <p><a href="register.php">Create admin account</a></p>
        </div>
    </div>
</div>
</body>
</html>
