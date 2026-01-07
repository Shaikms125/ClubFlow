<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "authority") {
    include "DB_connection.php";
    include "app/Model/Club.php";
    include "inc/csrf_helper.php";

    // Search logic
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    // Pagination logic
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 12;
    $offset = ($page - 1) * $limit;

    if (!empty($search)) {
        $total_clubs = count_search_clubs($conn, $search);
        $clubs = search_clubs_paginated($conn, $search, $offset, $limit);
    } else {
        $total_clubs = count_clubs($conn);
        $clubs = get_all_clubs_paginated($conn, $offset, $limit);
    }
    
    $total_pages = ceil($total_clubs / $limit);

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Clubs</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<style>
		.club-card:hover ~ .club-delete-btn,
		.club-delete-btn:hover {
			opacity: 1 !important;
		}
		.club-delete-btn:hover {
			background: #dc2626 !important;
			color: white !important;
			transform: scale(1.05);
		}
	</style>
	<script>
		function confirmDelete(event, clubId, clubName) {
			event.preventDefault();
			event.stopPropagation();
			
			if(confirm('Are you sure you want to delete "' + clubName + '"?\n\nThis will:\n• Remove all club admins\n• Remove all club members\n• Delete all associated tasks\n\nThis action cannot be undone.')) {
				window.location.href = 'app/delete-club.php?id=' + clubId + '&csrf_token=<?=generate_csrf_token()?>';
			}
		}
	</script>
</head>
<body>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<div class="main-container">
			<?php include "inc/header.php" ?>
			<div class="content">
				<div class="section-title">
					<span class="title-text">
						<i class="fa fa-university"></i>
						Manage Clubs
					</span>

					<div class="search-box">
						<form action="clubs.php" method="GET" style="display: flex; gap: 5px;">
							<input type="text" name="search" value="<?=htmlspecialchars($search)?>" placeholder="Search clubs..." class="input-1" style="padding: 8px 15px; min-width: 500px; background: #e9eaffff;">
							<button type="submit" class="btn btn-primary" style="padding: 8px 15px;">
								<i class="fa fa-search"></i>
							</button>
							<?php if(!empty($search)): ?>
								<a href="clubs.php" class="btn btn-secondary" style="padding: 8px 15px;" title="Clear Search">
									<i class="fa fa-times"></i>
								</a>
							<?php endif; ?>
						</form>
					</div>

					<a href="add-club.php" class="btn-primary">

						<i class="fa fa-plus"></i>
						Add Club
					</a>
				</div>
				
				<?php if (isset($_GET['success'])) {?>
					<div class="success" role="alert">
						<?php echo stripcslashes($_GET['success']); ?>
					</div>
				<?php } ?>
				
				<?php if ($clubs != 0) { ?>
					<div class="dashboard" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
						<?php foreach ($clubs as $club) { 
                            // Fetch member count for this club
                            $club_members = get_club_members($conn, $club['id']);
                            $count = ($club_members != 0) ? count($club_members) : 0;
                        ?>
						<div style="position: relative;">
							<a href="single-club.php?id=<?=$club['id']?>" class="dashboard-item club-card" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 200px;">
								<div style="width: 60px; height: 60px; background: #e0e7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; color: #6366f1;">
	                                <i class="fa fa-university" style="font-size: 28px; margin: 0;"></i>
	                            </div>
								<div class="item-number" style="font-size: 20px; margin-bottom: 5px;"><?=$club['name']?></div>
								<div class="item-label"><?=$count?> Members</div>
	                            <span style="margin-top: 15px; font-size: 13px; color: #6366f1; font-weight: 500;">
	                                Manage Club <i class="fa fa-arrow-right" style="font-size: 12px; margin-left: 5px;"></i>
	                            </span>
							</a>
							<button onclick="confirmDelete(event, <?=$club['id']?>, '<?=htmlspecialchars($club['name'], ENT_QUOTES)?>')" 
							        class="club-delete-btn" 
							        title="Delete Club"
							        style="position: absolute; top: 10px; right: 10px; background: #fee; color: #dc2626; border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; opacity: 1; transition: all 0.2s ease; z-index: 10;">
								<i class="fa fa-trash" style="font-size: 14px;"></i>
							</button>
						</div>
						<?php } ?>
					</div>
				<?php } else { ?>
					<div class="empty-state">
						<i class="fa fa-university"></i>
						<h3>No clubs found</h3>
						<p>Create a new club to get started</p>
					</div>
				<?php } ?>
				
				<!-- Pagination -->
				<?php if ($total_pages > 1): ?>
				<div class="col-12 mt-4">
					<nav aria-label="Page navigation">
						<ul class="pagination pagination-premium justify-content-center">
							<?php if($page > 1) { ?>
								<li class="page-item">
									<a class="page-link" href="clubs.php?page=<?=$page-1?><?=!empty($search)?'&search='.urlencode($search):''?>" aria-label="Previous">
										<span aria-hidden="true">&laquo;</span>
									</a>
								</li>
							<?php } ?>

							<?php for($i=1; $i<=$total_pages; $i++) { ?>
								<li class="page-item <?=$i==$page?'active':''?>">
									<a class="page-link" href="clubs.php?page=<?=$i?><?=!empty($search)?'&search='.urlencode($search):''?>"><?=$i?></a>
								</li>
							<?php } ?>

							<?php if($page < $total_pages) { ?>
								<li class="page-item">
									<a class="page-link" href="clubs.php?page=<?=$page+1?><?=!empty($search)?'&search='.urlencode($search):''?>" aria-label="Next">
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
   header("Location: index.php?action=login&error=$em");
   exit();
}
 ?>
