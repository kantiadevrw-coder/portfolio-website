<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login'])) {
    header('Location: ../admin/login.php');
    exit();
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('error_message', 'Invalid form token. Please try again.');
    header('Location: ../admin/login.php');
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = mysqli_prepare($conn, 'SELECT id, username, password FROM admins WHERE username = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($row && password_verify($password, $row['password'])) {
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $row['id'];
    $_SESSION['admin_username'] = $row['username'];
    $_SESSION['login_time'] = time();
    header('Location: ../admin/dashboard.php');
    exit();
}

set_flash('error_message', 'Invalid username or password.');
header('Location: ../admin/login.php');
exit();
?>
