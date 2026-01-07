<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "authority") {
    include "DB_connection.php";
    include "app/Model/Club.php";

    if(!isset($_GET['id'])){
        header("Location: clubs.php");
        exit();
    }
    
    $club_id = $_GET['id'];
    $club = get_club_by_id($conn, $club_id);

    if($club == 0){
        header("Location: clubs.php");
        exit();
    }

    $members = get_club_members($conn, $club_id);
    $admins = get_club_admins_by_club($conn, $club_id);

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Club Details - <?=$club['name']?></title>
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
						<?=$club['name']?>
					</span>
                    <div class="action-buttons">
                        <a href="clubs.php" class="btn-secondary">
                            <i class="fa fa-arrow-left"></i>
                            Back
                        </a>
						<a href="assign-club-admin.php?club_id=<?=$club_id?>" class="btn-primary">
							<i class="fa fa-user-plus"></i>
							Assign Admin
						</a>
						<a href="add-club-member.php?club_id=<?=$club_id?>" class="btn-primary">
							<i class="fa fa-plus"></i>
							Add Member
						</a>
                    </div>
				</div>
                
                <div class="dashboard" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 40px;">
                    <div class="dashboard-info-tile">
                        <i class="fa fa-users"></i>
                        <div class="item-number"><?= $members != 0 ? count($members) : 0 ?></div>
                        <div class="item-label">Total Members</div>
                    </div>
                    <div class="dashboard-info-tile">
                        <i class="fa fa-user-secret"></i>
                        <div class="item-number"><?= $admins != 0 ? count($admins) : 0 ?></div>
                        <div class="item-label">Club Admins</div>
                    </div>
                     <div class="dashboard-info-tile">
                        <i class="fa fa-calendar"></i>
                        <div class="item-number"><?= date("M d, Y", strtotime($club['created_at'])) ?></div>
                        <div class="item-label">Created At</div>
                    </div>
                </div>

                <!-- Club Admins Section -->
                <h2 class="section-title" style="font-size: 20px; margin-bottom: 15px;">
                    <span class="title-text">Club Admins</span>
                </h2>
				
				<?php if ($admins != 0) { ?>
					<div class="table-container">
						<table class="main-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Username</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=0; foreach ($admins as $admin) { ?>
								<tr>
									<td><?=++$i?></td>
									<td><?=$admin['full_name']?></td>
									<td><?=$admin['username']?></td>
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
					<div class="empty-state" style="padding: 20px; margin-bottom: 30px;">
						<p>No admins assigned to this club yet.</p>
					</div>
				<?php } ?>

                <!-- Club Members Section -->
                <h2 class="section-title" style="font-size: 20px; margin-bottom: 15px; margin-top: 40px;">
                    <span class="title-text">Club Members</span>
                </h2>

				<?php if ($members != 0) { ?>
					<div class="table-container">
						<table class="main-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Username</th>
									<th>Role</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=0; foreach ($members as $member) { ?>
								<tr>
									<td><?=++$i?></td>
									<td><?=$member['full_name']?></td>
									<td><?=$member['username']?></td>
									<td>
                                        <span class="status-badge pending" style="background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-color);">
                                            <?=ucfirst($member['role'])?>
                                        </span>
                                    </td>
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
					<div class="empty-state" style="padding: 20px;">
						<p>No members in this club yet.</p>
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
