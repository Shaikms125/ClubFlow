<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    
    if ($_SESSION['role'] == 'club_admin' || $_SESSION['role'] == 'authority') {
        include "../DB_connection.php";
        include "../inc/csrf_helper.php";
        verify_csrf_token();
        include "Model/Notice.php";
        include "Model/Club.php";

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $source = $_POST['source'] ?? '';
        
        // Basic Validation
        if (empty($title)) {
            $em = "Title is required";
            header("Location: ../notices.php?action=create&error=$em");
            exit;
        } else if (empty($description)) {
            $em = "Description is required";
            header("Location: ../notices.php?action=create&error=$em");
            exit;
        } else if (empty($source)) {
            $em = "Posting source is required";
            header("Location: ../notices.php?action=create&error=$em");
            exit;
        } else {
            $new_img_name = "";
            if (isset($_FILES['image']['name']) AND !empty($_FILES['image']['name'])) {
                $img_name = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $error = $_FILES['image']['error'];

                if($error === 0){
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_to_lc = strtolower($img_ex);
                    $allowed_exs = array('jpg', 'jpeg', 'png');

                    if (in_array($img_ex_to_lc, $allowed_exs)) {
                        if($_FILES['image']['size'] > 1048576){
                            $em = "Image size must be less than 1MB";
                            header("Location: ../notices.php?action=create&error=$em");
                            exit;
                        }

                        $img_info = @getimagesize($tmp_name);
                        if($img_info === false){
                            $em = "Invalid image file";
                            header("Location: ../notices.php?action=create&error=$em");
                            exit;
                        }

                        $width = $img_info[0];
                        $height = $img_info[1];
                        if($width > 1200 || $height > 800){
                            $em = "Image dimensions must be max 1200x800 pixels";
                            header("Location: ../notices.php?action=create&error=$em");
                            exit;
                        }

                        $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_to_lc;
                        $img_upload_path = '../img/notices/' . $new_img_name;
                        
                        if (!is_dir('../img/notices/')) {
                            mkdir('../img/notices/', 0755, true);
                        }
                        
                        move_uploaded_file($tmp_name, $img_upload_path);
                    } else {
                        $em = "Invalid image format";
                        header("Location: ../notices.php?action=create&error=$em");
                        exit;
                    }
                }
            }

            // Insert core notice data (title, description, image)
            $data = array($title, $description, $new_img_name);
            $notice_id = insert_notice($conn, $data);

            // Insert source
            if($source == 'authority'){
                add_notice_source($conn, $notice_id, 'authority');
            } else if(str_starts_with($source, 'club_')){
                $club_id = str_replace('club_', '', $source);
                add_notice_source($conn, $notice_id, 'club', $club_id);
            } else if(str_starts_with($source, 'dept_')){
                $dept_name = str_replace('dept_', '', $source);
                add_notice_source($conn, $notice_id, 'department', null, $dept_name);
            }

            header("Location: ../notices.php?success=Notice published successfully");
            exit;
        }
    } else {
        $em = "Unauthorized access";
        header("Location: ../notices.php?error=$em");
        exit;
    }
} else { 
   $em = "First login";
   header("Location: ../login.php?error=$em");
   exit;
}
?>
