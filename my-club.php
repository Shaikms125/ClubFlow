<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "club_admin") {
    include "DB_connection.php";
    include "app/Model/Club.php";
    include "app/Model/User.php";
    
    $club_id = get_club_id_by_admin($conn, $_SESSION['id']);
    $club = get_club_by_id($conn, $club_id);
    $members = get_club_members($conn, $club_id);

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>My Club</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<div class="main-container">
			<?php include "inc/header.php" ?>
			<div class="content">

				<h1 class="section-title">
					<span class="title-text">
						<i class="fa fa-university"></i>
						My Club: <?php if($club != 0) echo $club['name']; else echo "No Club Assigned"; ?>
					</span>
                    <?php if($club != 0) { ?>
                    <span style="font-size: 16px; font-weight: 500; color: var(--text-secondary); display: flex; align-items: center; gap: 8px;">
                        <i class="fa fa-users" style="color: var(--primary-color); font-size: 18px;"></i> 
                        <?= $members != 0 ? count($members) : 0 ?> Members
                    </span>
                    <?php } ?>
				</h1>
				
                <?php if($club != 0) { ?>
                
                <h1 class="section-title" style="margin-top: 30px;">
                    <span class="title-text">Club Members</span>
                </h1>
                
                <?php if ($members != 0) { ?>
					<div class="table-container">
						<table class="main-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Username</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=0; foreach ($members as $member) { ?>
								<tr>
									<td><?=++$i?></td>
									<td><?=$member['full_name']?></td>
									<td><?=$member['username']?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				<?php } else { ?>
					<div style="text-align: center; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
						<i style="font-size: 48px; color: #ccc; display: block; margin-bottom: 15px;" class="fa fa-inbox"></i>
						<h3 style="color: #7F8C8D;">No members yet</h3>
					</div>
				<?php } ?>
                
                <?php } else { ?>
                     <div class="danger">You are not assigned to manage any club yet.</div>
                <?php } ?>
                
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
