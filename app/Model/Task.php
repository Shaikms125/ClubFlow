<?php 

function insert_task($conn, $data){
	$sql = "INSERT INTO tasks (title, description, due_date, club_id) VALUES(?,?,?,?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
    return $conn->lastInsertId();
}

function assign_task_to_user($conn, $data){
    $sql = "INSERT INTO task_assignments (task_id, user_id) VALUES(?,?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function get_all_tasks($conn){
	$sql = "SELECT * FROM tasks ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	if($stmt->rowCount() > 0){
		$tasks = $stmt->fetchAll();
	}else $tasks = 0;

	return $tasks;
}

function get_all_tasks_by_club($conn, $club_id){
	$sql = "SELECT * FROM tasks WHERE club_id = ? ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	if($stmt->rowCount() > 0){
		$tasks = $stmt->fetchAll();
	}else $tasks = 0;

	return $tasks;
}
function count_tasks_by_club($conn, $club_id){
	$sql = "SELECT id FROM tasks WHERE club_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	return $stmt->rowCount();
}

function get_all_tasks_due_today_by_club($conn, $club_id){
	$sql = "SELECT * FROM tasks WHERE due_date = CURDATE() AND status != 'completed' AND club_id = ? ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	if($stmt->rowCount() > 0){
		$tasks = $stmt->fetchAll();
	}else $tasks = 0;

	return $tasks;
}
function count_tasks_due_today_by_club($conn, $club_id){
	$sql = "SELECT id FROM tasks WHERE due_date = CURDATE() AND status != 'completed' AND club_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	return $stmt->rowCount();
}

function get_all_tasks_overdue_by_club($conn, $club_id){
	$sql = "SELECT * FROM tasks WHERE due_date < CURDATE() AND status != 'completed' AND club_id = ? ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	if($stmt->rowCount() > 0){
		$tasks = $stmt->fetchAll();
	}else $tasks = 0;

	return $tasks;
}
function count_tasks_overdue_by_club($conn, $club_id){
	$sql = "SELECT id FROM tasks WHERE due_date < CURDATE() AND status != 'completed' AND club_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	return $stmt->rowCount();
}

function get_all_tasks_NoDeadline_by_club($conn, $club_id){
	$sql = "SELECT * FROM tasks WHERE status != 'completed' AND (due_date IS NULL OR due_date = '0000-00-00') AND club_id = ? ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	if($stmt->rowCount() > 0){
		$tasks = $stmt->fetchAll();
	}else $tasks = 0;

	return $tasks;
}
function count_tasks_NoDeadline_by_club($conn, $club_id){
	$sql = "SELECT id FROM tasks WHERE status != 'completed' AND (due_date IS NULL OR due_date = '0000-00-00') AND club_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	return $stmt->rowCount();
}

function count_pending_tasks_by_club($conn, $club_id){
	$sql = "SELECT id FROM tasks WHERE status = 'pending' AND club_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	return $stmt->rowCount();
}

function count_in_progress_tasks_by_club($conn, $club_id){
	$sql = "SELECT id FROM tasks WHERE status = 'in_progress' AND club_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	return $stmt->rowCount();
}

function count_completed_tasks_by_club($conn, $club_id){
	$sql = "SELECT id FROM tasks WHERE status = 'completed' AND club_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	return $stmt->rowCount();
}
function get_all_tasks_due_today($conn){
	$sql = "SELECT * FROM tasks WHERE due_date = CURDATE() AND status != 'completed' ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	if($stmt->rowCount() > 0){
		$tasks = $stmt->fetchAll();
	}else $tasks = 0;

	return $tasks;
}
function count_tasks_due_today($conn){
	$sql = "SELECT id FROM tasks WHERE due_date = CURDATE() AND status != 'completed'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function get_all_tasks_overdue($conn){
	$sql = "SELECT * FROM tasks WHERE due_date < CURDATE() AND status != 'completed' ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	if($stmt->rowCount() > 0){
		$tasks = $stmt->fetchAll();
	}else $tasks = 0;

	return $tasks;
}
function count_tasks_overdue($conn){
	$sql = "SELECT id FROM tasks WHERE due_date < CURDATE() AND status != 'completed'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}


function get_all_tasks_NoDeadline($conn){
	$sql = "SELECT * FROM tasks WHERE status != 'completed' AND due_date IS NULL OR due_date = '0000-00-00' ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	if($stmt->rowCount() > 0){
		$tasks = $stmt->fetchAll();
	}else $tasks = 0;

	return $tasks;
}
function count_tasks_NoDeadline($conn){
	$sql = "SELECT id FROM tasks WHERE status != 'completed' AND due_date IS NULL OR due_date = '0000-00-00'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}



function delete_task($conn, $data){
	$sql = "DELETE FROM tasks WHERE id=? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}


function get_task_by_id($conn, $id){
	$sql = "SELECT * FROM tasks WHERE id =? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$task = $stmt->fetch();
	}else $task = 0;

	return $task;
}
function count_tasks($conn){
	$sql = "SELECT id FROM tasks";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function update_task($conn, $data){
	$sql = "UPDATE tasks SET title=?, description=?, due_date=? WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function delete_task_assignments($conn, $task_id){
    $sql = "DELETE FROM task_assignments WHERE task_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$task_id]);
}

function get_task_assignee_ids($conn, $task_id){
    $sql = "SELECT user_id FROM task_assignments WHERE task_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$task_id]);
    
    if($stmt->rowCount() > 0){
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }else $ids = [];
    
    return $ids;
}

function get_task_assignees($conn, $task_id){
    $sql = "SELECT full_name FROM users JOIN task_assignments ON users.id = task_assignments.user_id WHERE task_assignments.task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$task_id]);

    if($stmt->rowCount() > 0){
        $assignees = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }else $assignees = [];

    return $assignees;
}

function update_task_status($conn, $data){
	$sql = "UPDATE tasks SET status=? WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}


function get_all_tasks_by_id($conn, $id){
	$sql = "SELECT t.* FROM tasks t JOIN task_assignments ta ON t.id = ta.task_id WHERE ta.user_id = ? ORDER BY t.id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$tasks = $stmt->fetchAll();
	}else $tasks = 0;

	return $tasks;
}



function count_pending_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'pending'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function count_in_progress_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'in_progress'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function count_completed_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'completed'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}


function count_my_tasks($conn, $id){
	$sql = "SELECT t.id FROM tasks t JOIN task_assignments ta ON t.id = ta.task_id WHERE ta.user_id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_tasks_overdue($conn, $id){
	$sql = "SELECT t.id FROM tasks t JOIN task_assignments ta ON t.id = ta.task_id WHERE t.due_date < CURDATE() AND t.status != 'completed' AND ta.user_id=? AND t.due_date != '0000-00-00'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_tasks_NoDeadline($conn, $id){
	$sql = "SELECT t.id FROM tasks t JOIN task_assignments ta ON t.id = ta.task_id WHERE ta.user_id=? AND t.status != 'completed' AND (t.due_date IS NULL OR t.due_date = '0000-00-00')";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_pending_tasks($conn, $id){
	$sql = "SELECT t.id FROM tasks t JOIN task_assignments ta ON t.id = ta.task_id WHERE t.status = 'pending' AND ta.user_id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_in_progress_tasks($conn, $id){
	$sql = "SELECT t.id FROM tasks t JOIN task_assignments ta ON t.id = ta.task_id WHERE t.status = 'in_progress' AND ta.user_id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_completed_tasks($conn, $id){
	$sql = "SELECT t.id FROM tasks t JOIN task_assignments ta ON t.id = ta.task_id WHERE t.status = 'completed' AND ta.user_id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}