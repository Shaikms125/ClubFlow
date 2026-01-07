<?php 
include "../inc/session_init.php";
init_session();
if (isset($_POST['user_name']) && isset($_POST['password'])) {
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
	$role = validate_input($_POST['role']);

	if (empty($user_name) || empty($password) || empty($role)) {
		$em = "All fields are required";
	    header("Location: ../index.php?error=$em&action=login");
	    exit();
	}else {
    
       $sql = "SELECT * FROM users WHERE username = ?";
       $stmt = $conn->prepare($sql);
       $stmt->execute([$user_name]);

       if ($stmt->rowCount() == 1) {
       	   $user = $stmt->fetch();
       	   $usernameDb = $user['username'];
       	   $passwordDb = $user['password'];
       	   $roleDb = $user['role'];
       	   $id = $user['id'];

           // Verify username, password AND role all match
       	   if ($user_name === $usernameDb && password_verify($password, $passwordDb) && $role === $roleDb) {
       	   		
				session_regenerate_id(true);
                $_SESSION['role'] = $roleDb;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $usernameDb;
                header("Location: ../dashboard.php");
                exit();

       	   }else {
       	   	   $em = "Information doesn't match";
			   header("Location: ../index.php?error=$em&action=login");
			   exit();
       	   }
       }else {
        $em = "Information doesn't match";
        header("Location: ../index.php?error=$em&action=login");
        exit();
       }
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../index.php?error=$em&action=login");
   exit();
}
