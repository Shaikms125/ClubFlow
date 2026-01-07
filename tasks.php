<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "club_admin")) {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    
    $club_id = 0;
    if ($_SESSION['role'] == "club_admin") {
        include "app/Model/Club.php";
        $club_id = get_club_id_by_admin($conn, $_SESSION['id']);
    }

    $text = "All";
    if (isset($_GET['due_date']) &&  $_GET['due_date'] == "Due Today") {
    	$text = "Due Today";
        if($club_id != 0){
            $tasks = get_all_tasks_due_today_by_club($conn, $club_id);
            $num_task = count_tasks_due_today_by_club($conn, $club_id);
        }else{
            $tasks = get_all_tasks_due_today($conn);
            $num_task = count_tasks_due_today($conn);
        }

    }else if (isset($_GET['due_date']) &&  $_GET['due_date'] == "Overdue") {
    	$text = "Overdue";
        if($club_id != 0){
            $tasks = get_all_tasks_overdue_by_club($conn, $club_id);
            $num_task = count_tasks_overdue_by_club($conn, $club_id);
        }else{
            $tasks = get_all_tasks_overdue($conn);
            $num_task = count_tasks_overdue($conn);
        }

    }else if (isset($_GET['due_date']) &&  $_GET['due_date'] == "No Deadline") {
    	$text = "No Deadline";
        if($club_id != 0){
            $tasks = get_all_tasks_NoDeadline_by_club($conn, $club_id);
            $num_task = count_tasks_NoDeadline_by_club($conn, $club_id);
        }else{
             $tasks = get_all_tasks_NoDeadline($conn);
             $num_task = count_tasks_NoDeadline($conn);
        }

    }else{
        if($club_id != 0){
             $tasks = get_all_tasks_by_club($conn, $club_id);
             $num_task = count_tasks_by_club($conn, $club_id);
        }else{
             $tasks = get_all_tasks($conn);
             $num_task = count_tasks($conn);
        }
    }
    $users = get_all_users($conn);
    

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>All Tasks</title>
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
						<?=$text?> Tasks (<?=$num_task?>)
					</span>
					<div class="action-buttons">
						<a href="create_task.php" class="btn-primary">
							<i class="fa fa-plus"></i>
							Create Task
						</a>
						<a href="tasks.php?due_date=Due Today" class="btn-secondary">Due Today</a>
						<a href="tasks.php?due_date=Overdue" class="btn-secondary">Overdue</a>
						<a href="tasks.php?due_date=No Deadline" class="btn-secondary">No Deadline</a>
						<a href="tasks.php" class="btn-secondary">All Tasks</a>
					</div>
				</div>
				
				<?php if (isset($_GET['success'])) {?>
					<div class="success" role="alert">
						<?php echo stripcslashes($_GET['success']); ?>
					</div>
				<?php } ?>
				
				<?php if ($tasks != 0) { ?>
					<div class="table-container">
						<table class="main-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Description</th>
									<th>Assigned To</th>
									<th>Due Date</th>
									<th>Status</th>
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
                                            $assignees = get_task_assignees($conn, $task['id']);
                                            if(!empty($assignees)){
                                                echo implode(", ", $assignees);
                                            } else {
                                                echo "Unassigned";
                                            }
										?>
									</td>
									<td><?php if($task['due_date'] == "") echo "No Deadline"; else echo $task['due_date']; ?></td>
									<td>
										<?php
											$statusClass = '';
											if ($task['status'] == "pending") $statusClass = 'pending';
											else if ($task['status'] == "in_progress") $statusClass = 'in_progress';
											else if ($task['status'] == "completed") $statusClass = 'completed';
											else $statusClass = 'pending'; // Default or fallback
										?>
										<span class="status-badge <?=$statusClass?>"><?=$task['status']?></span>
									</td>
									<td>
										<div class="table-actions">
											<a href="edit-task.php?id=<?=$task['id']?>" class="icon-btn edit" title="Edit">
												<i class="fa fa-edit"></i>
											</a>
											<a href="delete-task.php?id=<?=$task['id']?>" class="icon-btn delete" title="Delete">
												<i class="fa fa-trash"></i>
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
						<h3 style="color: #7F8C8D;">No tasks found</h3>
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
