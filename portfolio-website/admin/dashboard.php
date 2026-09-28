<?php
// Admin dashboard
// Save as: admin/dashboard.php

require_once '../config/database.php';
require_once '../includes/auth_check.php';

// Get statistics
$total_messages_query = "SELECT COUNT(*) as total FROM messages";
$total_messages_result = mysqli_query($conn, $total_messages_query);
$total_messages = mysqli_fetch_assoc($total_messages_result)['total'];

$unread_messages_query = "SELECT COUNT(*) as total FROM messages WHERE is_read = 0";
$unread_messages_result = mysqli_query($conn, $unread_messages_query);
$unread_messages = mysqli_fetch_assoc($unread_messages_result)['total'];

$total_projects_query = "SELECT COUNT(*) as total FROM projects";
$total_projects_result = mysqli_query($conn, $total_projects_query);
$total_projects = mysqli_fetch_assoc($total_projects_result)['total'];

$active_projects_query = "SELECT COUNT(*) as total FROM projects WHERE status = 1";
$active_projects_result = mysqli_query($conn, $active_projects_query);
$active_projects = mysqli_fetch_assoc($active_projects_result)['total'];

// Get recent messages
$recent_messages_query = "SELECT * FROM messages ORDER BY created_at DESC LIMIT 5";
$recent_messages_result = mysqli_query($conn, $recent_messages_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Portfolio</title>
    <link rel="stylesheet" href="assets/css/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Portfolio Admin</h3>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item active">
                    <i class="fas fa-dashboard"></i> Dashboard
                </a>
                <a href="messages.php" class="nav-item">
                    <i class="fas fa-envelope"></i> Messages
                    <?php if($unread_messages > 0): ?>
                        <span class="badge"><?php echo $unread_messages; ?></span>
                    <?php endif; ?>
                </a>
                <a href="projects.php" class="nav-item">
                    <i class="fas fa-project-diagram"></i> Projects
                </a>
                <a href="logout.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_messages; ?></h3>
                        <p>Total Messages</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-envelope-open"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $unread_messages; ?></h3>
                        <p>Unread Messages</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_projects; ?></h3>
                        <p>Total Projects</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $active_projects; ?></h3>
                        <p>Active Projects</p>
                    </div>
                </div>
            </div>
            
            <div class="recent-section">
                <h2>Recent Messages</h2>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($recent_messages_result) > 0): ?>
                                <?php while($message = mysqli_fetch_assoc($recent_messages_result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($message['name']); ?></td>
                                        <td><?php echo htmlspecialchars($message['email']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($message['subject'], 0, 30)); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($message['created_at'])); ?></td>
                                        <td>
                                            <?php if($message['is_read'] == 0): ?>
                                                <span class="status-badge unread">Unread</span>
                                            <?php else: ?>
                                                <span class="status-badge read">Read</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="messages.php" class="btn-small">View</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center;">No messages found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>