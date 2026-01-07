<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "club_member") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: my_task.php");
    	 exit();
    }
    $id = $_GET['id'];
    $task = get_task_by_id($conn, $id);

    if ($task == 0) {
    	 header("Location: my_task.php");
    	 exit();
    }
   $users = get_all_users($conn);
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Task</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">

</head>
<body>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<div class="main-container">
			<?php include "inc/header.php" ?>
			<div class="content">
				<div class="section-title">
					<span class="title-text">
						<i class="fa fa-edit"></i>
						Edit Task Status
					</span>
					<div class="action-buttons">
						<a href="my_task.php" class="btn-secondary">
							<i class="fa fa-arrow-left"></i>
							Back
						</a>
					</div>
				</div>
				
				<form class="form-1" method="POST" action="app/update-task-employee.php">
					<?php if (isset($_GET['error'])) {?>
						<div class="danger" role="alert">
							<?php echo stripcslashes($_GET['error']); ?>
						</div>
					<?php } ?>

					<?php include "inc/csrf_helper.php"; csrf_token(); ?>

					<?php if (isset($_GET['success'])) {?>
						<div class="success" role="alert">
							<?php echo stripcslashes($_GET['success']); ?>
						</div>
					<?php } ?>
					
					<div class="input-holder">
						<lable style="font-weight: 700; color: var(--primary-color); font-size: 16px;">Task Title</lable>
						<p style="padding: 12px; background: var(--light-bg); border-radius: 8px; color: var(--text-dark);">
							<?=$task['title']?>
						</p>
					</div>
					
					<div class="input-holder">
						<lable style="font-weight: 700; color: var(--primary-color); font-size: 16px;">Description</lable>
						<p style="padding: 12px; background: var(--light-bg); border-radius: 8px; color: var(--text-dark); white-space: pre-wrap;">
							<?=$task['description']?>
						</p>
					</div>
					
					<div class="input-holder">
						<lable>Update Status</lable>
						<select name="status" class="input-1" required>
							<option value="">Select Status</option>
							<option value="pending" <?php if($task['status'] == "pending") echo "selected"; ?>>Pending</option>
							<option value="in_progress" <?php if($task['status'] == "in_progress") echo "selected"; ?>>In Progress</option>
							<option value="completed" <?php if($task['status'] == "completed") echo "selected"; ?>>Completed</option>
						</select>
					</div>
					
					<input type="text" name="id" value="<?=$task['id']?>" hidden>
					
					<button type="submit" class="btn" style="width: 100%; margin-top: 20px;">
						<i class="fa fa-save"></i>
						Update Status
					</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: index.php?action=login&error=$em");
   exit();
}
 ?>
