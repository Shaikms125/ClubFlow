<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    
    if ($_SESSION['role'] == 'club_admin' || $_SESSION['role'] == 'authority') {
        include "../DB_connection.php";
        include "../inc/csrf_helper.php";
        verify_csrf_token();
        include "Model/Event.php";

        $id = $_POST['id'];

        // Authorization Check
        $can_manage = false;
        if($_SESSION['role'] == 'authority') $can_manage = true;
        else if($_SESSION['role'] == 'club_admin') {
            include_once "Model/Club.php";
            $admin_club_id = get_club_id_by_admin($conn, $_SESSION['id']);
            $organizers = get_event_organizers($conn, $id);
            if($organizers){
                foreach($organizers as $org){
                    if($org['organizer_type'] == 'club' && $org['club_id'] == $admin_club_id){
                        $can_manage = true;
                        break;
                    }
                }
            }
        }

        if(!$can_manage){
            $em = "Access denied: You don't have permission to update this event.";
            header("Location: ../events.php?error=".urlencode($em));
            exit;
        }

        $title = $_POST['title'];
        $description = $_POST['description'];
        $date = $_POST['date'];
        $place = $_POST['place'];
        
        // Basic Validation
        if (empty($title)) {
            $em = "Title is required";
            header("Location: ../edit-event.php?id=$id&error=$em");
            exit;
        }else if (empty($description)) {
            $em = "Description is required";
            header("Location: ../edit-event.php?id=$id&error=$em");
            exit;
        }else if (empty($place)) {
            $em = "Place is required";
            header("Location: ../edit-event.php?id=$id&error=$em");
            exit;
        }else {
            
            // Check if image is uploaded
            if (isset($_FILES['image']['name']) AND !empty($_FILES['image']['name'])) {
                 $img_name = $_FILES['image']['name'];
                 $tmp_name = $_FILES['image']['tmp_name'];
                 $error = $_FILES['image']['error'];

                 if($error === 0){
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_to_lc = strtolower($img_ex);

                    $allowed_exs = array('jpg', 'jpeg', 'png');

                    if(in_array($img_ex_to_lc, $allowed_exs)){
                         // Check Size (1MB)
                        if($_FILES['image']['size'] > 1048576){
                             $em = "Image size must be less than 1MB";
                             header("Location: ../edit-event.php?id=$id&error=$em");
                             exit;
                        }

                        // Check Dimensions
                        list($width, $height) = getimagesize($tmp_name);
                        if($width > 1000 || $height > 500){
                             $em = "Image dimensions must be max 1000x500 pixels";
                             header("Location: ../edit-event.php?id=$id&error=$em");
                             exit;
                        }

                        $new_img_name = uniqid("EVENT-", true).'.'.$img_ex_to_lc;
                        $img_upload_path = '../img/events/'.$new_img_name;
                        
                        // Delete old image
                        $current_event = get_event_by_id($conn, $id);
                        if($current_event){
                            $old_image = '../img/events/'.$current_event['image'];
                            if(file_exists($old_image)){
                                unlink($old_image);
                            }
                        }

					if (!is_dir('../img/events/')) {
						mkdir('../img/events/', 0755, true);
					}

                        move_uploaded_file($tmp_name, $img_upload_path);

                        // Update with Image
                        $data = [$title, $description, $new_img_name, $date, $place, $id];
                        update_event($conn, $data);

                        // Update Organizers
                        delete_event_organizers($conn, $id);
                        $organized_by = isset($_POST['organized_by']) ? $_POST['organized_by'] : array();
                        
                        if (!empty($organized_by)) {
                            foreach ($organized_by as $org_type) {
                                if ($org_type === 'authority') {
                                    add_event_organizer($conn, $id, 'authority');
                                } else if ($org_type === 'club') {
                                    if (isset($_POST['organized_by_club_ids']) && is_array($_POST['organized_by_club_ids'])) {
                                        $club_ids = $_POST['organized_by_club_ids'];
                                        foreach ($club_ids as $club_id) {
                                            $club_id = (int)$club_id;
                                            if ($club_id > 0) {
                                                add_event_organizer($conn, $id, 'club', $club_id);
                                            }
                                        }
                                    }
                                } else if ($org_type === 'department') {
                                    if (isset($_POST['organized_by_department_names'])) {
                                        $department_names = $_POST['organized_by_department_names'];
                                        if (is_array($department_names)) {
                                            foreach ($department_names as $dept_name) {
                                                $dept_name = trim($dept_name);
                                                if (!empty($dept_name)) {
                                                    add_event_organizer($conn, $id, 'department', null, $dept_name);
                                                }
                                            }
                                        } else if (!empty($department_names)) {
                                            $department_names = trim($department_names);
                                            add_event_organizer($conn, $id, 'department', null, $department_names);
                                        }
                                    }
                                }
                            }
                        }

                        $em = "Event updated successfully";
                        header("Location: ../events.php?success=$em");
                        exit;

                    }else {
                        $em = "You can't upload files of this type";
                        header("Location: ../edit-event.php?id=$id&error=$em");
                        exit;
                    }
                 }else {
                    $em = "Unknown error occurred!";
                    header("Location: ../edit-event.php?id=$id&error=$em");
                    exit;
                 }
            }else {
                // Update without Image
                $data = [$title, $description, $date, $place, $id];
                update_event_no_img($conn, $data);
                
                // Update Organizers
                delete_event_organizers($conn, $id);
                $organized_by = isset($_POST['organized_by']) ? $_POST['organized_by'] : array();
                
                if (!empty($organized_by)) {
                    foreach ($organized_by as $org_type) {
                        if ($org_type === 'authority') {
                            add_event_organizer($conn, $id, 'authority');
                        } else if ($org_type === 'club') {
                            if (isset($_POST['organized_by_club_ids']) && is_array($_POST['organized_by_club_ids'])) {
                                $club_ids = $_POST['organized_by_club_ids'];
                                foreach ($club_ids as $club_id) {
                                    $club_id = (int)$club_id;
                                    if ($club_id > 0) {
                                        add_event_organizer($conn, $id, 'club', $club_id);
                                    }
                                }
                            }
                        } else if ($org_type === 'department') {
                            if (isset($_POST['organized_by_department_names'])) {
                                $department_names = $_POST['organized_by_department_names'];
                                if (is_array($department_names)) {
                                    foreach ($department_names as $dept_name) {
                                        $dept_name = trim($dept_name);
                                        if (!empty($dept_name)) {
                                            add_event_organizer($conn, $id, 'department', null, $dept_name);
                                        }
                                    }
                                } else if (!empty($department_names)) {
                                    $department_names = trim($department_names);
                                    add_event_organizer($conn, $id, 'department', null, $department_names);
                                }
                            }
                        }
                    }
                }

                $em = "Event updated successfully";
                header("Location: ../events.php?success=$em");
                exit;
            }
        }
    }else {
      $em = "Permission denied";
      header("Location: ../login.php?error=$em");
      exit;
    }

}else {
  $em = "First login";
  header("Location: ../login.php?error=$em");
  exit;
}
?>
