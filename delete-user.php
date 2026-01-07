<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "authority")) {
    include "DB_connection.php";
    include "app/Model/User.php";
    include "inc/csrf_helper.php";
    verify_csrf_token('GET');
    
    if (!isset($_GET['id'])) {
    	 header("Location: user.php");
    	 exit();
    }
     $id = (int)$_GET['id'];
     $user = get_user_by_id($conn, $id);
     if ($user == 0) {
         $em = "User not found";
         header("Location: user.php?error=$em");
         exit();
     }
     
    // Authorization Check
    
    // 1. Prevent Self-Deletion
    if ($id == $_SESSION['id']) {
        $em = "You cannot delete yourself";
        header("Location: user.php?error=$em");
        exit();
    }

    // 2. Protect Authority Users (No one can delete authority)
    if ($user['role'] == 'authority') {
         $em = "Access denied: Authority users cannot be deleted";
         header("Location: user.php?error=$em");
         exit();
    }
    
    // 3. Authority CAN delete Admin (implied allowed if not blocked above)
    // No specific block needed for Admin deletion if logged in user is Authority/Admin.


     $data = array($id);
     delete_user($conn, $data);
     $sm = "Deleted Successfully";
     header("Location: user.php?success=$sm");
     exit();

 }else{ 
   $em = "First login";
   header("Location: index.php?action=login&error=$em");
   exit();
}
 ?>
