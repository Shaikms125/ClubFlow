<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/Event.php";
    include "app/Model/Club.php";
    include "inc/csrf_helper.php";

    $events = [];
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    // Pagination logic
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;
    $total_events = 0;

    if($_SESSION['role'] == 'authority' || $_SESSION['role'] == 'club_admin'){
        if (!empty($search)) {
            $total_events = count_search_events($conn, $search);
            $events = search_events_paginated($conn, $search, $offset, $limit);
        } else {
            $total_events = count_all_events($conn);
            $events = get_all_events_paginated($conn, $offset, $limit);
        }
        
        if($_SESSION['role'] == 'club_admin'){
            $admin_club_id = get_club_id_by_admin($conn, $_SESSION['id']);
        }
    }else {
        if (!empty($search)) {
            $total_events = count_search_events($conn, $search);
            $events = search_events_paginated($conn, $search, $offset, $limit);
        } else {
            $total_events = count_all_events($conn);
            $events = get_all_events_paginated($conn, $offset, $limit);
        }
    }
    $total_pages = ceil($total_events / $limit);
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Events</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<div class="main-container">
			<?php include "inc/header.php" ?>
			<div class="content">
				<div class="section-title">
                    <div class="title-text">
                        <i class="fa fa-calendar"></i> Events
                    </div>
                    <div class="search-box mb-4">
                    <form action="events.php" method="GET" style="display: flex; gap: 5px;">
                        <input type="text" name="search" value="<?=htmlspecialchars($search)?>" placeholder="Search events by title..." class="input-1" style="padding: 8px 15px; min-width: 500px; background: #e9eaffff;">
                        <button type="submit" class="btn btn-primary" style="padding: 8px 15px;">
                            <i class="fa fa-search"></i>
                        </button>
                        <?php if(!empty($search)): ?>
                            <a href="events.php" class="btn btn-secondary" style="padding: 8px 15px;" title="Clear Search">
                                <i class="fa fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
                    <?php if($_SESSION['role'] == 'club_admin' || $_SESSION['role'] == 'authority') { ?>
                        <a href="create-event.php" class="btn btn-primary"><i class="fa fa-plus"></i> Create Event</a>
                    <?php } ?>
                </div>

                

                <?php if (isset($_GET['success'])) { ?>
                    <div class="success"><i class="fa fa-check-circle"></i> <?php echo stripcslashes($_GET['success']); ?></div>
                <?php } ?>
                <?php if (isset($_GET['error'])) { ?>
                    <div class="danger"><i class="fa fa-exclamation-circle"></i> <?php echo stripcslashes($_GET['error']); ?></div>
                <?php } ?>

                <div class="table-container">
                    <table class="main-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($events != 0) { 
                                foreach ($events as $event) { 
                            ?>
                            <tr>
                                <td>#<?=$event['id']?></td>
                                <td>
                                    <?php if(!empty($event['image'])) { ?>
                                        <img src="img/events/<?=$event['image']?>" style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px;">
                                    <?php } else { ?>
                                        <span class="text-secondary"><i class="fa fa-image"></i></span>
                                    <?php } ?>
                                </td>
                                <td><strong><?=$event['title']?></strong></td>
                                <td><?=$event['date']?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="view-event.php?id=<?=$event['id']?>" class="icon-btn" title="View"><i class="fa fa-eye"></i></a>
                                    <?php 
                                        $can_manage = false;
                                        if($_SESSION['role'] == 'authority') $can_manage = true;
                                        else if($_SESSION['role'] == 'club_admin') {
                                            $organizers = get_event_organizers($conn, $event['id']);
                                            if($organizers){
                                                foreach($organizers as $org){
                                                    if($org['organizer_type'] == 'club' && $org['club_id'] == $admin_club_id){
                                                        $can_manage = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        
                                        if($can_manage):
                                    ?>
                                        <a href="edit-event.php?id=<?=$event['id']?>" class="icon-btn edit" title="Edit"><i class="fa fa-pencil"></i></a>
                                        <a href="app/delete-event.php?id=<?=$event['id']?>&csrf_token=<?=generate_csrf_token()?>" class="icon-btn delete" title="Delete"><i class="fa fa-trash"></i></a>
                                    <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="5" class="text-center p-4">
                                    <p class="text-secondary mb-0">No events found.</p>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="col-12 mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-premium justify-content-center">
                            <?php if($page > 1) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="events.php?page=<?=$page-1?><?=!empty($search)?'&search='.urlencode($search):''?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php for($i=1; $i<=$total_pages; $i++) { ?>
                                <li class="page-item <?=$i==$page?'active':''?>">
                                    <a class="page-link" href="events.php?page=<?=$i?><?=!empty($search)?'&search='.urlencode($search):''?>"><?=$i?></a>
                                </li>
                            <?php } ?>

                            <?php if($page < $total_pages) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="events.php?page=<?=$page+1?><?=!empty($search)?'&search='.urlencode($search):''?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
			</div>
		</div>
	</div>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit;
}
 ?>
