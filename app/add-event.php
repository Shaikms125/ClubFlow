<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    
    if ($_SESSION['role'] == 'club_admin' || $_SESSION['role'] == 'authority') {
        include "../DB_connection.php";
        include "../inc/csrf_helper.php";
        verify_csrf_token();
        include "Model/Event.php";
        include "Model/Club.php";

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $date = $_POST['date'] ?? '';
        $place = $_POST['place'] ?? '';
        $organized_by = isset($_POST['organized_by']) ? $_POST['organized_by'] : array();
        
        // Basic Validation
        if (empty($title)) {
            $em = "Title is required";
            header("Location: ../create-event.php?error=$em");
            exit;
        }else if (empty($description)) {
            $em = "Description is required";
            header("Location: ../create-event.php?error=$em");
            exit;
        }else if (empty($date)) {
            $em = "Date is required";
            header("Location: ../create-event.php?error=$em");
            exit;
        }else if (empty($place)) {
            $em = "Place is required";
            header("Location: ../create-event.php?error=$em");
            exit;
        }
        
        $new_img_name = ""; // Default empty
        if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
            $img_name = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $error = $_FILES['image']['error'];

            if($error === 0){
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_to_lc = strtolower($img_ex);

                $allowed_exs = array('jpg', 'jpeg', 'png');

                if(in_array($img_ex_to_lc, $allowed_exs)){
                    // Check Size (1MB = 1048576 bytes)
                    if($_FILES['image']['size'] > 1048576){
                         $em = "Image size must be less than 1MB";
                         header("Location: ../create-event.php?error=$em");
                         exit;
                    }

                    // Check Dimensions
                    list($width, $height) = getimagesize($tmp_name);
                    if($width > 1000 || $height > 500){
                         $em = "Image dimensions must be max 1000x500 pixels";
                         header("Location: ../create-event.php?error=$em");
                         exit;
                    }

                    $new_img_name = uniqid("EVENT-", true).'.'.$img_ex_to_lc;
                    $img_upload_path = '../img/events/'.$new_img_name;
                    
                    // Create directory if not exists
                    if (!file_exists('../img/events/')) {
                        mkdir('../img/events/', 0755, true);
                    }

                    move_uploaded_file($tmp_name, $img_upload_path);

                }else {
                    $em = "You can't upload files of this type";
                    header("Location: ../create-event.php?error=$em");
                    exit;
                }
             }else {
                $em = "Unknown error occurred!";
                header("Location: ../create-event.php?error=$em");
                exit;
             }
        }

        // Insert event
        $data = [$title, $description, $new_img_name, $date, $place];
        $event_id = insert_event($conn, $data);

        // Add organizers
        if (!empty($organized_by)) {
            foreach ($organized_by as $org_type) {
                if ($org_type === 'authority') {
                    add_event_organizer($conn, $event_id, 'authority');
                } else if ($org_type === 'club') {
                    if (isset($_POST['organized_by_club_ids']) && is_array($_POST['organized_by_club_ids'])) {
                        // Both authority and club_admin can select multiple clubs
                        $club_ids = $_POST['organized_by_club_ids'];
                        foreach ($club_ids as $club_id) {
                            $club_id = (int)$club_id;
                            if ($club_id > 0) {
                                add_event_organizer($conn, $event_id, 'club', $club_id);
                            }
                        }
                    }
                } else if ($org_type === 'department') {
                    if (isset($_POST['organized_by_department_names'])) {
                        // Support multiple departments
                        $department_names = $_POST['organized_by_department_names'];
                        if (is_array($department_names)) {
                            foreach ($department_names as $dept_name) {
                                $dept_name = trim($dept_name);
                                if (!empty($dept_name)) {
                                    add_event_organizer($conn, $event_id, 'department', null, $dept_name);
                                }
                            }
                        } else if (!empty($department_names)) {
                            $department_names = trim($department_names);
                            add_event_organizer($conn, $event_id, 'department', null, $department_names);
                        }
                    }
                }
            }
        }

        $em = "Event created successfully";
        header("Location: ../events.php?success=$em"); 
        exit;
    } else {
        $em = "Permission denied";
        header("Location: ../create-event.php?error=$em");
        exit;
    }
} else {
    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit;
}
?>
