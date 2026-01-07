<?php 

function get_all_users($conn){
	$sql = "SELECT * FROM users WHERE role != 'admin' ORDER BY id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	if($stmt->rowCount() > 0){
		$users = $stmt->fetchAll();
	}else $users = 0;

	return $users;
}

function count_all_users($conn){
	$sql = "SELECT id FROM users WHERE role != 'admin'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function get_all_users_paginated($conn, $offset, $limit){
	$offset = (int)$offset;
	$limit = (int)$limit;
	$sql = "SELECT * FROM users WHERE role != 'admin' ORDER BY id DESC LIMIT $limit OFFSET $offset";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	if($stmt->rowCount() > 0){
		$users = $stmt->fetchAll();
	}else $users = 0;

	return $users;
}


function search_users_paginated($conn, $search, $offset, $limit){
	$offset = (int)$offset;
	$limit = (int)$limit;
	$key = "%$search%";
	$sql = "SELECT * FROM users 
	        WHERE role != 'admin' 
	        AND (full_name LIKE ? OR username LIKE ? OR role LIKE ?)
	        ORDER BY id DESC LIMIT $limit OFFSET $offset";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$key, $key, $key]);

	if($stmt->rowCount() > 0){
		$users = $stmt->fetchAll();
	}else $users = 0;

	return $users;
}

function count_search_users($conn, $search){
	$key = "%$search%";
	$sql = "SELECT id FROM users 
	        WHERE role != 'admin' 
	        AND (full_name LIKE ? OR username LIKE ? OR role LIKE ?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$key, $key, $key]);

	return $stmt->rowCount();
}




function is_username_unique($conn, $username, $exclude_id=null){
	$username = strtolower($username);
	$sql = "SELECT id FROM users WHERE username = ?";
    $params = [$username];
    
    if($exclude_id){
        $sql .= " AND id != ?";
        $params[] = $exclude_id;
    }
    
	$stmt = $conn->prepare($sql);
	$stmt->execute($params);
	return $stmt->rowCount() == 0;
}

function insert_user($conn, $data){
	// Enforce lowercase username (index 1)
	if(isset($data[1])) {
		$data[1] = strtolower($data[1]);
	}
	$sql = "INSERT INTO users (full_name, username, password, role) VALUES(?,?,?, ?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function update_user($conn, $data){
	// Enforce lowercase username (index 1)
	if(isset($data[1])) {
		$data[1] = strtolower($data[1]);
	}
	$sql = "UPDATE users SET full_name=?, username=?, password=?, role=? WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function delete_user($conn, $data){
    // Cleanup notifications first
    $sql_notif = "DELETE FROM notifications WHERE recipient=?";
    $stmt_notif = $conn->prepare($sql_notif);
    // data array contains id at index 0
    $stmt_notif->execute([$data[0]]);

	$sql = "DELETE FROM users WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}


function get_user_by_id($conn, $id){
	$sql = "SELECT * FROM users WHERE id =? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$user = $stmt->fetch();
	}else $user = 0;

	return $user;
}

function update_profile($conn, $data){
	$sql = "UPDATE users SET full_name=?,  password=? WHERE id=? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function count_users($conn){
	$sql = "SELECT id FROM users WHERE role='club_member'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}