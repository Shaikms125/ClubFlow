<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    
    if ($_SESSION['role'] == 'club_admin' || $_SESSION['role'] == 'authority') {
        include "../DB_connection.php";
        include "../inc/csrf_helper.php";
        verify_csrf_token('GET');
        include "Model/Notice.php";

        if(isset($_GET['id'])){
            $id = $_GET['id'];
            
            // Get notice to check if exists and delete image
            $sql = "SELECT image FROM notices WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            
            if($stmt->rowCount() > 0){
                $notice = $stmt->fetch();
                
                // Delete image if exists
                if($notice['image'] && file_exists('../img/notices/'.$notice['image'])){
                    unlink('../img/notices/'.$notice['image']);
                }
                
                // Delete notice
                delete_notice($conn, $id);
                $em = "Notice deleted successfully";
                header("Location: ../notices.php?success=$em");
                exit;
            }else {
                $em = "Notice not found";
                header("Location: ../notices.php?error=$em");
                exit;
            }
        }else {
            $em = "Invalid request";
            header("Location: ../notices.php?error=$em");
            exit;
        }
    }else {
        $em = "Unauthorized access";
        header("Location: ../notices.php?error=$em");
        exit;
    }
}else{ 
   $em = "First login";
   header("Location: ../login.php?error=$em");
   exit;
}
?>
