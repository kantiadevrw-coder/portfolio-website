<?php
require_once '../config/database.php';
require_once '../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_project'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error_message', 'Invalid form token.');
    } else {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id) {
            set_flash('error_message', 'Invalid project ID.');
        } else {
            $stmt = mysqli_prepare($conn, 'DELETE FROM projects WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            set_flash(mysqli_stmt_affected_rows($stmt) > 0 ? 'success_message' : 'error_message', mysqli_stmt_affected_rows($stmt) > 0 ? 'Project deleted successfully.' : 'Project not found.');
            mysqli_stmt_close($stmt);
        }
    }
    header('Location: projects.php');
    exit();
}

$mode = $_GET['action'] ?? '';
$edit_project = null;
if ($mode === 'edit') {
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id) {
        $stmt = mysqli_prepare($conn, 'SELECT * FROM projects WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $edit_project = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        if (!$edit_project) {
            set_flash('error_message', 'Project not found.');
            header('Location: projects.php');
            exit();
        }
    }
}

$success_message = get_flash('success_message');
$error_message = get_flash('error_message');
$projects_result = mysqli_query($conn, 'SELECT * FROM projects ORDER BY display_order ASC, created_at DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Projects - Portfolio</title>
<link rel="stylesheet" href="assets/css/admin-style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-wrapper">
<aside class="sidebar"><div class="sidebar-header"><h3>Portfolio Admin</h3></div><nav class="sidebar-nav">
<a href="dashboard.php" class="nav-item"><i class="fas fa-dashboard"></i> Dashboard</a>
<a href="messages.php" class="nav-item"><i class="fas fa-envelope"></i> Messages</a>
<a href="projects.php" class="nav-item active"><i class="fas fa-project-diagram"></i> Projects</a>
<a href="logout.php" class="nav-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav></aside>
<main class="admin-main">
<div class="admin-header"><h1>Manage Projects</h1><p>Add, edit, or delete your portfolio projects.</p></div>
<?php if ($success_message): ?><div class="alert alert-success"><?php echo e($success_message); ?></div><?php endif; ?>
<?php if ($error_message): ?><div class="alert alert-error"><?php echo e($error_message); ?></div><?php endif; ?>
<p style="margin-bottom:20px"><a href="add.php" class="btn-primary" style="display:inline-block;padding:10px 20px"><i class="fas fa-plus"></i> Add New Project</a></p>
<div class="table-responsive"><table class="data-table"><thead><tr><th>ID</th><th>Image</th><th>Title</th><th>Technologies</th><th>Status</th><th>Order</th><th>Created</th><th>Actions</th></tr></thead><tbody>
<?php if (mysqli_num_rows($projects_result) > 0): while ($project = mysqli_fetch_assoc($projects_result)): ?>
<tr>
<td><?php echo (int)$project['id']; ?></td>
<td><?php if ($project['image_url']): ?><img src="<?php echo e($project['image_url']); ?>" alt="<?php echo e($project['title']); ?>" style="width:50px;height:50px;object-fit:cover"><?php else: ?>No Image<?php endif; ?></td>
<td><?php echo e($project['title']); ?></td><td><?php echo e($project['technologies']); ?></td>
<td><?php echo $project['status'] ? '<span class="status-badge read">Active</span>' : '<span class="status-badge unread">Inactive</span>'; ?></td>
<td><?php echo (int)$project['display_order']; ?></td><td><?php echo e(date('M d, Y', strtotime($project['created_at']))); ?></td>
<td class="action-buttons"><a href="edit.php?id=<?php echo (int)$project['id']; ?>" class="btn-edit">Edit</a>
<form action="projects.php" method="POST" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this project?');"><input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>"><input type="hidden" name="id" value="<?php echo (int)$project['id']; ?>"><button type="submit" name="delete_project" class="btn-delete">Delete</button></form></td>
</tr>
<?php endwhile; else: ?><tr><td colspan="8" style="text-align:center">No projects found. Add your first project.</td></tr><?php endif; ?>
</tbody></table></div>
</main></div>

<?php if ($mode === 'add' || $edit_project): $is_edit = (bool)$edit_project; ?>
<div class="modal-overlay" style="display:flex"><div class="form-container">
<h2><?php echo $is_edit ? 'Edit Project' : 'Add New Project'; ?></h2>
<form action="<?php echo $is_edit ? '../actions/update_project.php' : '../actions/add_project.php'; ?>" method="POST">
<input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
<?php if ($is_edit): ?><input type="hidden" name="id" value="<?php echo (int)$edit_project['id']; ?>"><?php endif; ?>
<div class="form-group"><label for="title">Title *</label><input id="title" type="text" name="title" maxlength="200" value="<?php echo e($edit_project['title'] ?? ''); ?>" required></div>
<div class="form-group"><label for="description">Description *</label><textarea id="description" name="description" required><?php echo e($edit_project['description'] ?? ''); ?></textarea></div>
<div class="form-group"><label for="technologies">Technologies *</label><input id="technologies" type="text" name="technologies" maxlength="255" placeholder="PHP, MySQL, JavaScript" value="<?php echo e($edit_project['technologies'] ?? ''); ?>" required></div>
<div class="form-group"><label for="image_url">Image URL</label><input id="image_url" type="url" name="image_url" maxlength="500" placeholder="https://example.com/image.jpg" value="<?php echo e($edit_project['image_url'] ?? ''); ?>"></div>
<div class="form-group"><label for="project_url">Project URL</label><input id="project_url" type="url" name="project_url" maxlength="500" placeholder="https://your-project.com" value="<?php echo e($edit_project['project_url'] ?? ''); ?>"></div>
<div class="form-group"><label for="github_url">GitHub URL</label><input id="github_url" type="url" name="github_url" maxlength="500" placeholder="https://github.com/your-repo" value="<?php echo e($edit_project['github_url'] ?? ''); ?>"></div>
<div class="form-group"><label for="display_order">Display Order</label><input id="display_order" type="number" name="display_order" min="0" max="9999" value="<?php echo (int)($edit_project['display_order'] ?? 0); ?>"></div>
<div class="form-group"><label for="status">Status</label><select id="status" name="status"><option value="1" <?php echo (($edit_project['status'] ?? 1) == 1) ? 'selected' : ''; ?>>Active</option><option value="0" <?php echo (($edit_project['status'] ?? 1) == 0) ? 'selected' : ''; ?>>Inactive</option></select></div>
<div class="form-group"><button type="submit" name="<?php echo $is_edit ? 'update_project' : 'add_project'; ?>" class="btn-submit"><?php echo $is_edit ? 'Update Project' : 'Add Project'; ?></button> <a href="projects.php" class="btn-danger" style="padding:10px 20px">Cancel</a></div>
</form></div></div>
<?php endif; ?>
</body></html>
