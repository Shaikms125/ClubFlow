<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "authority") {
    include "DB_connection.php";
    include "app/Model/User.php";
    include "app/Model/Club.php";

    $users = get_all_users($conn);
    $clubs = get_all_clubs($conn);
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Assign Club Admin</title>
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
						Assign Club Admin
					</span>
                    <div class="action-buttons">
                        <?php 
                        $back_url = "club-admins.php";
                        if(isset($_GET['club_id']) && !empty($_GET['club_id'])){
                            $back_url = "single-club.php?id=" . $_GET['club_id'];
                        }
                        ?>
                        <a href="<?=$back_url?>" class="btn-secondary">
                            <i class="fa fa-arrow-left"></i>
                            Back
                        </a>
                    </div>
				</div>
			    <form action="app/assign-club-admin.php" method="POST" class="form-1">
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
					  <label>Select User</label>
					  <select name="user_id" class="input-1" required>
                          <option value="">Select User</option>
                          <?php if($users != 0){ 
                                foreach($users as $user){
                                    if($user['role'] == 'club_member'){
                          ?>
                              <option value="<?=$user['id']?>"><?=$user['full_name']?> (<?=$user['username']?>)</option>
                          <?php } } } ?>
                      </select>
					</div>
                    <div class="input-holder">
					  <label>Select Club</label>
					  <select name="club_id" class="input-1" required>
                          <option value="">Select Club</option>
                          <?php if($clubs != 0){ 
                                foreach($clubs as $club){
                          ?>
                              <option value="<?=$club['id']?>"><?=$club['name']?></option>
                          <?php } } ?>
                      </select>
					</div>
					
					<button class="btn-primary">Assign</button>
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
