<?php
// Contact form processing
// Save as: actions/contact_action.php

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {

    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error_message', 'Invalid form token. Please try again.');
        header('Location: ../index.php#contact');
        exit();
    }
    
    // Get and sanitize input
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $subject = sanitize_input($_POST['subject']);
    $message = sanitize_input($_POST['message']);
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (strlen($name) < 2 || strlen($name) > 100) {
        $errors[] = "Name must be between 2 and 100 characters";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    } elseif (strlen($subject) > 200) {
        $errors[] = "Subject must be less than 200 characters";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    } elseif (strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters";
    }
    
    // If no errors, save to database
    if (empty($errors)) {
        
        // Use prepared statement to prevent SQL injection
        $sql = "INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success_message'] = "Thank you! Your message has been sent successfully.";
            } else {
                $_SESSION['error_message'] = "Sorry, there was an error sending your message. Please try again.";
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error_message'] = "Database error. Please try again later.";
        }
        
    } else {
        // Store errors in session
        $_SESSION['error_message'] = implode(", ", $errors);
    }
    
    // Redirect back to contact section
    header("Location: ../index.php#contact");
    exit();
    
} else {
    // If someone tries to access this file directly
    header("Location: ../index.php");
    exit();
}
?>