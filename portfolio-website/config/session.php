<?php
// Session management file
// Save as: config/session.php

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Regenerate session ID periodically
if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Function to set session message
function set_message($type, $message) {
    $_SESSION[$type] = $message;
}

// Function to get session message
function get_message($type) {
    if (isset($_SESSION[$type])) {
        $message = $_SESSION[$type];
        unset($_SESSION[$type]);
        return $message;
    }
    return '';
}

// Function to check if user has access
function check_admin_access() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}
?>