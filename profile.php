<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/User.php";
    $user = get_user_by_id($conn, $_SESSION['id']);
    
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
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
						<i class="fa fa-user"></i>
						My Profile
					</span>
					<a href="edit_profile.php" class="btn-primary">
						<i class="fa fa-edit"></i>
						Edit Profile
					</a>
				</div>
				
				<div style="max-width: 600px;">
					<div class="table-container">
						<table class="main-table">
							<tbody>
								<tr>
									<td style="font-weight: 600; width: 150px;">Full Name</td>
									<td><?=$user['full_name']?></td>
								</tr>
								<tr>
									<td style="font-weight: 600;">Username</td>
									<td>@<?=$user['username']?></td>
								</tr>
								<tr>
									<td style="font-weight: 600;">Joined At</td>
									<td><?=$user['created_at']?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
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
