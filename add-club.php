<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "authority") {
  
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Club</title>
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
					<i class="fa fa-university"></i>
						Add Club
					</span>
					<div class="action-buttons">
						<a href="clubs.php" class="btn-secondary">
							<i class="fa fa-arrow-left"></i>
							Back
						</a>
					</div>
				</div>
			    <form action="app/add-club.php" method="POST" class="form-1">
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
					  <label>Club Name</label>
					  <input type="text" name="club_name" class="input-1" placeholder="Enter club name" required>
					</div>
					
					<button class="btn-primary">Add Club</button>
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
