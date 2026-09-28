<?php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) { set_flash('error_message', 'Invalid project ID.'); header('Location: projects.php'); exit(); }
$stmt = mysqli_prepare($conn, 'SELECT * FROM projects WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$project = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
if (!$project) { set_flash('error_message', 'Project not found.'); header('Location: projects.php'); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Project - Portfolio Admin</title>
<link rel="stylesheet" href="assets/css/admin-style.css"><link rel="stylesheet" href="assets/css/project-form.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-wrapper">
<aside class="sidebar"><div class="sidebar-header"><h3>Portfolio Admin</h3></div><nav class="sidebar-nav">
<a href="dashboard.php" class="nav-item"><i class="fas fa-dashboard"></i> Dashboard</a><a href="messages.php" class="nav-item"><i class="fas fa-envelope"></i> Messages</a><a href="projects.php" class="nav-item active"><i class="fas fa-project-diagram"></i> Projects</a><a href="logout.php" class="nav-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav></aside>
<main class="admin-main project-form-page"><div class="project-form-card">
<div class="project-form-head"><div><h1><i class="fas fa-pen-to-square"></i> Edit Project</h1><p>Update the information for <strong><?php echo e($project['title']); ?></strong>.</p></div><a href="projects.php" class="btn-form btn-cancel"><i class="fas fa-arrow-left"></i> Back</a></div>
<form action="../actions/update_project.php" method="post">
<input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>"><input type="hidden" name="id" value="<?php echo (int)$project['id']; ?>">
<div class="form-grid">
<div class="form-group full"><label for="title">Project Title <span class="required">*</span></label><input class="form-control" type="text" id="title" name="title" maxlength="200" required value="<?php echo e($project['title']); ?>"></div>
<div class="form-group full"><label for="description">Description <span class="required">*</span></label><textarea class="form-control" id="description" name="description" required><?php echo e($project['description']); ?></textarea></div>
<div class="form-group full"><label for="technologies">Technologies <span class="required">*</span></label><input class="form-control" type="text" id="technologies" name="technologies" maxlength="255" required value="<?php echo e($project['technologies']); ?>"></div>
<div class="form-group"><label for="image_url">Image URL</label><input class="form-control" type="url" id="image_url" name="image_url" maxlength="500" value="<?php echo e($project['image_url']); ?>" placeholder="https://example.com/image.jpg"></div>
<div class="form-group"><label for="project_url">Project URL</label><input class="form-control" type="url" id="project_url" name="project_url" maxlength="500" value="<?php echo e($project['project_url']); ?>" placeholder="https://example.com"></div>
<div class="form-group"><label for="github_url">GitHub URL</label><input class="form-control" type="url" id="github_url" name="github_url" maxlength="500" value="<?php echo e($project['github_url']); ?>" placeholder="https://github.com/username/project"></div>
<div class="form-group"><label for="display_order">Display Order</label><input class="form-control" type="number" id="display_order" name="display_order" min="0" max="9999" value="<?php echo (int)$project['display_order']; ?>"></div>
<div class="form-group"><label for="status">Status</label><select class="form-control" id="status" name="status"><option value="1" <?php echo (int)$project['status'] === 1 ? 'selected' : ''; ?>>Active</option><option value="0" <?php echo (int)$project['status'] === 0 ? 'selected' : ''; ?>>Inactive</option></select></div>
</div>
<div class="form-actions"><button type="submit" name="update_project" value="1" class="btn-form btn-save"><i class="fas fa-save"></i> Save Changes</button><a href="projects.php" class="btn-form btn-cancel">Cancel</a></div>
</form></div></main></div>
</body></html>
