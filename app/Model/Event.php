<?php

function get_all_events($conn, $limit=null){
    $sql = "SELECT e.* FROM events e ORDER BY e.date DESC, e.created_at DESC";
    if($limit){
        $limit = (int)$limit;
        $sql .= " LIMIT $limit";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $events = $stmt->fetchAll();
    }else {
        $events = 0;
    }

    return $events;
}

function get_upcoming_events($conn, $limit=6){
     $limit = (int)$limit;
     $sql = "SELECT e.* FROM events e ORDER BY e.created_at DESC LIMIT $limit";
     $stmt = $conn->prepare($sql);
     $stmt->execute();
     
     if($stmt->rowCount() > 0){
         return $stmt->fetchAll();
     }else return 0;
}

function get_events_by_club($conn, $club_id){
    $sql = "SELECT * FROM events WHERE id IN (SELECT event_id FROM event_organizers WHERE organizer_type='club' AND club_id=?) ORDER BY date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$club_id]);

    if ($stmt->rowCount() > 0) {
        $events = $stmt->fetchAll();
    }else {
        $events = 0;
    }

    return $events;
}

function get_events_by_club_paginated($conn, $club_id, $offset, $limit){
    $offset = (int)$offset;
    $limit = (int)$limit;
    $sql = "SELECT * FROM events WHERE id IN (SELECT event_id FROM event_organizers WHERE organizer_type='club' AND club_id=?) ORDER BY date DESC LIMIT $limit OFFSET $offset";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$club_id]);

    if ($stmt->rowCount() > 0) {
        $events = $stmt->fetchAll();
    }else {
        $events = 0;
    }

    return $events;
}

function search_events_paginated($conn, $search, $offset, $limit){
    $offset = (int)$offset;
    $limit = (int)$limit;
    $key = "%$search%";
    $sql = "SELECT e.* FROM events e 
            WHERE e.title LIKE ? 
            ORDER BY e.created_at DESC LIMIT $limit OFFSET $offset";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$key]);

    if ($stmt->rowCount() > 0) {
        $events = $stmt->fetchAll();
    }else {
        $events = 0;
    }

    return $events;
}

function count_search_events($conn, $search){
    $key = "%$search%";
    $sql = "SELECT id FROM events WHERE title LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$key]);
    return $stmt->rowCount();
}

function search_events_by_club_paginated($conn, $club_id, $search, $offset, $limit){
    $offset = (int)$offset;
    $limit = (int)$limit;
    $key = "%$search%";
    $sql = "SELECT * FROM events 
            WHERE id IN (SELECT event_id FROM event_organizers WHERE organizer_type='club' AND club_id=?) 
            AND title LIKE ?
            ORDER BY date DESC LIMIT $limit OFFSET $offset";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$club_id, $key]);

    if ($stmt->rowCount() > 0) {
        $events = $stmt->fetchAll();
    }else {
        $events = 0;
    }

    return $events;
}

function count_search_events_by_club($conn, $club_id, $search){
    $key = "%$search%";
    $sql = "SELECT id FROM events 
            WHERE id IN (SELECT event_id FROM event_organizers WHERE organizer_type='club' AND club_id=?) 
            AND title LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$club_id, $key]);
    return $stmt->rowCount();
}

function count_events_by_club($conn, $club_id){
    $sql = "SELECT id FROM events WHERE id IN (SELECT event_id FROM event_organizers WHERE organizer_type='club' AND club_id=?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$club_id]);
    return $stmt->rowCount();
}


function get_event_by_id($conn, $id){
    $sql = "SELECT * FROM events WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $event = $stmt->fetch();
        return $event;
    }else {
        return 0;
    }
}

function insert_event($conn, $data){
    $sql = "INSERT INTO events (title, description, image, date, place) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
    return $conn->lastInsertId();
}

function update_event($conn, $data){
    $sql = "UPDATE events SET title=?, description=?, image=?, date=?, place=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function update_event_no_img($conn, $data){
    $sql = "UPDATE events SET title=?, description=?, date=?, place=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function delete_event($conn, $id){
    $sql = "DELETE FROM events WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
}

function count_all_events($conn){
    $sql = "SELECT id FROM events";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

function get_all_events_paginated($conn, $offset, $limit){
    $offset = (int)$offset;
    $limit = (int)$limit;
    $sql = "SELECT e.* FROM events e ORDER BY e.created_at DESC LIMIT $limit OFFSET $offset";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $events = $stmt->fetchAll();
    }else {
        $events = 0;
    }

    return $events;
}

// Event Organizers functions
function add_event_organizer($conn, $event_id, $organizer_type, $club_id=null, $department_name=null){
    $sql = "INSERT INTO event_organizers (event_id, organizer_type, club_id, department_name) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$event_id, $organizer_type, $club_id, $department_name]);
}

function get_event_organizers($conn, $event_id){
    $sql = "SELECT eo.*, c.name as club_name FROM event_organizers eo LEFT JOIN clubs c ON eo.club_id = c.id WHERE eo.event_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$event_id]);
    
    if($stmt->rowCount() > 0){
        return $stmt->fetchAll();
    }else return 0;
}

function delete_event_organizers($conn, $event_id){
    $sql = "DELETE FROM event_organizers WHERE event_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$event_id]);
}

function get_organizers_display($organizers){
    if($organizers == 0) return 'Unknown';
    
    $displays = [];
    foreach($organizers as $org){
        if($org['organizer_type'] == 'authority'){
            $displays[] = 'East West Authority';
        } else if($org['organizer_type'] == 'club'){
            $displays[] = $org['club_name'] ? $org['club_name'] : 'Unknown Club';
        } else if($org['organizer_type'] == 'department'){
            $displays[] = $org['department_name'] ? $org['department_name'] : 'Unknown Department';
        }
    }
    return implode(', ', array_unique($displays));
}

?>
