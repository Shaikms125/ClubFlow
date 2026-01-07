<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "authority" || $_SESSION['role'] == "club_admin")) {
  
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Add User</title>
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
						<i class="fa fa-user-plus"></i>
						Add User
					</span>
					<div class="action-buttons">
						<?php 
						// Determine back URL based on role
						$back_url = "user.php"; // Default for authority
						if($_SESSION['role'] == 'club_admin'){
							$back_url = "club-members.php";
						}
						?>
						<a href="<?=$back_url?>" class="btn-secondary">
							<i class="fa fa-arrow-left"></i>
							Back
						</a>
					</div>
				</div>
				
				<form class="form-1" method="POST" action="app/add-user.php">
					<?php if (isset($_GET['error'])) {?>
						<div class="danger" role="alert">
							<?php echo stripcslashes($_GET['error']); ?>
						</div>
					<?php } ?>

					<?php if (isset($_GET['success'])) {?>
						<div class="success" role="alert">
							<?php echo stripcslashes($_GET['success']); ?>
						</div>
					<?php } ?>
					<?php include "inc/csrf_helper.php"; csrf_token(); ?>
					
					<div class="input-holder">
						<lable>Full Name</lable>
						<input type="text" name="full_name" class="input-1" placeholder="Full Name" required>
					</div>
					
					<div class="input-holder">
						<lable>Username</lable>
						<input type="text" name="user_name" class="input-1" placeholder="Username" required>
					</div>
					
					<div class="input-holder">
						<lable>Password</lable>
						<input type="password" name="password" class="input-1" placeholder="Password" required>
					</div>

					<button type="submit" class="btn" style="width: 100%; margin-top: 20px;">
						<i class="fa fa-plus"></i>
						Add User
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
