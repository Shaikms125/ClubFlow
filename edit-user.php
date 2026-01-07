<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "authority")) {
    include "DB_connection.php";
    include "app/Model/User.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: user.php");
    	 exit();
    }
    $id = $_GET['id'];
    $user = get_user_by_id($conn, $id);

    if ($user == 0) {
    	 header("Location: user.php");
    	 exit();
    }

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit User</title>
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
						Edit User
					</span>
					<div class="action-buttons">
						<a href="user.php" class="btn-secondary">
							<i class="fa fa-arrow-left"></i>
							Back
						</a>
					</div>
				</div>
				
				<form class="form-1" method="POST" action="app/update-user.php">
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
						<lable>Full Name</lable>
						<input type="text" name="full_name" class="input-1" placeholder="Full Name" value="<?=$user['full_name']?>" required>
					</div>
					
					<div class="input-holder">
						<lable>Username</lable>
						<input type="text" name="user_name" value="<?=$user['username']?>" class="input-1" placeholder="Username" required>
					</div>
					
					<div class="input-holder">
						<lable>Password</lable>
						<input type="password" name="password" class="input-1" placeholder="Leave empty to keep current password">
					</div>
					
					<input type="text" name="id" value="<?=$user['id']?>" hidden>

					<button type="submit" class="btn" style="width: 100%; margin-top: 20px;">
						<i class="fa fa-save"></i>
						Update User
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
