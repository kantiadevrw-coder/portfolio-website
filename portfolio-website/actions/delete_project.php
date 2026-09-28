<?php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['delete_project'])) { header('Location: ../admin/projects.php'); exit(); }
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { set_flash('error_message','Invalid form token.'); header('Location: ../admin/projects.php'); exit(); }
$id=filter_var($_POST['id']??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
if(!$id){set_flash('error_message','Invalid project ID.');}else{$stmt=mysqli_prepare($conn,'DELETE FROM projects WHERE id=?');mysqli_stmt_bind_param($stmt,'i',$id);mysqli_stmt_execute($stmt);set_flash(mysqli_stmt_affected_rows($stmt)>0?'success_message':'error_message',mysqli_stmt_affected_rows($stmt)>0?'Project deleted successfully.':'Project not found.');mysqli_stmt_close($stmt);}
header('Location: ../admin/projects.php');exit();
?>
