<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "authority" || $_SESSION['role'] == "club_admin")) {
    include "DB_connection.php";
    include "app/Model/Club.php";

    $members = [];
    if($_SESSION['role'] == 'authority'){
        // Show all members - implementing a query here for simplicity or add to Model
        $sql = "SELECT u.full_name, u.username, c.name as club_name 
                FROM club_members cm 
                JOIN users u ON cm.user_id = u.id 
                JOIN clubs c ON cm.club_id = c.id
                ORDER BY c.name ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0) $members = $stmt->fetchAll();
    }else if($_SESSION['role'] == 'club_admin'){
        $club_id = get_club_id_by_admin($conn, $_SESSION['id']);
        if($club_id != 0){
            $members = get_club_members($conn, $club_id);
            // existing function returns id, full_name, username. No club_name.
            // We can fetch club name separately if needed.
        }
    }
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Club Members</title>
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
						<i class="fa fa-users"></i>
						Club Members
					</span>
					<div class="action-buttons">
						<a href="add-club-member.php" class="btn-primary">
							<i class="fa fa-plus"></i>
							Add Existing User
						</a>
						<a href="add-user.php" class="btn-primary">
							<i class="fa fa-user-plus"></i>
							Create Member
						</a>
					</div>
				</div>
				
				<?php if (isset($_GET['success'])) {?>
					<div class="success" role="alert">
						<?php echo stripcslashes($_GET['success']); ?>
					</div>
				<?php } ?>
				
				<?php if (!empty($members)) { ?>
					<div class="table-container">
						<table class="main-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Member Name</th>
									<th>Username</th>
									<?php if($_SESSION['role'] == 'authority') { ?>
									<th>Club</th>
									<?php } ?>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=0; foreach ($members as $member) { ?>
								<tr>
									<td><?=++$i?></td>
									<td><?=$member['full_name']?></td>
									<td><?=$member['username']?></td>
									<?php if($_SESSION['role'] == 'authority') { ?>
									<td><?=$member['club_name']?></td>
									<?php } ?>
									<td>
										<div class="table-actions">
											<button class="icon-btn delete" disabled title="Remove">
												<i class="fa fa-trash"></i>
											</button>
										</div>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				<?php } else { ?>
					<div style="text-align: center; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
						<i style="font-size: 48px; color: #ccc; display: block; margin-bottom: 15px;" class="fa fa-inbox"></i>
						<h3 style="color: #7F8C8D;">No club members found</h3>
					</div>
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
