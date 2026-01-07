<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "authority" || $_SESSION['role'] == "club_admin")) {
    include "DB_connection.php";
    include "app/Model/User.php";
    include "app/Model/Club.php";

    $users = get_all_users($conn);
    if($_SESSION['role'] == 'authority'){
        $clubs = get_all_clubs($conn);
    }else{
        $clubs = [];
        $club_id = get_club_id_by_admin($conn, $_SESSION['id']);
        if($club_id != 0){
             $res = get_club_by_id($conn, $club_id);
             if($res != 0) $clubs[] = $res;
        }
    }
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Club Member</title>
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
						Add Club Member
					</span>
					<div class="action-buttons">
						<?php 
						$back_url = "club-members.php";
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
			    <form action="app/add-club-member.php" method="POST" class="form-1">
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
                          <?php 
                                $available_users = get_club_members_without_club($conn);
                                if($available_users != 0){ 
                                foreach($available_users as $user){
                          ?>
                              <option value="<?=$user['id']?>"><?=$user['full_name']?> (<?=$user['username']?>)</option>
                          <?php } } ?>
                      </select>
					</div>
                    
                    <?php if ($_SESSION['role'] == 'authority') { ?>
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
                    <?php } else { 
                        $my_club_name = "Unknown Club";
                        $my_club_id = 0;
                        if(count($clubs) > 0){
                             $my_club_name = $clubs[0]['name'];
                             $my_club_id = $clubs[0]['id'];
                        }
                    ?>
                        <input type="hidden" name="club_id" value="<?=$my_club_id?>">
                        <div class="input-holder">
                            <label>Club</label>
                            <input type="text" class="input-1" value="<?=$my_club_name?>" disabled readonly>
                        </div>
                    <?php } ?>
					
					<button class="btn btn-primary">Add Member</button>
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
