<?php
require_once '../config/database.php';
require_once '../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error_message', 'Invalid form token.');
    } else {
        $id = filter_var($_POST['message_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $action = $_POST['message_action'] ?? '';
        if (!$id) set_flash('error_message', 'Invalid message ID.');
        elseif ($action === 'delete') {
            $stmt = mysqli_prepare($conn, 'DELETE FROM messages WHERE id = ?'); mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt);
            set_flash(mysqli_stmt_affected_rows($stmt) > 0 ? 'success_message' : 'error_message', mysqli_stmt_affected_rows($stmt) > 0 ? 'Message deleted successfully.' : 'Message not found.'); mysqli_stmt_close($stmt);
        } elseif ($action === 'read') {
            $stmt = mysqli_prepare($conn, 'UPDATE messages SET is_read = 1 WHERE id = ?'); mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt); set_flash('success_message', 'Message marked as read.');
        }
    }
    header('Location: messages.php'); exit();
}

$success_message = get_flash('success_message'); $error_message = get_flash('error_message');
$page = max(1, (int)($_GET['page'] ?? 1)); $limit = 10; $offset = ($page - 1) * $limit;
$count_result = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM messages'); $total_messages = (int)mysqli_fetch_assoc($count_result)['total']; $total_pages = max(1, (int)ceil($total_messages / $limit));
$stmt = mysqli_prepare($conn, 'SELECT * FROM messages ORDER BY created_at DESC LIMIT ?, ?'); mysqli_stmt_bind_param($stmt, 'ii', $offset, $limit); mysqli_stmt_execute($stmt); $messages_result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Messages - Admin Dashboard</title><link rel="stylesheet" href="assets/css/admin-style.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
<body><div class="admin-wrapper"><aside class="sidebar"><div class="sidebar-header"><h3>Portfolio Admin</h3></div><nav class="sidebar-nav"><a href="dashboard.php" class="nav-item"><i class="fas fa-dashboard"></i> Dashboard</a><a href="messages.php" class="nav-item active"><i class="fas fa-envelope"></i> Messages</a><a href="projects.php" class="nav-item"><i class="fas fa-project-diagram"></i> Projects</a><a href="logout.php" class="nav-item"><i class="fas fa-sign-out-alt"></i> Logout</a></nav></aside>
<main class="admin-main"><div class="admin-header"><h1>Messages</h1><p>Manage contact form submissions.</p></div>
<?php if($success_message): ?><div class="alert alert-success"><?php echo e($success_message); ?></div><?php endif; ?><?php if($error_message): ?><div class="alert alert-error"><?php echo e($error_message); ?></div><?php endif; ?>
<div class="table-responsive"><table class="data-table"><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if(mysqli_num_rows($messages_result)>0): while($message=mysqli_fetch_assoc($messages_result)): ?><tr><td><?php echo (int)$message['id']; ?></td><td><?php echo e($message['name']); ?></td><td><?php echo e($message['email']); ?></td><td><?php echo e($message['subject']); ?></td><td><?php echo e(strlen($message['message'])>50?substr($message['message'],0,50).'...':$message['message']); ?></td><td><?php echo e(date('M d, Y H:i',strtotime($message['created_at']))); ?></td><td><?php echo $message['is_read'] ? '<span class="status-badge read">Read</span>' : '<span class="status-badge unread">Unread</span>'; ?></td><td class="action-buttons">
<?php if(!$message['is_read']): ?><form action="messages.php" method="POST" style="display:inline"><input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>"><input type="hidden" name="message_id" value="<?php echo (int)$message['id']; ?>"><input type="hidden" name="message_action" value="read"><button type="submit" class="btn-view">Mark Read</button></form><?php endif; ?>
<form action="messages.php" method="POST" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this message?');"><input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>"><input type="hidden" name="message_id" value="<?php echo (int)$message['id']; ?>"><input type="hidden" name="message_action" value="delete"><button type="submit" class="btn-delete">Delete</button></form></td></tr>
<?php endwhile; else: ?><tr><td colspan="8" style="text-align:center">No messages found.</td></tr><?php endif; ?></tbody></table></div>
<?php if($total_pages>1): ?><div class="pagination"><?php for($i=1;$i<=$total_pages;$i++): ?><a href="?page=<?php echo $i; ?>" class="page-link <?php echo $page===$i?'active':''; ?>"><?php echo $i; ?></a><?php endfor; ?></div><?php endif; ?>
</main></div></body></html>
