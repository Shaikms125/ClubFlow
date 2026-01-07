<?php 
session_start();
include "DB_connection.php";
include "app/Model/Club.php";
include "app/Model/Event.php";
include "inc/csrf_helper.php";


if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$club = get_club_by_id($conn, $id);

if($club == 0){
    header("Location: index.php");
    exit();
}

// Get Club Stats
$members = get_club_members($conn, $id);
$member_count = $members ? count($members) : 0;

// Get Club Events
$club_events = get_events_by_club($conn, $id);
$tab = 'clubs'; // Set active tab for unified header
?>
<!DOCTYPE html>
<html>
<head>
	<title><?=$club['name']?> | EWU Events</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
</head>
<body class="public-page">
    <?php include "inc/homepage-header.php"; ?>

    <div class="header-bg" style="background: linear-gradient(135deg, var(--sidebar-bg) 0%, var(--primary-color) 100%); padding: 60px 0 40px; color: white;">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3"><?=htmlspecialchars($club['name'])?></h1>
            <div class="d-flex justify-content-center gap-4">

                <div class="badge bg-white text-dark fs-6 px-3 py-2 rounded-pill shadow-sm">
                    <i class="fa fa-calendar text-primary"></i> <?= $club_events ? count($club_events) : 0 ?> Events
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: -30px;">
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
                    <h3 class="mb-4 text-secondary border-bottom pb-2">Latest Events</h3>
                    
                    <div class="row g-4">
                         <?php 
                        if ($club_events != 0) { 
                            foreach ($club_events as $event) { ?>
                            <div class="col-md-4">
                                <a href="view-event.php?id=<?=$event['id']?>" class="text-decoration-none">
                                    <div class="task-card h-100 p-0 overflow-hidden border-0 shadow-sm hover-lift">
                                        <?php if(!empty($event['image'])) { ?>
                                            <img src="img/events/<?=$event['image']?>" class="img-fluid" style="width: 100%; height: 200px; object-fit: cover;">
                                        <?php } else { ?>
                                            <div class="d-flex align-items-center justify-content-center bg-light" style="width: 100%; height: 200px;">
                                                <i class="fa fa-picture-o fa-3x text-muted"></i>
                                            </div>
                                        <?php } ?>
                                        <div class="p-4">
                                            <h5 class="fw-bold text-dark mb-2"><?=htmlspecialchars($event['title'])?></h5>
                                            <p class="text-secondary small mb-3">
                                                <?=substr(strip_tags($event['description']), 0, 80)?>...
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted"><i class="fa fa-clock-o"></i> <?=date("M d, Y", strtotime($event['created_at']))?></small>
                                                <span class="btn btn-sm btn-outline-primary rounded-pill">View</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } } else { ?>
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="fa fa-calendar-times-o fa-3x mb-3"></i>
                                <p>No events found for this club.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "inc/login-modal.php"; ?>
</body>
</html>
