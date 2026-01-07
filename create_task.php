<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "club_admin")) {
    include "DB_connection.php";
    include "app/Model/User.php";

    $all_users = 0;
    if ($_SESSION['role'] == "club_admin") {
        include "app/Model/Club.php";
        $club_id = get_club_id_by_admin($conn, $_SESSION['id']);
        $all_users = get_club_members($conn, $club_id);
    } else if ($_SESSION['role'] == "admin") {
        $all_users = get_all_users($conn);
    }
    
    // Filter out authority and club_admin
    $users = [];
    if ($all_users != 0) {
        foreach ($all_users as $user) {
            if ($user['role'] != 'authority' && $user['role'] != 'club_admin' && $user['role'] != 'admin') {
                $users[] = $user;
            }
        }
    }

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Task</title>
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
						<i class="fa fa-plus-circle"></i>
						Create Task
					</span>
					<div class="action-buttons">
						<a href="tasks.php" class="btn-secondary">
							<i class="fa fa-arrow-left"></i>
							Back
						</a>
					</div>
				</div>
				
				<form class="form-1" method="POST" action="app/add-task.php">
					<?php if (isset($_GET['error'])) {?>
						<div class="danger" role="alert">
							<?php echo stripcslashes($_GET['error']); ?>
						</div>
					<?php } ?>

					<?php include "inc/csrf_helper.php"; csrf_token(); ?>

					<?php if (isset($_GET['success'])) {?>
						<div class="success" role="alert">
							<?php echo stripcslashes($_GET['success']); ?>
						</div>
					<?php } ?>
					
					<div class="input-holder">
						<lable>Title</lable>
						<input type="text" name="title" class="input-1" placeholder="Task title" required>
					</div>
					
					<div class="input-holder">
						<lable>Description</lable>
						<textarea type="text" name="description" class="input-1" placeholder="Task description"></textarea>
					</div>
					
					<div class="input-holder">
						<lable>Due Date</lable>
						<input type="date" name="due_date" class="input-1">
					</div>
					
					<div class="input-holder">
						<lable>Assign To Organizer</lable>
                        
                        <div class="assign-container">
                            <div class="selected-pills" id="selectedPills">
                                <span class="placeholder-text" style="color: #94a3b8; font-size: 14px;">Select a user...</span>
                            </div>
                            <button type="button" class="add-btn" id="addBtn">
                                <i class="fa fa-chevron-down"></i>
                            </button>
                            
                            <div class="user-dropdown" id="userDropdown">
                                <?php if (!empty($users)) { 
                                    foreach ($users as $user) {
                                ?>
                                <label class="dropdown-item" style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div class="avatar-small" style="width: 30px; height: 30px; background: #e0e7ff; color: #6366f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                            <?=strtoupper(substr($user['full_name'], 0, 1))?>
                                        </div>
                                        <span><?=$user['full_name']?> (<?=ucfirst($user['role'])?>)</span>
                                    </div>
                                    <input type="radio" name="assigned_user_radio" class="user-radio" value="<?=$user['id']?>" data-name="<?=$user['full_name']?>" style="accent-color: var(--primary-color);">
                                </label>
                                <?php } } else { ?>
                                    <div style="padding: 10px; text-align: center; color: #94a3b8;">No users found</div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <!-- Hidden input for form submission -->
                        <input type="hidden" name="assigned_to" id="assignedToInput">
					</div>

                    <script>
                        const addBtn = document.getElementById('addBtn');
                        const dropdown = document.getElementById('userDropdown');
                        const radios = document.querySelectorAll('.user-radio');
                        const pillsContainer = document.getElementById('selectedPills');
                        const hiddenInput = document.getElementById('assignedToInput');
                        
                        // Toggle Dropdown
                        const toggleDropdown = (e) => {
                            e.stopPropagation();
                            dropdown.classList.toggle('show');
                        };

                        addBtn.addEventListener('click', toggleDropdown);
                        pillsContainer.addEventListener('click', toggleDropdown); // Also open when clicking the container
                        
                        // Close dropdown when clicking outside
                        document.addEventListener('click', (e) => {
                            if (!dropdown.contains(e.target) && !addBtn.contains(e.target) && !pillsContainer.contains(e.target)) {
                                dropdown.classList.remove('show');
                            }
                        });
                        
                        // Handle Radio Change
                        radios.forEach(radio => {
                            radio.addEventListener('change', () => {
                                if(radio.checked) {
                                    const name = radio.dataset.name;
                                    const id = radio.value;
                                    
                                    // Update Display
                                    pillsContainer.innerHTML = `
                                        <div class="user-pill">
                                            ${name}
                                            <i class="fa fa-times" onclick="removeUser(event)"></i>
                                        </div>
                                    `;
                                    
                                    // Update Hidden Input
                                    hiddenInput.value = id;
                                    
                                    // Close Dropdown
                                    dropdown.classList.remove('show');
                                }
                            });
                        });
                        
                        // Remove User function
                        window.removeUser = function(e) {
                            e.stopPropagation(); // Prevent re-opening dropdown
                            pillsContainer.innerHTML = '<span class="placeholder-text" style="color: #94a3b8; font-size: 14px;">Select a user...</span>';
                            hiddenInput.value = '';
                            
                            // Uncheck all radios
                            radios.forEach(r => r.checked = false);
                        };
                    </script>
					
					<button type="submit" class="btn" style="width: 100%; margin-top: 20px;">
						<i class="fa fa-plus"></i>
						Create Task
					</button>
				</form>
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
