<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['user_name']) && isset($_POST['password']) && isset($_POST['full_name']) && ($_SESSION['role'] == 'authority' || $_SESSION['role'] == 'club_admin')) {
	include "../DB_connection.php";
    include "../inc/csrf_helper.php";
    verify_csrf_token();

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$user_name = validate_input($_POST['user_name']);
	$password = validate_input($_POST['password']);
	$full_name = validate_input($_POST['full_name']);

	if (empty($user_name)) {
		$em = "User name is required";
	    header("Location: ../add-user.php?error=$em");
	    exit();
	}else if (empty($password)) {
		$em = "Password is required";
	    header("Location: ../add-user.php?error=$em");
	    exit();
	}else if (empty($full_name)) {
		$em = "Full name is required";
	    header("Location: ../add-user.php?error=$em");
	    exit();
	}else {
    
       include "Model/User.php";
       
       if(!is_username_unique($conn, $user_name)){
           $em = "Username '$user_name' is already taken";
           header("Location: ../add-user.php?error=$em");
           exit();
       }

       $password = password_hash($password, PASSWORD_DEFAULT);

       $data = array($full_name, $user_name, $password, "club_member");
       insert_user($conn, $data);
       
       // If creator is club_admin, add the new user to their club
       if($_SESSION['role'] == 'club_admin'){
           // We need to get the ID of the user we just created. 
           // Since insert_user doesn't return ID, valid assumption is selecting by username
           // Or we can modify Model/User.php but let's stick to this file for now safely.
           // Actually, let's fetch the user by username.
           $sql = "SELECT id FROM users WHERE username = ?";
           $stmt = $conn->prepare($sql);
           $stmt->execute([$user_name]);
           if($stmt->rowCount() > 0){
               $new_user = $stmt->fetch();
               $new_user_id = $new_user['id'];
               
               include "Model/Club.php";
               $club_id = get_club_id_by_admin($conn, $_SESSION['id']);
               
               if($club_id != 0){
                   $sql2 = "INSERT INTO club_members (user_id, club_id) VALUES (?, ?)";
                   $stmt2 = $conn->prepare($sql2);
                   $stmt2->execute([$new_user_id, $club_id]);
               }
           }
       }

       $em = "User created successfully";
       // Redirect back to club members if club admin
       if($_SESSION['role'] == 'club_admin'){
            header("Location: ../club-members.php?success=$em");
       }else{
            header("Location: ../add-user.php?success=$em");
       }
	    exit();
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../add-user.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../add-user.php?error=$em");
   exit();
}
