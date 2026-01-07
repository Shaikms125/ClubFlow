<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['user_name']) && isset($_POST['password']) && isset($_POST['full_name']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'authority')) {
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
	$id = validate_input($_POST['id']);


	if (empty($user_name)) {
		$em = "User name is required";
	    header("Location: ../edit-user.php?error=$em&id=$id");
	    exit();
	}else if (empty($full_name)) {
		$em = "Full name is required";
	    header("Location: ../edit-user.php?error=$em&id=$id");
	    exit();
	}else {
    
       include "Model/User.php";
       
       if(!is_username_unique($conn, $user_name, $id)){
           $em = "Username '$user_name' is already taken";
           header("Location: ../edit-user.php?error=$em&id=$id");
           exit();
       }

       // Get current user data
       $current_user = get_user_by_id($conn, $id);
       $role = $current_user['role'];
       
       if(empty($password)){
           $password = $current_user['password'];
       }else{
           $password = password_hash($password, PASSWORD_DEFAULT);
       } 
       
        $data = array($full_name, $user_name, $password, $role, $id);
        update_user($conn, $data);

       $em = "User updated successfully";
	    header("Location: ../edit-user.php?success=$em&id=$id");
	    exit();

    
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../edit-user.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../edit-user.php?error=$em");
   exit();
}
