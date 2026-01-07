<?php 
$current_page = basename($_SERVER['PHP_SELF']);
if($_SESSION['role'] == "club_member"){ ?>
	<!-- Sidebar Navigation for Organizer -->
	<div class="sidebar-wrapper">
		<a href="index.php" class="sidebar-logo" style="text-decoration: none; display: block;" title="Go to EWU Portal">
			<i class="fa fa-home" style="font-size: 32px; color: #6e8efb; margin-bottom: 5px; display: block;"></i>
			<div style="font-size: 10px; text-transform: uppercase; color: #fff; letter-spacing: 1.5px; opacity: 0.7;">Home Portal</div>
		</a>
		<nav class="sidebar-nav">
			<a href="dashboard.php" class="nav-link<?= $current_page=='dashboard.php' ? ' active' : '' ?>">
				<i class="fa fa-tachometer" aria-hidden="true"></i>
				<span>Dashboard</span>
			</a>
			<a href="my_task.php" class="nav-link<?= $current_page=='my_task.php' ? ' active' : '' ?>">
				<i class="fa fa-calendar" aria-hidden="true"></i>
				<span>My Tasks</span>
			</a>
			<a href="events.php" class="nav-link<?= $current_page=='events.php' ? ' active' : '' ?>">
				<i class="fa fa-calendar-o" aria-hidden="true"></i>
				<span>Events</span>
			</a>
			<a href="profile.php" class="nav-link<?= $current_page=='profile.php' ? ' active' : '' ?>">
				<i class="fa fa-user" aria-hidden="true"></i>
				<span>Profile</span>
			</a>
			<a href="notifications.php" class="nav-link<?= $current_page=='notifications.php' ? ' active' : '' ?>">
				<i class="fa fa-bell" aria-hidden="true"></i>
				<span>Notifications</span>
			</a>
		</nav>
	</div>
<?php } else if ($_SESSION['role'] == "authority") { ?>
	<!-- Sidebar Navigation for Authority -->
	<div class="sidebar-wrapper">
		<a href="index.php" class="sidebar-logo" style="text-decoration: none; display: block;" title="Go to EWU Portal">
			<i class="fa fa-home" style="font-size: 32px; color: #6e8efb; margin-bottom: 5px; display: block;"></i>
			<div style="font-size: 10px; text-transform: uppercase; color: #fff; letter-spacing: 1.5px; opacity: 0.7;">Home Portal</div>
		</a>
		
		<nav class="sidebar-nav">
			<a href="dashboard.php" class="nav-link<?= $current_page=='dashboard.php' ? ' active' : '' ?>">
				<i class="fa fa-tachometer" aria-hidden="true"></i>
				<span>Dashboard</span>
			</a>
			<a href="clubs.php" class="nav-link<?= $current_page=='clubs.php' ? ' active' : '' ?>">
				<i class="fa fa-university" aria-hidden="true"></i>
				<span>Manage Clubs</span>
			</a>
			<a href="club-admins.php" class="nav-link<?= $current_page=='club-admins.php' ? ' active' : '' ?>">
				<i class="fa fa-users" aria-hidden="true"></i>
				<span>Club Admins</span>
			</a>
			 <a href="events.php" class="nav-link<?= $current_page=='events.php' ? ' active' : '' ?>">
				<i class="fa fa-calendar-o" aria-hidden="true"></i>
				<span>Manage Events</span>
			</a>
			 <a href="notices.php" class="nav-link<?= $current_page=='notices.php' ? ' active' : '' ?>">
				<i class="fa fa-bullhorn" aria-hidden="true"></i>
				<span>Manage Notices</span>
			</a>
			 <a href="user.php" class="nav-link<?= $current_page=='user.php' ? ' active' : '' ?>">
				<i class="fa fa-user-plus" aria-hidden="true"></i>
				<span>Create User</span>
			</a>
			<a href="profile.php" class="nav-link<?= $current_page=='profile.php' ? ' active' : '' ?>">
				<i class="fa fa-user" aria-hidden="true"></i>
				<span>Profile</span>
			</a>
		</nav>
	</div>
<?php } else if ($_SESSION['role'] == "club_admin") { ?>
	<!-- Sidebar Navigation for Club Admin -->
	<div class="sidebar-wrapper">
		<a href="index.php" class="sidebar-logo" style="text-decoration: none; display: block;" title="Go to EWU Portal">
			<i class="fa fa-home" style="font-size: 32px; color: #6e8efb; margin-bottom: 5px; display: block;"></i>
			<div style="font-size: 10px; text-transform: uppercase; color: #fff; letter-spacing: 1.5px; opacity: 0.7;">Home Portal</div>
		</a>
		<nav class="sidebar-nav">
			<a href="dashboard.php" class="nav-link<?= $current_page=='dashboard.php' ? ' active' : '' ?>">
				<i class="fa fa-tachometer" aria-hidden="true"></i>
				<span>Dashboard</span>
			</a>
			<a href="my-club.php" class="nav-link<?= $current_page=='my-club.php' ? ' active' : '' ?>">
				<i class="fa fa-university" aria-hidden="true"></i>
				<span>My Club</span>
			</a>
			<a href="events.php" class="nav-link<?= $current_page=='events.php' ? ' active' : '' ?>">
				<i class="fa fa-calendar-o" aria-hidden="true"></i>
				<span>Events</span>
			</a>
			<a href="tasks.php" class="nav-link<?= $current_page=='tasks.php' ? ' active' : '' ?>">
				<i class="fa fa-calendar" aria-hidden="true"></i>
				<span>Club Tasks</span>
			</a>
			<a href="notices.php" class="nav-link<?= $current_page=='notices.php' ? ' active' : '' ?>">
				<i class="fa fa-bullhorn" aria-hidden="true"></i>
				<span>Manage Notices</span>
			</a>
			 <a href="club-members.php" class="nav-link<?= $current_page=='club-members.php' ? ' active' : '' ?>">
				<i class="fa fa-users" aria-hidden="true"></i>
				<span>Manage Members</span>
			</a>
			<a href="profile.php" class="nav-link<?= $current_page=='profile.php' ? ' active' : '' ?>">
				<i class="fa fa-user" aria-hidden="true"></i>
				<span>Profile</span>
			</a>
		</nav>
	</div>
<?php } ?>
