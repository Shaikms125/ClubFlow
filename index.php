<?php 
    session_start();
    include "DB_connection.php";
    include "app/Model/Event.php";
    include "app/Model/Notice.php";
    include "app/Model/Club.php";
    include "inc/csrf_helper.php";

    // Logic to determine active tab and fetch data accordingly
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'discover';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    // Set limit based on active tab
    if ($tab == 'notices') {
        $limit = 15;
    } elseif ($tab == 'events') {
        $limit = 9;
    } elseif ($tab == 'clubs') {
        $limit = 12;
    } else {
        $limit = 6;
    }
    
    $offset = ($page - 1) * $limit;

    // Initialize variables
    $notices = 0;
    $events = 0;
    $clubs = 0;
    $total_pages = 1;
    $total_notice_pages = 1;

    if ($tab == 'discover') {
        $notices = get_all_notices($conn, 6); // Limit 6 for discover
        $events = get_upcoming_events($conn, 6); // Limit 6 for discover (latest 6)
        $clubs = get_all_clubs($conn, 4); // Limit 4 for discover
    } elseif ($tab == 'notices') {
        $total_notices = count_all_notices($conn);
        $total_notice_pages = ceil($total_notices / $limit);
        $notices = get_all_notices_paginated($conn, $offset, $limit);
    } elseif ($tab == 'events') {
        $total_events = count_all_events($conn);
        $total_pages = ceil($total_events / $limit);
        $events = get_all_events_paginated($conn, $offset, $limit);
    } elseif ($tab == 'clubs') {
        $total_clubs = count_clubs($conn);
        $total_pages = ceil($total_clubs / $limit);
        $clubs = get_all_clubs_paginated($conn, $offset, $limit);
    }

    // Optimization: Batch fetch notice sources if notices exist
    $notice_sources_map = [];
    if($notices != 0 && (is_array($notices) || is_object($notices))){
        $notice_ids = array_column($notices, 'id');
        $notice_sources_map = get_sources_for_notices($conn, $notice_ids);
    }
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Welcome | EWU Events</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="public-page">
    <!-- Header -->
    <?php include "inc/homepage-header.php"; ?>

    <!-- Content -->
     <div class="container public-container">
        
        <!-- DISCOVER VIEW (ALL SECTIONS) -->
        <?php if ($tab == 'discover') { ?>
            
            <!-- NOTICES -->
            <div class="mb-6">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                     <div>
                        <h3 class="fw-bold text-danger m-0 section-header"><i class="fa fa-bullhorn me-2"></i> Important Notices</h3>
                        <div class="section-header-underline"></div>
                     </div>
                     <a href="?tab=notices" class="btn-secondary">View All</a>
                </div>
                <?php if ($notices != 0) { ?>
                <div class="mobile-horizontal-view">
                    <div class="row g-4 mt-3">
                        <?php foreach ($notices as $notice) { 
                            $sources = isset($notice_sources_map[$notice['id']]) ? $notice_sources_map[$notice['id']] : 0;
                            $source_display = get_source_display($sources);
                        ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="view-notice.php?id=<?=$notice['id']?>" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 notice-card" style="transition: transform 0.2s;">
                                    <div class="card-body p-4 d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <span class="badge rounded-pill bg-danger-subtle text-danger px-3">Notice</span>
                                            <small class="text-muted"><i class="fa fa-calendar"></i> <?=date("M d, Y", strtotime($notice['created_at']))?></small>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-0" style="line-height: 1.5; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;" title="<?=htmlspecialchars($notice['title'])?>">
                                            <?=htmlspecialchars($notice['title'])?>
                                        </h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <hr class="mt-5">
                <?php } else { echo '<p class="text-muted">No recent notices.</p>'; } ?>
            </div>

            <!-- EVENTS -->
            <div class="mb-6">
                 <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-dark m-0 section-header"><i class="fa fa-calendar-check-o me-2"></i> Upcoming Events</h3>
                        <div class="section-header-underline"></div>
                    </div>
                    <a href="?tab=events" class="btn-secondary">View All</a>
                </div>
                <?php if ($events != 0) { ?>
                <div class="mobile-horizontal-view">
                    <div class="row g-4 mt-3">
                        <?php foreach ($events as $event) { 
                            $has_image = !empty($event['image']);
                        ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="view-event.php?id=<?=$event['id']?>" class="text-decoration-none text-dark">
                                <div class="card-event-modern">
                                    <!-- Image Wrapper / Header Area -->
                                    <div class="card-event-img-wrapper <?=$has_image ? '' : 'no-image'?>">
                                        <?php if($has_image) { ?>
                                            <img src="img/events/<?=$event['image']?>" alt="<?=$event['title']?>" loading="lazy">
                                        <?php } ?>
                                        
                                        <!-- Date Badge (Top Left) -->
                                        <div class="event-date-badge">
                                            <span class="event-date-month"><?=date("M", strtotime($event['date']))?></span>
                                            <span class="event-date-day"><?=date("d", strtotime($event['date']))?></span>
                                            <span class="event-date-year"><?=date("Y", strtotime($event['date']))?></span>
                                        </div>

                                        <!-- Title Overlay (Bottom Left) -->
                                        <div class="event-title-overlay">
                                            <h5><?=strlen($event['title']) > 63 ? htmlspecialchars(substr($event['title'],0,63)).'...' : htmlspecialchars($event['title'])?></h5>
                                        </div>
                                    </div>
                                    
                                    <!-- Description Body -->
                                    <div class="card-event-body">
                                         <div class="d-flex justify-content-between align-items-center mb-2">
                                             <!-- Replaced Club Name with Event Tag -->
                                             <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                                Event
                                            </span>
                                        </div>
                                        <p class="text-secondary small mb-3 flex-grow-1 event-description">
                                            <?=substr(strip_tags($event['description']), 0, 160)?>...
                                        </p>
                                        

                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <hr class="mt-5">
                <?php } else { echo '<p class="text-muted">No upcoming events.</p>'; } ?>
            </div>

            <!-- CLUBS -->
             <div class="mb-5">
                 <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-dark m-0 section-header"><i class="fa fa-users me-2"></i> Explore Clubs</h3>
                        <div class="section-header-underline"></div>
                    </div>
                    <a href="?tab=clubs" class="btn-secondary">View All</a>
                </div>
                <?php if ($clubs != 0) { ?>
                <div class="mobile-horizontal-view">
                    <div class="row g-4 mt-3">
                         <?php foreach ($clubs as $club) { ?>
                        <div class="col-6 col-sm-4 col-md-3">
                            <a href="view-club.php?id=<?=$club['id']?>" class="text-decoration-none">
                                <div class="card-premium text-center p-4">
                                    <div class="mb-3">
                                        <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 80px; height: 80px;">
                                            <i class="fa fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-3"><?=htmlspecialchars($club['name'])?></h5>
                                    <div class="mt-2">
                                        <span class="btn btn-sm btn-outline-secondary rounded-pill">View Club</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <hr class="mt-4">
                <?php } else { echo '<p class="text-muted">No clubs found.</p>'; } ?>
            </div>
            
        <?php } elseif ($tab == 'notices') { ?>
            <!-- NOTICES ONLY TAB (Paginated) -->
             <h2 class="mb-4 text-danger"><i class="fa fa-bullhorn me-2"></i> All Notices</h2>
             <?php if ($notices != 0) { ?>
             <div class="row g-4">
                <?php foreach ($notices as $notice) { 
                    $sources = isset($notice_sources_map[$notice['id']]) ? $notice_sources_map[$notice['id']] : 0;
                    $source_display = get_source_display($sources);
                ?>
                <div class="col-12 col-md-6 col-lg-4">
                     <a href="view-notice.php?id=<?=$notice['id']?>" class="text-decoration-none">
                         <div class="card border-0 shadow-sm h-100 notice-card" style="transition: transform 0.2s;">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge rounded-pill bg-danger-subtle text-danger px-3">Notice</span>
                                    <small class="text-muted"><i class="fa fa-calendar"></i> <?=date("M d, Y", strtotime($notice['created_at']))?></small>
                                </div>
                                <h5 class="fw-bold text-dark mb-0" style="line-height: 1.5; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;" title="<?=htmlspecialchars($notice['title'])?>">
                                    <?=htmlspecialchars($notice['title'])?>
                                </h5>
                            </div>
                         </div>
                     </a>
                </div>
                <?php } ?>
             </div>
             <!-- Pagination -->
             <?php if ($total_notice_pages > 1) { ?>
             <div class="col-12 mt-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-premium justify-content-center">
                        <?php if($page > 1) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?tab=notices&page=<?=$page-1?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php } ?>
                        
                        <?php for($i=1; $i<=$total_notice_pages; $i++) { ?>
                            <li class="page-item <?=$i==$page?'active':''?>"><a class="page-link" href="?tab=notices&page=<?=$i?>"><?=$i?></a></li>
                        <?php } ?>
                        
                        <?php if($page < $total_notice_pages) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?tab=notices&page=<?=$page+1?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
             </div>
             <?php } ?>
             <?php } else { echo '<p>No notices found.</p>'; } ?>

        <?php } elseif ($tab == 'events') { ?>
            <!-- EVENTS ONLY TAB (Paginated) -->
            <h2 class="mb-4 text-dark"><i class="fa fa-calendar-check-o me-2"></i> All Events</h2>
             <?php if ($events != 0) { ?>
              <div class="row g-4">
                    <?php foreach ($events as $event) { 
                        $has_image = !empty($event['image']);
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="view-event.php?id=<?=$event['id']?>" class="text-decoration-none text-dark">
                            <div class="card-event-modern">
                                <!-- Image Wrapper / Header Area -->
                                <div class="card-event-img-wrapper <?=$has_image ? '' : 'no-image'?>">
                                    <?php if($has_image) { ?>
                                        <img src="img/events/<?=$event['image']?>" alt="<?=$event['title']?>" loading="lazy">
                                    <?php } ?>
                                    
                                    <!-- Date Badge (Top Left) -->
                                    <div class="event-date-badge">
                                        <span class="event-date-month"><?=date("M", strtotime($event['date']))?></span>
                                        <span class="event-date-day"><?=date("d", strtotime($event['date']))?></span>
                                        <span class="event-date-year"><?=date("Y", strtotime($event['date']))?></span>
                                    </div>

                                    <!-- Title Overlay (Bottom Left) -->
                                    <div class="event-title-overlay">
                                        <h5><?=strlen($event['title']) > 63 ? htmlspecialchars(substr($event['title'],0,63)).'...' : htmlspecialchars($event['title'])?></h5>
                                    </div>
                                </div>
                                
                                <!-- Description Body -->
                                <div class="card-event-body">
                                     <div class="d-flex justify-content-between align-items-center mb-2">
                                         <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                            Event
                                        </span>
                                    </div>
                                    <p class="text-secondary small mb-3 flex-grow-1 event-description">
                                        <?=substr(strip_tags($event['description']), 0, 160)?>...
                                    </p>
                                    

                                </div>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                </div>
                <!-- Pagination -->
                 <div class="col-12 mt-5">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-premium justify-content-center">
                            <?php if($page > 1) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="?tab=events&page=<?=$page-1?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php } ?>
                            
                            <?php for($i=1; $i<=$total_pages; $i++) { ?>
                                <li class="page-item <?=$i==$page?'active':''?>"><a class="page-link" href="?tab=events&page=<?=$i?>"><?=$i?></a></li>
                            <?php } ?>
                            
                            <?php if($page < $total_pages) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="?tab=events&page=<?=$page+1?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                 </div>
             <?php } else { echo '<p class="text-muted">No events found.</p>'; } ?>
            
        <?php } elseif ($tab == 'clubs') { ?>
            <!-- CLUBS ONLY TAB -->
            <h2 class="mb-4 text-dark"><i class="fa fa-users me-2"></i> All Clubs</h2>
            <?php if ($clubs != 0) { ?>
            <div class="row g-4">
                 <?php foreach ($clubs as $club) { ?>
                <div class="col-md-3">
                    <a href="view-club.php?id=<?=$club['id']?>" class="text-decoration-none">
                        <div class="card-premium text-center p-4">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 80px; height: 80px;">
                                    <i class="fa fa-users fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark"><?=htmlspecialchars($club['name'])?></h5>
                            <div class="mt-3">
                                <span class="btn btn-sm btn-outline-secondary rounded-pill">View Club</span>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>
            </div>
            <!-- Pagination -->
             <?php if ($total_pages > 1) { ?>
             <div class="col-12 mt-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-premium justify-content-center">
                        <?php if($page > 1) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?tab=clubs&page=<?=$page-1?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php } ?>
                        
                        <?php for($i=1; $i<=$total_pages; $i++) { ?>
                            <li class="page-item <?=$i==$page?'active':''?>"><a class="page-link" href="?tab=clubs&page=<?=$i?>"><?=$i?></a></li>
                        <?php } ?>
                        
                        <?php if($page < $total_pages) { ?>
                            <li class="page-item">
                                <a class="page-link" href="?tab=clubs&page=<?=$page+1?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
             </div>
             <?php } ?>
            <?php } else { echo '<p class="text-muted">No clubs found.</p>'; } ?>
        <?php } ?>

     </div>

    <?php include "inc/login-modal.php"; ?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/mobile-scroll.js"></script>
</body>
</html>
