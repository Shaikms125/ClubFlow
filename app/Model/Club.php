<?php 

function get_all_clubs($conn, $limit=null){
	$sql = "SELECT * FROM clubs ORDER BY id DESC";
    if($limit){
        $limit = (int)$limit;
        $sql .= " LIMIT $limit";
    }
	$stmt = $conn->prepare($sql);
	$stmt->execute();

	if ($stmt->rowCount() > 0) {
		$clubs = $stmt->fetchAll();
	}else {
		$clubs = 0;
	}

    return $clubs;
}

function count_clubs($conn){
	$sql = "SELECT id FROM clubs";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	return $stmt->rowCount();
}

function get_all_clubs_paginated($conn, $offset, $limit){
	$offset = (int)$offset;
	$limit = (int)$limit;
	$sql = "SELECT * FROM clubs ORDER BY id DESC LIMIT $limit OFFSET $offset";
	$stmt = $conn->prepare($sql);
	$stmt->execute();

	if ($stmt->rowCount() > 0) {
		$clubs = $stmt->fetchAll();
	}else {
		$clubs = 0;
	}

	return $clubs;
}

function search_clubs_paginated($conn, $search, $offset, $limit){
	$offset = (int)$offset;
	$limit = (int)$limit;
	$key = "%$search%";
	$sql = "SELECT * FROM clubs 
	        WHERE name LIKE ? 
	        ORDER BY id DESC LIMIT $limit OFFSET $offset";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$key]);

	if ($stmt->rowCount() > 0) {
		$clubs = $stmt->fetchAll();
	}else {
		$clubs = 0;
	}

	return $clubs;
}

function count_search_clubs($conn, $search){
	$key = "%$search%";
	$sql = "SELECT id FROM clubs WHERE name LIKE ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$key]);
	return $stmt->rowCount();
}



function get_club_by_id($conn, $id){
	$sql = "SELECT * FROM clubs WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if ($stmt->rowCount() > 0) {
		$club = $stmt->fetch();
		return $club;
	}else {
		return 0;
	}
}


function insert_club($conn, $data){
	$sql = "INSERT INTO clubs (name) VALUES (?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function assign_club_admin($conn, $data){
	$sql = "INSERT INTO club_admins (user_id, club_id) VALUES (?,?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function get_all_club_admins($conn){
	$sql = "SELECT ca.id, u.full_name, c.name as club_name, u.username 
	        FROM club_admins ca 
	        JOIN users u ON ca.user_id = u.id 
	        JOIN clubs c ON ca.club_id = c.id
            ORDER BY ca.id DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute();

	if ($stmt->rowCount() > 0) {
		$admins = $stmt->fetchAll();
	}else {
		$admins = 0;
	}

	return $admins;
}

function get_all_club_admins_paginated($conn, $offset, $limit){
	$offset = (int)$offset;
	$limit = (int)$limit;
	$sql = "SELECT ca.id, u.full_name, c.name as club_name, u.username 
	        FROM club_admins ca 
	        JOIN users u ON ca.user_id = u.id 
	        JOIN clubs c ON ca.club_id = c.id
            ORDER BY ca.id DESC LIMIT $limit OFFSET $offset";
	$stmt = $conn->prepare($sql);
	$stmt->execute();

	if ($stmt->rowCount() > 0) {
		$admins = $stmt->fetchAll();
	}else {
		$admins = 0;
	}

	return $admins;
}

function count_club_admins($conn){
	$sql = "SELECT id FROM club_admins";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	return $stmt->rowCount();
}

function search_club_admins_paginated($conn, $search, $offset, $limit){
	$offset = (int)$offset;
	$limit = (int)$limit;
	$key = "%$search%";
	$sql = "SELECT ca.id, u.full_name, c.name as club_name, u.username 
	        FROM club_admins ca 
	        JOIN users u ON ca.user_id = u.id 
	        JOIN clubs c ON ca.club_id = c.id
	        WHERE u.full_name LIKE ? OR c.name LIKE ? OR u.username LIKE ?
            ORDER BY ca.id DESC LIMIT $limit OFFSET $offset";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$key, $key, $key]);

	if ($stmt->rowCount() > 0) {
		$admins = $stmt->fetchAll();
	}else {
		$admins = 0;
	}

	return $admins;
}

function count_search_club_admins($conn, $search){
	$key = "%$search%";
	$sql = "SELECT ca.id 
	        FROM club_admins ca 
	        JOIN users u ON ca.user_id = u.id 
	        JOIN clubs c ON ca.club_id = c.id
	        WHERE u.full_name LIKE ? OR c.name LIKE ? OR u.username LIKE ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$key, $key, $key]);
	return $stmt->rowCount();
}


function get_club_members($conn, $club_id){
    $sql = "SELECT u.id, u.full_name, u.username, u.role FROM club_members cm
            JOIN users u ON cm.user_id = u.id
            WHERE cm.club_id = ? ORDER BY u.full_name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$club_id]);
    
    if($stmt->rowCount() > 0){
        $members = $stmt->fetchAll();
    }else $members = 0;
    return $members;
}

function add_club_member($conn, $data){
    $sql = "INSERT INTO club_members (user_id, club_id) VALUES(?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function get_club_id_by_admin($conn, $id){
    $sql = "SELECT club_id FROM club_admins WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    
    if($stmt->rowCount() > 0){
        $res = $stmt->fetch();
        return $res['club_id'];
    }else return 0;
}
function get_user_club_id($conn, $user_id){
    $sql = "SELECT club_id FROM club_members WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    
    if($stmt->rowCount() > 0){
        $res = $stmt->fetch();
        return $res['club_id'];
    }else return 0;
}
function get_club_members_without_club($conn){
    $sql = "SELECT id, full_name, username, role FROM users WHERE role = 'club_member' AND id NOT IN (SELECT user_id FROM club_members) ORDER BY full_name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    if($stmt->rowCount() > 0){
        $res = $stmt->fetchAll();
        return $res;
    }else return 0;
}

function get_club_admins_by_club($conn, $club_id){
	$sql = "SELECT ca.id, u.full_name, u.username 
	        FROM club_admins ca 
	        JOIN users u ON ca.user_id = u.id 
	        WHERE ca.club_id = ?
            ORDER BY u.full_name ASC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$club_id]);

	if ($stmt->rowCount() > 0) {
		$admins = $stmt->fetchAll();
	}else {
		$admins = 0;
	}

	return $admins;
}
?>
