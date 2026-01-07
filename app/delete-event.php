<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    
    if ($_SESSION['role'] == 'club_admin' || $_SESSION['role'] == 'authority') {
        include "../DB_connection.php";
        include "../inc/csrf_helper.php";
        verify_csrf_token('GET');
        include "Model/Event.php";

        $id = $_GET['id'];

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
            $em = "Access denied: You don't have permission to delete this event.";
            header("Location: ../events.php?error=".urlencode($em));
            exit;
        }
        
        $current_event = get_event_by_id($conn, $id);
        if($current_event){
            // Delete Image
            $old_image = '../img/events/'.$current_event['image'];
            if(file_exists($old_image)){
                unlink($old_image);
            }
            // Delete Record
            delete_event($conn, $id);
            
            $em = "Event deleted successfully";
            header("Location: ../events.php?success=$em");
            exit;
        }else {
            $em = "Event not found";
            header("Location: ../events.php?error=$em");
            exit;
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
