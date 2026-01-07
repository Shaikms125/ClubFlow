<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['assigned_to']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'club_admin') && isset($_POST['due_date'])) {
	include "../DB_connection.php";
	include "../inc/csrf_helper.php";
	verify_csrf_token();

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$title = validate_input($_POST['title']);
	$description = validate_input($_POST['description']);
	$assigned_to = $_POST['assigned_to'];
	$due_date = validate_input($_POST['due_date']);

	if (empty($title)) {
		$em = "Title is required";
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}else if (empty($description)) {
		$em = "Description is required";
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}else if ($assigned_to == 0) {
		$em = "Select User";
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}else {
    
       include "Model/Task.php";
       include "Model/Notification.php";

       $club_id = 0;
       if($_SESSION['role'] == 'club_admin'){
           include "Model/Club.php";
           $club_id = get_club_id_by_admin($conn, $_SESSION['id']);
       }

       $data = array($title, $description, $due_date, $club_id);
       $task_id = insert_task($conn, $data);
       
       assign_task_to_user($conn, array($task_id, $assigned_to));
       
       $notif_data = array("'$title' has been assigned to you. Please review and start working on it", $assigned_to, 'New Task Assigned');
       insert_notification($conn, $notif_data);


       $em = "Task created successfully";
	    header("Location: ../create_task.php?success=$em");
	    exit();

    
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../create_task.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../create_task.php?error=$em");
   exit();
}
