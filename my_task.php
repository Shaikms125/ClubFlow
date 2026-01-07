<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";

    // Fetch User Info to check Club Membership
    include "app/Model/Club.php";

    // Fetch User Info to check Club Membership
    $user_club_id = get_user_club_id($conn, $_SESSION['id']);

    $tasks = get_all_tasks_by_id($conn, $_SESSION['id']);

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>My Tasks</title>
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
						<i class="fa fa-calendar"></i>
						My Tasks
					</span>
				</div>
				
				<?php if (isset($_GET['success'])) {?>
					<div class="success" role="alert">
						<?php echo stripcslashes($_GET['success']); ?>
					</div>
				<?php } ?>
				
				<?php if (empty($user_club_id) && $_SESSION['role'] == 'club_member') { ?>
                    <div class="no-club-widget">
                        <i class="fa fa-frown-o"></i>
                        <h2>You are not a member of any club</h2>
                     </div>
                <?php } else {
                    if ($tasks != 0) { ?>
					<div class="table-container">
						<table class="main-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Description</th>
									<th>Status</th>
									<th>Due Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=0; foreach ($tasks as $task) { ?>
								<tr>
									<td><?=++$i?></td>
									<td><?=$task['title']?></td>
									<td><?=$task['description']?></td>
									<td>
										<?php
											$statusClass = '';
											if ($task['status'] == "pending") $statusClass = 'pending';
											else if ($task['status'] == "in_progress") $statusClass = 'in_progress';
											else if ($task['status'] == "completed") $statusClass = 'completed';
											else $statusClass = 'pending';
										?>
										<span class="status-badge <?=$statusClass?>"><?=$task['status']?></span>
									</td>
									<td><?=$task['due_date']?></td>
									<td>
										<div class="table-actions">
											<a href="edit-task-employee.php?id=<?=$task['id']?>" class="icon-btn edit" title="Edit">
												<i class="fa fa-edit"></i>
											</a>
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
						<h3 style="color: #7F8C8D;">No tasks assigned to you</h3>
					</div>
				<?php } } ?>
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
