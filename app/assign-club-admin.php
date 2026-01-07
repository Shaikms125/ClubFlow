<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "authority") {

    if (isset($_POST['user_id']) && isset($_POST['club_id'])) {
        include "../DB_connection.php";
        include "../inc/csrf_helper.php";
        verify_csrf_token();
        
        $user_id = $_POST['user_id'];
        $club_id = $_POST['club_id'];

        if (empty($user_id)) {
            $em = "User is required";
            header("Location: ../assign-club-admin.php?error=$em");
            exit();
        }else if (empty($club_id)) {
            $em = "Club is required";
            header("Location: ../assign-club-admin.php?error=$em");
            exit();
        }else {
            // Also update user role to club_admin? 
            // The requirement says "authority can add club assign someone to a clubadmin".
            // It implies changing their role context or giving them permissions.
            // I should update users table role as well if they are just 'club_member'.
            
            $sql = "UPDATE users SET role='club_admin' WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$user_id]);

            $sql = "INSERT INTO club_admins (user_id, club_id) VALUES (?,?)";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$user_id, $club_id]);

            if ($res) {
                $em = "Club Admin assigned successfully";
                header("Location: ../assign-club-admin.php?success=$em");
                exit();
            }else {
                $em = "Unknown error occurred";
                header("Location: ../assign-club-admin.php?error=$em");
                exit();
            }
        }
    }else {
        header("Location: ../assign-club-admin.php?error=error");
        exit();
    }

}else{ 
   $em = "First login";
   header("Location: ../index.php?action=login&error=$em");
   exit();
}
?>
