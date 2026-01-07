<?php

function get_all_notices($conn, $limit=null){
    $sql = "SELECT n.* FROM notices n ORDER BY n.created_at DESC";
    if($limit){
        $limit = (int)$limit;
        $sql .= " LIMIT $limit";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    if($stmt->rowCount() > 0){
        return $stmt->fetchAll();
    }else return 0;
}

function get_notice_by_id($conn, $id){
    $sql = "SELECT * FROM notices WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $notice = $stmt->fetch();
        return $notice;
    }else {
        return 0;
    }
}

function insert_notice($conn, $data){
    $sql = "INSERT INTO notices (title, description, image) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
    return $conn->lastInsertId();
}

function update_notice($conn, $data){
    $sql = "UPDATE notices SET title=?, description=?, image=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function update_notice_no_img($conn, $data){
    $sql = "UPDATE notices SET title=?, description=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function delete_notice($conn, $id){
    $sql = "DELETE FROM notices WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
}

function count_all_notices($conn){
    $sql = "SELECT id FROM notices";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

function get_all_notices_paginated($conn, $offset, $limit){
    $offset = (int)$offset;
    $limit = (int)$limit;
    $sql = "SELECT n.* FROM notices n ORDER BY n.created_at DESC LIMIT $limit OFFSET $offset";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $notices = $stmt->fetchAll();
    }else {
        $notices = 0;
    }

    return $notices;
}

function search_notices_paginated($conn, $search, $offset, $limit){
    $offset = (int)$offset;
    $limit = (int)$limit;
    $key = "%$search%";
    $sql = "SELECT n.* FROM notices n 
            WHERE n.title LIKE ? 
            ORDER BY n.created_at DESC LIMIT $limit OFFSET $offset";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$key]);

    if ($stmt->rowCount() > 0) {
        $notices = $stmt->fetchAll();
    }else {
        $notices = 0;
    }

    return $notices;
}

function count_search_notices($conn, $search){
    $key = "%$search%";
    $sql = "SELECT id FROM notices WHERE title LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$key]);
    return $stmt->rowCount();
}

// Notice Sources functions
function add_notice_source($conn, $notice_id, $source_type, $club_id=null, $department_name=null, $custom_name=null){
    $sql = "INSERT INTO notice_sources (notice_id, source_type, club_id, department_name, custom_name) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$notice_id, $source_type, $club_id, $department_name, $custom_name]);
}

function get_notice_sources($conn, $notice_id){
    $sql = "SELECT ns.*, c.name as club_name FROM notice_sources ns LEFT JOIN clubs c ON ns.club_id = c.id WHERE ns.notice_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$notice_id]);
    
    if($stmt->rowCount() > 0){
        return $stmt->fetchAll();
    }else return 0;
}

function delete_notice_sources($conn, $notice_id){
    $sql = "DELETE FROM notice_sources WHERE notice_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$notice_id]);
}

function get_source_display($sources){
    if(!$sources || $sources == 0) return 'Authority';
    
    $displays = [];
    foreach($sources as $src){
        if($src['source_type'] == 'authority'){
            $displays[] = 'East West Authority';
        } else if($src['source_type'] == 'club'){
            $displays[] = $src['club_name'] ? $src['club_name'] : 'Unknown Club';
        } else if($src['source_type'] == 'department'){
            $displays[] = $src['department_name'] ? $src['department_name'] : 'Unknown Department';
        } else if($src['source_type'] == 'custom'){
            $displays[] = $src['custom_name'] ? $src['custom_name'] : 'Custom Source';
        }
    }
    return implode(', ', array_unique($displays));
}

function get_sources_for_notices($conn, $notice_ids){
    if(empty($notice_ids)) return [];
    
    $in_query = implode(',', array_fill(0, count($notice_ids), '?'));
    
    $sql = "SELECT ns.*, c.name as club_name 
            FROM notice_sources ns 
            LEFT JOIN clubs c ON ns.club_id = c.id 
            WHERE ns.notice_id IN ($in_query)";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute($notice_ids);
    
    $results = [];
    if($stmt->rowCount() > 0){
        $rows = $stmt->fetchAll();
        foreach($rows as $row){
            $results[$row['notice_id']][] = $row;
        }
    }
    return $results;
}


?>
