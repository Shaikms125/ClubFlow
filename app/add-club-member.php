<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "authority" || $_SESSION['role'] == "club_admin")) {

    if (isset($_POST['user_id']) && isset($_POST['club_id'])) {
        include "../DB_connection.php";
        include "../inc/csrf_helper.php";
        verify_csrf_token();
        
        $user_id = $_POST['user_id'];
        $club_id = $_POST['club_id'];

        // Security Check for Club Admin
        if($_SESSION['role'] == 'club_admin'){
             include "Model/Club.php";
             $admin_club_id = get_club_id_by_admin($conn, $_SESSION['id']);
             if($club_id != $admin_club_id){
                 $em = "Access denied: You can only add members to your your own club";
                 header("Location: ../add-club-member.php?error=$em");
                 exit();
             }
        }

        if (empty($user_id)) {
            $em = "User is required";
            header("Location: ../add-club-member.php?error=$em");
            exit();
        }else if (empty($club_id)) {
            $em = "Club is required";
            header("Location: ../add-club-member.php?error=$em");
            exit();
        }else {
            $sql = "INSERT INTO club_members (user_id, club_id) VALUES (?,?)";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$user_id, $club_id]);

            if ($res) {
                $em = "Member added successfully";
                header("Location: ../add-club-member.php?success=$em");
                exit();
            }else {
                $em = "Unknown error occurred";
                header("Location: ../add-club-member.php?error=$em");
                exit();
            }
        }
    }else {
        header("Location: ../add-club-member.php?error=error");
        exit();
    }

}else{ 
   $em = "First login";
   header("Location: ../index.php?action=login&error=$em");
   exit();
}
?>
