<?php
require_once '../config/database.php';
require_once '../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['update_project'])) { header('Location: ../admin/projects.php'); exit(); }
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { set_flash('error_message','Invalid form token.'); header('Location: ../admin/projects.php'); exit(); }
$id=filter_var($_POST['id'] ?? null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
if(!$id){set_flash('error_message','Invalid project ID.');header('Location: ../admin/projects.php');exit();}
$title=trim($_POST['title']??'');$description=trim($_POST['description']??'');$technologies=trim($_POST['technologies']??'');$image_url=trim($_POST['image_url']??'');$project_url=trim($_POST['project_url']??'');$github_url=trim($_POST['github_url']??'');$display_order=max(0,(int)($_POST['display_order']??0));$status=((int)($_POST['status']??0)===1)?1:0;
$errors=[];if($title===''||strlen($title)>200)$errors[]='Title is required and must be 200 characters or fewer.';if($description==='')$errors[]='Description is required.';if($technologies===''||strlen($technologies)>255)$errors[]='Technologies are required and must be 255 characters or fewer.';foreach([['Image URL',$image_url],['Project URL',$project_url],['GitHub URL',$github_url]] as [$label,$url])if($url!==''&&!filter_var($url,FILTER_VALIDATE_URL))$errors[]="$label is invalid.";
if($errors){set_flash('error_message',implode(' ',$errors));header('Location: ../admin/projects.php?action=edit&id='.$id);exit();}
$stmt=mysqli_prepare($conn,'UPDATE projects SET title=?,description=?,technologies=?,image_url=?,project_url=?,github_url=?,display_order=?,status=? WHERE id=?');mysqli_stmt_bind_param($stmt,'ssssssiii',$title,$description,$technologies,$image_url,$project_url,$github_url,$display_order,$status,$id);mysqli_stmt_execute($stmt);set_flash(mysqli_stmt_affected_rows($stmt)>=0?'success_message':'error_message', 'Project updated successfully.');mysqli_stmt_close($stmt);header('Location: ../admin/projects.php');exit();
?>
