<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "authority") {

    if (isset($_POST['club_name'])) {
        include "../DB_connection.php";
        include "../inc/csrf_helper.php";
        verify_csrf_token();
        
        function validate_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $club_name = validate_input($_POST['club_name']);

        if (empty($club_name)) {
            $em = "Club name is required";
            header("Location: ../add-club.php?error=$em");
            exit();
        }else {
            $sql = "INSERT INTO clubs (name) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$club_name]);

            if ($res) {
                $em = "Club created successfully";
                header("Location: ../add-club.php?success=$em");
                exit();
            }else {
                $em = "Unknown error occurred";
                header("Location: ../add-club.php?error=$em");
                exit();
            }
        }
    }else {
        header("Location: ../add-club.php?error=error");
        exit();
    }

}else{ 
   $em = "First login";
   header("Location: ../index.php?action=login&error=$em");
   exit();
}
?>
