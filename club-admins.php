<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "authority") {
    include "DB_connection.php";
    include "app/Model/Club.php";

    // Search logic
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    // Pagination logic
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    if (!empty($search)) {
        $total_admins = count_search_club_admins($conn, $search);
        $admins = search_club_admins_paginated($conn, $search, $offset, $limit);
    } else {
        $total_admins = count_club_admins($conn);
        $admins = get_all_club_admins_paginated($conn, $offset, $limit);
    }
    
    $total_pages = ceil($total_admins / $limit);
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Club Admins</title>
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
					<span class="title-text">
						<i class="fa fa-users"></i>
						Club Admins
					</span>

					<div class="search-box">
						<form action="club-admins.php" method="GET" style="display: flex; gap: 5px;">
							<input type="text" name="search" value="<?=htmlspecialchars($search)?>" placeholder="Search admins or clubs..." class="input-1" style="padding: 8px 15px; min-width: 500px; background: #e9eaffff;">
							<button type="submit" class="btn btn-primary" style="padding: 8px 15px;">
								<i class="fa fa-search"></i>
							</button>
							<?php if(!empty($search)): ?>
								<a href="club-admins.php" class="btn btn-secondary" style="padding: 8px 15px;" title="Clear Search">
									<i class="fa fa-times"></i>
								</a>
							<?php endif; ?>
						</form>
					</div>

					<a href="assign-club-admin.php" class="btn-primary">

						<i class="fa fa-plus"></i>
						Assign New
					</a>
				</div>
				
				<?php if (isset($_GET['success'])) {?>
					<div class="success" role="alert">
						<?php echo stripcslashes($_GET['success']); ?>
					</div>
				<?php } ?>
				
				<?php if ($admins != 0) { ?>
					<div class="table-container">
						<table class="main-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Admin Name</th>
									<th>Club Name</th>
									<th>Username</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=0; foreach ($admins as $admin) { ?>
								<tr>
									<td><?=++$i?></td>
									<td><?=$admin['full_name']?></td>
									<td><?=$admin['club_name']?></td>
									<td><?=$admin['username']?></td>
									<td>
										<div class="table-actions">
											<button class="icon-btn delete" disabled title="Remove">
												<i class="fa fa-trash"></i>
											</button>
										</div>
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
										<a class="page-link" href="club-admins.php?page=<?=$page-1?><?=!empty($search)?'&search='.urlencode($search):''?>" aria-label="Previous">
											<span aria-hidden="true">&laquo;</span>
										</a>
									</li>
								<?php } ?>

								<?php for($i=1; $i<=$total_pages; $i++) { ?>
									<li class="page-item <?=$i==$page?'active':''?>">
										<a class="page-link" href="club-admins.php?page=<?=$i?><?=!empty($search)?'&search='.urlencode($search):''?>"><?=$i?></a>
									</li>
								<?php } ?>

								<?php if($page < $total_pages) { ?>
									<li class="page-item">
										<a class="page-link" href="club-admins.php?page=<?=$page+1?><?=!empty($search)?'&search='.urlencode($search):''?>" aria-label="Next">
											<span aria-hidden="true">&raquo;</span>
										</a>
									</li>
								<?php } ?>
							</ul>
						</nav>
					</div>
					<?php endif; ?>

				<?php } else { ?>
					<div style="text-align: center; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
						<i style="font-size: 48px; color: #ccc; display: block; margin-bottom: 15px;" class="fa fa-inbox"></i>
						<h3 style="color: #7F8C8D;">No club admins found</h3>
					</div>
				<?php } ?>
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
