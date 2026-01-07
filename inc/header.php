<header class="header">
	<div class="header-left">
		<!-- Logo removed - title now in sidebar -->
	</div>
	<div class="header-right">
        <?php 
        // Logic to get Club Name
        $header_club_name = "";
        if(isset($_SESSION['role']) && isset($_SESSION['id'])){
            if($_SESSION['role'] == 'club_member' || $_SESSION['role'] == 'club_admin'){
                // Need to fetch user's club_id
                // Assuming DB_connection.php and User.php are included by the parent page usually, 
                // but header might need to be safe. 
                // However, header is included inside pages that include DB.
                // Let's safe check or assume variables exist.
                // Best to run a quick query if variables aren't global.
                // Usually parent has $conn.
                if(isset($conn)){
                   if($_SESSION['role'] == 'club_admin'){
                       include_once "app/Model/Club.php";
                       $cid = get_club_id_by_admin($conn, $_SESSION['id']);
                       if($cid){
                           $cinfo = get_club_by_id($conn, $cid);
                           if($cinfo) $header_club_name = $cinfo['name'];
                       }
                   }else if($_SESSION['role'] == 'club_member'){
                        include_once "app/Model/Club.php";
                        $cid = get_user_club_id($conn, $_SESSION['id']);
                        if($cid){
                             $cinfo = get_club_by_id($conn, $cid);
                             if($cinfo) $header_club_name = $cinfo['name'];
                        }
                   }
                }
            }
        }
        
        if(!empty($header_club_name)){
        ?>
        <div class="header-club-name">
            <i class="fa fa-university"></i> <?=$header_club_name?>
        </div>
        <?php } ?>
        
		<div class="user-profile-pill">
			<div class="avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
			<div class="user-info-text">
				<div class="user-type"><?php 
					if($_SESSION['role'] == 'club_member'){
						echo "Member";
					}else if($_SESSION['role'] == 'club_admin'){
						echo "Club Admin";
					}else{
						echo ucfirst($_SESSION['role']);
					}
				?></div>
				<div class="username"><?=$_SESSION['username']?></div>
			</div>
		</div>
		<span class="notification" id="notificationBtn">
			<i class="fa fa-bell" aria-hidden="true"></i>
			<span id="notificationNum"></span>
		</span>
		<a href="logout.php" class="logout-btn">
			<i class="fa fa-sign-out" aria-hidden="true"></i>
		</a>
	</div>
</header>
<div class="notification-bar" id="notificationBar">
	<ul id="notifications">
	
	</ul>
</div>
<script type="text/javascript">
	var openNotification = false;

	const notification = ()=> {
		let notificationBar = document.querySelector("#notificationBar");
		if (openNotification) {
			notificationBar.classList.remove('open-notification');
			openNotification = false;
		}else {
			notificationBar.classList.add('open-notification');
			openNotification = true;
		}
	}
	let notificationBtn = document.querySelector("#notificationBtn");
	notificationBtn.addEventListener("click", notification);
</script>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script type="text/javascript">
	$(document).ready(function(){

       $("#notificationNum").load("app/notification-count.php");
       $("#notifications").load("app/notification.php");

   });
</script>