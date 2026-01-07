<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "authority")) {
    include "DB_connection.php";
    include "app/Model/User.php";
    include "app/Model/Club.php";
    include "inc/csrf_helper.php";

    // Search logic
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    // Pagination logic
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    if (!empty($search)) {
        $total_users = count_search_users($conn, $search);
        $users = search_users_paginated($conn, $search, $offset, $limit);
    } else {
        $total_users = count_all_users($conn);
        $users = get_all_users_paginated($conn, $offset, $limit);
    }
    
    $total_pages = ceil($total_users / $limit);
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Manage Users</title>
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
						Manage Users
					</span>
					
					<div class="search-box">
						<form action="user.php" method="GET" style="display: flex; gap: 5px;">
							<input type="text" name="search" value="<?=htmlspecialchars($search)?>" placeholder="Search users..." class="input-1" style="padding: 8px 15px; min-width: 500px; background: #e9eaffff;">
							<button type="submit" class="btn btn-primary" style="padding: 8px 15px; ">
								<i class="fa fa-search"></i>
							</button>
							<?php if(!empty($search)): ?>
								<a href="user.php" class="btn btn-secondary" style="padding: 8px 15px;" title="Clear Search">
									<i class="fa fa-times"></i>
								</a>
							<?php endif; ?>
						</form>
					</div>

					<a href="add-user.php" class="btn-primary">

						<i class="fa fa-plus"></i>
						Add User
					</a>
				</div>
				
				<?php if (isset($_GET['success'])) {?>
					<div class="success" role="alert">
						<?php echo stripcslashes($_GET['success']); ?>
					</div>
				<?php } ?>
				
				<?php if ($users != 0) { ?>
					<div class="table-container">
						<table class="main-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Full Name</th>
									<th>Username</th>
									<th>Club</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=0; foreach ($users as $user) { 
                                   if($user['id'] == $_SESSION['id']) continue;
                                ?>
								<tr>
									<td><?=++$i?></td>
									<td><?=$user['full_name']?></td>
									<td>@<?=$user['username']?></td>
									<td>
										<?php 
										// Check if user is member or admin of any club
										$user_club_id = get_user_club_id($conn, $user['id']);
										$admin_club_id = get_club_id_by_admin($conn, $user['id']);
										
										if ($user_club_id != 0) {
											$club = get_club_by_id($conn, $user_club_id);
											echo '<span class="status-badge pending" style="background: #d1fae5; color: #059669; border-color: transparent;">' . htmlspecialchars($club['name']) . '</span>';
										} elseif ($admin_club_id != 0) {
											$club = get_club_by_id($conn, $admin_club_id);
											echo '<span class="status-badge pending" style="background: var(--primary-light); color: var(--primary-color); border-color: transparent;">' . htmlspecialchars($club['name']) . ' (Admin)</span>';
										} else {
											echo '<span class="status-badge pending" style="background: #fee2e2; color: #dc2626; border-color: transparent;">NULL</span>';
										}
										?>
									</td>
									<td>
										<div class="table-actions">
											<a href="edit-user.php?id=<?=$user['id']?>" class="icon-btn edit" title="Edit">
												<i class="fa fa-edit"></i>
											</a>
											<a href="delete-user.php?id=<?=$user['id']?>&csrf_token=<?=generate_csrf_token()?>" class="icon-btn delete" title="Delete">
												<i class="fa fa-trash"></i>
											</a>
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
										<a class="page-link" href="user.php?page=<?=$page-1?><?=!empty($search)?'&search='.urlencode($search):''?>" aria-label="Previous">
											<span aria-hidden="true">&laquo;</span>
										</a>
									</li>
								<?php } ?>

								<?php for($i=1; $i<=$total_pages; $i++) { ?>
									<li class="page-item <?=$i==$page?'active':''?>">
										<a class="page-link" href="user.php?page=<?=$i?><?=!empty($search)?'&search='.urlencode($search):''?>"><?=$i?></a>
									</li>
								<?php } ?>

								<?php if($page < $total_pages) { ?>
									<li class="page-item">
										<a class="page-link" href="user.php?page=<?=$page+1?><?=!empty($search)?'&search='.urlencode($search):''?>" aria-label="Next">
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
						<h3 style="color: #7F8C8D;">No users found</h3>
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
