<?php 
session_start();
include "DB_connection.php";
include "app/Model/Event.php";
include "inc/csrf_helper.php";


if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$event = get_event_by_id($conn, $id);

if($event == 0){
    header("Location: index.php");
    exit();
}

// Get organizers
$organizers = get_event_organizers($conn, $id);
$organizers_display = get_organizers_display($organizers);
$has_image = !empty($event['image']);
$tab = 'events'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=htmlspecialchars($event['title'])?> | EWU Events</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
    <style>
        .event-view-wrapper {
            max-width: 850px;
            margin: 5px auto 60px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.05);
            position: relative;
        }

        /* Image Handling */
        .event-main-img-container {
            width: 100%;
            height: 450px;
            overflow: hidden;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            background: #f1f5f9;
        }

        .event-main-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Content Container */
        .event-content-container {
            position: relative;
            padding: 60px 40px 40px;
        }

        /* Date Badge - Vertical Center Overlap */
        .event-date-badge-vertical {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 15px 25px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10;
            min-width: 110px;
            border: 1px solid rgba(0,0,0,0.03);
            line-height: 1;
        }

        .ev-month {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--primary-color);
            margin-bottom: 6px;
            letter-spacing: 1px;
        }

        .ev-day {
            font-size: 38px;
            font-weight: 900;
            color: #1e293b;
        }

        .ev-year {
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            margin-top: 6px;
        }

        /* Header Info */
        .event-view-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .event-view-title {
            font-size: 42px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
            line-height: 1.1;
        }

        .event-view-address {
            font-size: 18px;
            color: #64748b;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .event-view-address i {
            color: var(--primary-color);
        }

        /* Description Body */
        .event-view-body {
            font-size: 18px;
            line-height: 1.8;
            color: #334155;
            margin-bottom: 50px;
        }

        /* Organizers Box */
        .event-view-organizers {
            background: #f8fafc;
            padding: 30px;
            border-radius: 16px;
            border-left: 6px solid var(--primary-color);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .org-label {
            font-size: 13px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .org-value {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Actions/Footer */
        .event-view-actions {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: center;
        }

        /* Responsive Scaling */
        @media (max-width: 768px) {
            .event-view-wrapper { margin: 20px 15px 40px; }
            .event-main-img-container { height: 300px; }
            .event-content-container { padding: 50px 25px 30px; }
            .event-view-title { font-size: 30px; }
            .event-view-address { font-size: 16px; }
            .event-view-body { font-size: 16px; }
            .event-date-badge-vertical { padding: 12px 20px; min-width: 90px; }
            .ev-day { font-size: 28px; }
        }

        <?php if(!$has_image): ?>
        .event-view-wrapper {
            margin-top: 60px;
        }
        .event-content-container {
            padding-top: 80px;
            border-radius: 20px;
        }
        <?php endif; ?>
    </style>
</head>
<body class="public-page">
    
    <?php include "inc/homepage-header.php"; ?>

    <div class="container public-container">
        
        <div class="event-view-wrapper">
            
            <?php if($has_image): ?>
            <div class="event-main-img-container">
                <img src="img/events/<?=$event['image']?>" alt="<?=htmlspecialchars($event['title'])?>">
            </div>
            <?php endif; ?>

            <div class="event-content-container">
                
                <!-- Center Overlapping Date Badge -->
                <div class="event-date-badge-vertical">
                    <span class="ev-month"><?=date('M', strtotime($event['date'] ?? $event['created_at']))?></span>
                    <span class="ev-day"><?=date('d', strtotime($event['date'] ?? $event['created_at']))?></span>
                    <span class="ev-year"><?=date('Y', strtotime($event['date'] ?? $event['created_at']))?></span>
                </div>

                <!-- Strategic Header -->
                <div class="event-view-header">
                    <h1 class="event-view-title"><?=htmlspecialchars($event['title'])?></h1>
                    
                    <?php if(!empty($event['place'])): ?>
                    <div class="event-view-address">
                        <i class="fa fa-map-marker"></i>
                        <span><?=htmlspecialchars($event['place'])?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Body Content -->
                <div class="event-view-body">
                    <?=nl2br(htmlspecialchars($event['description']))?>
                </div>

                <!-- Organized By Block -->
                <div class="event-view-organizers">
                    <div class="org-label">
                         Organized By
                    </div>
                    <div class="org-value">
                        <?=$organizers_display?>
                    </div>
                </div>

                <!-- Admin Action Button -->
                <?php 
                    $can_manage = false;
                    if(isset($_SESSION['role'])){
                        if($_SESSION['role'] == 'authority') $can_manage = true;
                        else if($_SESSION['role'] == 'club_admin'){
                            include_once "app/Model/Club.php";
                            $admin_club_id = get_club_id_by_admin($conn, $_SESSION['id']);
                            if($organizers){
                                foreach($organizers as $org){
                                    if($org['organizer_type'] == 'club' && $org['club_id'] == $admin_club_id){
                                        $can_manage = true; break;
                                    }
                                }
                            }
                        }
                    }
                    if($can_manage): 
                ?>
                <div class="event-view-actions">
                    <a href="edit-event.php?id=<?=$id?>" class="btn btn-primary btn-lg shadow-sm px-5 rounded-pill">
                        <i class="fa fa-pencil me-2"></i> Edit This Event
                    </a>
                </div>
                <?php endif; ?>

            </div>
        </div>

    </div>

    <?php
        if(file_exists("login-model.php")) {
            include "login-model.php";
        } else {
            include "inc/login-modal.php";
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
