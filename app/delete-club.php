<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "authority") {
    include "../DB_connection.php";
    include "../app/Model/Club.php";
    include "../inc/csrf_helper.php";
    verify_csrf_token('GET');

    if (!isset($_GET['id'])) {
    	 header("Location: ../clubs.php");
    	 exit();
    }
    $id = $_GET['id'];
    $club = get_club_by_id($conn, $id);

    if ($club == 0) {
    	 header("Location: ../clubs.php");
    	 exit();
    }
    
    // 1. Reset Admin Roles to Member
    // Find admins for this club
    $sql_admins = "SELECT user_id FROM club_admins WHERE club_id=?";
    $stmt = $conn->prepare($sql_admins);
    $stmt->execute([$id]);
    
    if($stmt->rowCount() > 0){
        $admins = $stmt->fetchAll();
        foreach($admins as $admin){
            // Downgrade role to club_member
            $sql_update = "UPDATE users SET role='club_member' WHERE id=?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->execute([$admin['user_id']]);
        }
    }
    
    // 2. Remove Admin Association (Auto-deleted by FK? User said "removed from clubadmins table")
    // If Foreign Key is set to CASCADE (which it usually is in my new schema), this happens automatically.
    // If NOT set to CASCADE, we must delete manually.
    // Explicit delete is safer.
    $sql_delete_admins = "DELETE FROM club_admins WHERE club_id=?";
    $stmt_del_admins = $conn->prepare($sql_delete_admins);
    $stmt_del_admins->execute([$id]);
    
    // 3. Remove/Detach Members
    // Delete from club_members table (users table doesn't have club_id column)
    $sql_delete_members = "DELETE FROM club_members WHERE club_id=?";
    $stmt_del_members = $conn->prepare($sql_delete_members);
    $stmt_del_members->execute([$id]);

    // 4. Delete Club (and Tasks via Cascade or Manual)
    // "when a club is deleted any task assossiated with that club is deleted"
    // My previous step added ON DELETE CASCADE to tasks table for club_id. 
    // So deleting the club will auto-delete the tasks.
    // If the FK failed, we should manually delete tasks just in case? 
    // Let's do manual delete to be 100% sure as per user request.
    $sql_del_tasks = "DELETE FROM tasks WHERE club_id=?";
    $stmt_del_tasks = $conn->prepare($sql_del_tasks);
    $stmt_del_tasks->execute([$id]);

    $sql_delete_club = "DELETE FROM clubs WHERE id=?";
    $stmt_club = $conn->prepare($sql_delete_club);
    $stmt_club->execute([$id]);
    
    $sm = "Club deleted successfully";
    header("Location: ../clubs.php?success=$sm");
    exit();

 }else{ 
   $em = "Access Denied";
   header("Location: ../clubs.php?error=$em");
   exit();
}
?>
