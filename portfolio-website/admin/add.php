<?php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Project - Portfolio Admin</title>
<link rel="stylesheet" href="assets/css/admin-style.css">
<link rel="stylesheet" href="assets/css/project-form.css">
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
<main class="admin-main project-form-page">
<div class="project-form-card">
<div class="project-form-head"><div><h1><i class="fas fa-plus-circle"></i> Add New Project</h1><p>Enter the project information that will appear in your portfolio.</p></div><a href="projects.php" class="btn-form btn-cancel"><i class="fas fa-arrow-left"></i> Back</a></div>
<form action="../actions/add_project.php" method="post">
<input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
<div class="form-grid">
<div class="form-group full"><label for="title">Project Title <span class="required">*</span></label><input class="form-control" type="text" id="title" name="title" maxlength="200" required placeholder="e.g. Portfolio Management System"><span class="help-text">Maximum 200 characters.</span></div>
<div class="form-group full"><label for="description">Description <span class="required">*</span></label><textarea class="form-control" id="description" name="description" required placeholder="Describe what the project does, its purpose, and the main features."></textarea></div>
<div class="form-group full"><label for="technologies">Technologies <span class="required">*</span></label><input class="form-control" type="text" id="technologies" name="technologies" maxlength="255" required placeholder="PHP, MySQL, HTML, CSS, JavaScript"><span class="help-text">List the technologies separated by commas.</span></div>
<div class="form-group"><label for="image_url">Image URL</label><input class="form-control" type="url" id="image_url" name="image_url" maxlength="500" placeholder="https://example.com/project-image.jpg"><span class="help-text">Optional. Must be a valid URL.</span></div>
<div class="form-group"><label for="project_url">Project URL</label><input class="form-control" type="url" id="project_url" name="project_url" maxlength="500" placeholder="https://example.com"><span class="help-text">Optional live project link.</span></div>
<div class="form-group"><label for="github_url">GitHub URL</label><input class="form-control" type="url" id="github_url" name="github_url" maxlength="500" placeholder="https://github.com/username/project"><span class="help-text">Optional repository link.</span></div>
<div class="form-group"><label for="display_order">Display Order</label><input class="form-control" type="number" id="display_order" name="display_order" min="0" max="9999" value="0"><span class="help-text">Lower numbers appear first.</span></div>
<div class="form-group"><label for="status">Status</label><select class="form-control" id="status" name="status"><option value="1" selected>Active</option><option value="0">Inactive</option></select><span class="help-text">Inactive projects can remain saved without being displayed.</span></div>
</div>
<div class="form-actions"><button type="submit" name="add_project" value="1" class="btn-form btn-save"><i class="fas fa-save"></i> Add Project</button><a href="projects.php" class="btn-form btn-cancel">Cancel</a></div>
</form>
</div></main></div>
</body></html>
