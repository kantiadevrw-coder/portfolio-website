<?php
require_once '../config/database.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit();
}

$error_message = get_flash('error_message');
$success_message = get_flash('success_message');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Portfolio</title>
    <link rel="stylesheet" href="assets/css/admin-style.css">
</head>
<body class="login-page">
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h2>Create Admin Account</h2>
            <p>Register an administrator for your portfolio</p>
        </div>
        <?php if ($error_message): ?><div class="alert alert-error"><?php echo e($error_message); ?></div><?php endif; ?>
        <?php if ($success_message): ?><div class="alert alert-success"><?php echo e($success_message); ?></div><?php endif; ?>

        <form method="POST" action="../actions/register_action.php" class="login-form">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" maxlength="50" autocomplete="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" maxlength="100" autocomplete="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" minlength="8" autocomplete="new-password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="8" autocomplete="new-password" required>
            </div>
            <button type="submit" name="register" class="btn-login">Register</button>
        </form>
        <div class="login-footer"><p><a href="login.php">Back to login</a></p></div>
    </div>
</div>
</body>
</html>
