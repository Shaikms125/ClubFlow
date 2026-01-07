<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['assigned_to']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'club_admin') && isset($_POST['due_date'])) {
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
	$assigned_to = validate_input($_POST['assigned_to']);
	$id = validate_input($_POST['id']);
	$due_date = validate_input($_POST['due_date']);

	if (empty($title)) {
		$em = "Title is required";
	    header("Location: ../edit-task.php?error=$em&id=$id");
	    exit();
	}else if (empty($description)) {
		$em = "Description is required";
	    header("Location: ../edit-task.php?error=$em&id=$id");
	    exit();
	}else if ($assigned_to == 0) {
		$em = "Select User";
	    header("Location: ../edit-task.php?error=$em&id=$id");
	    exit();
	}else {
    
       include "Model/Task.php";
       include "Model/Notification.php";

       // Update task details
       $data = array($title, $description, $due_date, $id);
       update_task($conn, $data);
       
       // Update assignments: Delete old, Insert new
       // Update assignments: Delete old, Insert new
       delete_task_assignments($conn, $id);
       
       if ($assigned_to != 0 && !empty($assigned_to)) {
           assign_task_to_user($conn, array($id, $assigned_to));
           // Optional: Send notification for update?
           $notif_data = array("Task '$title' has been updated/assigned to you.", $assigned_to, 'Task Updated');
           insert_notification($conn, $notif_data);
       }

       $em = "Task updated successfully";
	    header("Location: ../edit-task.php?success=$em&id=$id");
	    exit();

    
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../edit-task.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../index.php?action=login&error=$em");
   exit();
}
