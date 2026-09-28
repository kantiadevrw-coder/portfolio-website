<?php
require_once '../config/database.php';
require_once '../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['add_project'])) { header('Location: ../admin/projects.php'); exit(); }
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { set_flash('error_message', 'Invalid form token.'); header('Location: ../admin/projects.php?action=add'); exit(); }

$title = trim($_POST['title'] ?? ''); $description = trim($_POST['description'] ?? ''); $technologies = trim($_POST['technologies'] ?? '');
$image_url = trim($_POST['image_url'] ?? ''); $project_url = trim($_POST['project_url'] ?? ''); $github_url = trim($_POST['github_url'] ?? '');
$display_order = max(0, (int)($_POST['display_order'] ?? 0)); $status = isset($_POST['status']) && (int)$_POST['status'] === 1 ? 1 : 0;
$errors=[];
if ($title === '' || strlen($title)>200) $errors[]='Title is required and must be 200 characters or fewer.';
if ($description === '') $errors[]='Description is required.';
if ($technologies === '' || strlen($technologies)>255) $errors[]='Technologies are required and must be 255 characters or fewer.';
foreach ([['Image URL',$image_url],['Project URL',$project_url],['GitHub URL',$github_url]] as [$label,$url]) if ($url !== '' && !filter_var($url,FILTER_VALIDATE_URL)) $errors[]="$label is invalid.";
if ($errors) { set_flash('error_message', implode(' ', $errors)); header('Location: ../admin/projects.php?action=add'); exit(); }
$stmt=mysqli_prepare($conn,'INSERT INTO projects (title,description,technologies,image_url,project_url,github_url,display_order,status) VALUES (?,?,?,?,?,?,?,?)');
mysqli_stmt_bind_param($stmt,'ssssssii',$title,$description,$technologies,$image_url,$project_url,$github_url,$display_order,$status);
if(mysqli_stmt_execute($stmt)) set_flash('success_message','Project added successfully.'); else set_flash('error_message','Unable to add project.');
mysqli_stmt_close($stmt); header('Location: ../admin/projects.php'); exit();
?>
