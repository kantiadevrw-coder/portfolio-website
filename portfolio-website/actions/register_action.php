<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['register'])) {
    header('Location: ../admin/register.php');
    exit();
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('error_message', 'Invalid form token. Please try again.');
    header('Location: ../admin/register.php');
    exit();
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $username)) {
    set_flash('error_message', 'Username must be 3-50 characters and contain only letters, numbers, or underscores.');
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error_message', 'Please enter a valid email address.');
} elseif (strlen($password) < 8) {
    set_flash('error_message', 'Password must be at least 8 characters.');
} elseif ($password !== $confirm) {
    set_flash('error_message', 'Passwords do not match.');
} else {
    $check = mysqli_prepare($conn, 'SELECT id FROM admins WHERE username = ? OR email = ? LIMIT 1');
    mysqli_stmt_bind_param($check, 'ss', $username, $email);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);
    $exists = mysqli_fetch_assoc($result);
    mysqli_stmt_close($check);

    if ($exists) {
        set_flash('error_message', 'That username or email is already registered.');
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, 'INSERT INTO admins (username, password, email) VALUES (?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'sss', $username, $hash, $email);
        if (mysqli_stmt_execute($stmt)) {
            set_flash('success_message', 'Registration successful. You can now log in.');
        } else {
            set_flash('error_message', 'Unable to create the account. Please try again.');
        }
        mysqli_stmt_close($stmt);
    }
}

header('Location: ../admin/register.php');
exit();
?>
