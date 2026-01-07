<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && ($_SESSION['role'] == 'club_admin' || $_SESSION['role'] == 'authority')) {
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Event</title>
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
                        <i class="fa fa-calendar-plus-o"></i> Create Event
                    </div>
                    <div class="action-buttons">
                        <a href="events.php" class="btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="form-container">
                    <form action="app/add-event.php" method="POST" enctype="multipart/form-data" class="form-1">
                        <?php if (isset($_GET['error'])) { ?>
                            <div class="danger mb-4">
                                <i class="fa fa-exclamation-circle"></i> <?php echo stripcslashes($_GET['error']); ?>
                            </div>
                        <?php } ?>
                        <?php include "inc/csrf_helper.php"; csrf_token(); ?>
                        
                        <div class="input-holder">
                            <label>Event Title</label>
                            <input type="text" class="input-1" name="title" required placeholder="e.g. Spring Fest 2026">
                        </div>
                        
                        <div class="input-holder">
                            <label>Description</label>
                            <textarea class="input-1" name="description" rows="5" required placeholder="Describe the event..."></textarea>
                        </div>
                        
                        <div class="input-holder">
                            <label>Event Date</label>
                            <input type="date" class="input-1" name="date" required>
                        </div>

                        <div class="input-holder">
                            <label>Event Place</label>
                            <input type="text" class="input-1" name="place" placeholder="e.g., Main Auditorium, Room 101" required>
                        </div>

                        <div class="input-holder">
                            <label>Cover Image (Max 1MB)</label>
                            <input type="file" class="input-1" name="image">
                        </div>

                        <!-- Organizers Multi-Select Pill UI -->
                        <div class="input-holder">
                            <label>Organizers</label>
                            
                            <div class="assign-container">
                                <!-- Pills Container -->
                                <div class="selected-pills" id="orgPills">
                                    <span class="placeholder-text" style="color: #94a3b8; font-size: 14px;">Select organizers...</span>
                                </div>
                                
                                <button type="button" class="add-btn" id="orgBtn">
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                
                                <!-- Dropdown -->
                                <div class="user-dropdown" id="orgDropdown">
                                    <div class="source-group-title" style="padding: 10px 15px; background: #f8fafc; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">General</div>
                                    <label class="dropdown-item" style="display: flex; align-items: center; justify-content: space-between;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="avatar-small" style="width: 30px; height: 30px; background: #e0e7ff; color: #6366f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                                <i class="fa fa-university"></i>
                                            </div>
                                            <span>East West Authority</span>
                                        </div>
                                        <input type="checkbox" class="org-check" value="authority" data-name="East West Authority" data-type="authority" style="accent-color: var(--primary-color);">
                                    </label>

                                    <label class="dropdown-item" style="display: flex; align-items: center; justify-content: space-between;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="avatar-small" style="width: 30px; height: 30px; background: #fff7ed; color: #ea580c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                                <i class="fa fa-building-o"></i>
                                            </div>
                                            <span>Department</span>
                                        </div>
                                        <input type="checkbox" class="org-check" value="department" data-name="Department" data-type="department" style="accent-color: var(--primary-color);">
                                    </label>

                                    <!-- Club List -->
                                    <?php 
                                        include "DB_connection.php";
                                        include "app/Model/Club.php";
                                        $clubs = get_all_clubs($conn);
                                        $has_club_permission = ($_SESSION['role'] == 'authority' || $_SESSION['role'] == 'club_admin');
                                    ?>
                                    
                                    <?php if($has_club_permission && $clubs != 0): ?>
                                    <div class="source-group-title" style="padding: 10px 15px; background: #f8fafc; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Clubs</div>
                                    <?php foreach($clubs as $club): ?>
                                        <label class="dropdown-item" style="display: flex; align-items: center; justify-content: space-between;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div class="avatar-small" style="width: 30px; height: 30px; background: #f0fdf4; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                                    <i class="fa fa-users"></i>
                                                </div>
                                                <span><?=$club['name']?></span>
                                            </div>
                                            <input type="checkbox" class="org-check" value="<?=$club['id']?>" data-name="<?=$club['name']?>" data-type="club" style="accent-color: var(--primary-color);">
                                        </label>
                                    <?php endforeach; endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Department Inputs (Conditional) -->
                        <div class="input-holder" id="department_inputs_holder" style="display: none;">
                            <label>Department Names</label>
                            <div id="department_inputs_container">
                                <div class="d-flex gap-2 mb-2">
                                    <input type="text" class="input-1" name="organized_by_department_names[]" placeholder="Enter department name">
                                    <button class="btn btn-secondary" type="button" onclick="addDepartmentInput()"><i class="fa fa-plus" style="color: black;"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Inputs for Form Submission -->
                        <div id="hiddenInputs"></div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-paper-plane"></i> Publish Event
                            </button>
                        </div>
                    </form>
                </div>
			</div>
		</div>
	</div>

	<script>
		// --- Dropdown Components ---
        const orgBtn = document.getElementById('orgBtn');
        const orgDropdown = document.getElementById('orgDropdown');
        const orgPills = document.getElementById('orgPills');
        const orgChecks = document.querySelectorAll('.org-check');
        const hiddenDiv = document.getElementById('hiddenInputs');
        const placeHolder = orgPills.querySelector('.placeholder-text');
        const deptHolder = document.getElementById('department_inputs_holder');

        const toggleDropdown = (e) => {
            e.stopPropagation();
            orgDropdown.classList.toggle('show');
        };

        orgBtn.addEventListener('click', toggleDropdown);
        orgPills.addEventListener('click', toggleDropdown);

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!orgDropdown.contains(e.target) && !orgBtn.contains(e.target) && !orgPills.contains(e.target)) {
                orgDropdown.classList.remove('show');
            }
        });

        // Handle Checkbox Changes
        orgChecks.forEach(chk => {
            chk.addEventListener('change', () => {
                renderPills();
                // Toggle Department Input
                if(chk.dataset.type === 'department') {
                    deptHolder.style.display = chk.checked ? 'block' : 'none';
                }
            });
        });

        function renderPills() {
            // Clear current pills (except placeholder if empty)
            orgPills.innerHTML = '';
            
            // Re-build Hidden Inputs
            hiddenDiv.innerHTML = '';
            
            let count = 0;

            orgChecks.forEach(chk => {
                if(chk.checked) {
                    count++;
                    const name = chk.dataset.name;
                    const val = chk.value;
                    const type = chk.dataset.type;

                    // Create Pill
                    const pill = document.createElement('div');
                    pill.className = 'user-pill';
                    pill.innerHTML = `${name} <i class="fa fa-times" onclick="uncheckOrg('${val}', '${type}', event)"></i>`;
                    orgPills.appendChild(pill);

                    // Create Hidden Inputs
                    if(type === 'authority') {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'organized_by[]';
                        input.value = 'authority';
                        hiddenDiv.appendChild(input);
                    } else if (type === 'department') {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'organized_by[]';
                        input.value = 'department';
                        hiddenDiv.appendChild(input);
                    } else if (type === 'club') {
                        const inputId = document.createElement('input');
                        inputId.type = 'hidden';
                        inputId.name = 'organized_by_club_ids[]';
                        inputId.value = val;
                        hiddenDiv.appendChild(inputId);
                    }
                }
            });
            
            // Add 'club' type flag if any club is selected
            const hasClub = Array.from(orgChecks).some(c => c.checked && c.dataset.type === 'club');
            if(hasClub) {
               const input = document.createElement('input');
               input.type = 'hidden';
               input.name = 'organized_by[]';
               input.value = 'club';
               hiddenDiv.appendChild(input); 
            }

            if(count === 0) {
                orgPills.appendChild(placeHolder);
            }
        }

        window.uncheckOrg = function(val, type, e) {
            e.stopPropagation(); 
            const selector = `.org-check[value="${val}"][data-type="${type}"]`;
            const chk = document.querySelector(selector);
            if(chk) {
                chk.checked = false;
                renderPills();
                if(type === 'department') {
                    deptHolder.style.display = 'none';
                }
            }
        };


		function addDepartmentInput() {
			const container = document.getElementById('department_inputs_container');
			const newDiv = document.createElement('div');
			newDiv.className = 'd-flex gap-2 mb-2';
			newDiv.innerHTML = `
				<input type="text" class="input-1" name="organized_by_department_names[]" placeholder="Enter department name">
				<button class="btn btn-secondary" type="button" onclick="this.parentElement.remove()"><i class="fa fa-minus" style="color: black;"></i></button>
			`;
			container.appendChild(newDiv);
		}

		document.querySelector('form').addEventListener('submit', function(e) {
            const hasAuthority = document.querySelector('.org-check[dataset-type="authority"]')?.checked;
            const hasDept = document.querySelector('.org-check[dataset-type="department"]')?.checked;
            const hasClub = Array.from(document.querySelectorAll('.org-check[dataset-type="club"]')).some(c => c.checked);
            
            // Note: dataset-type is not a valid selector attribute, it should be data-type
            // Let's rely on the hidden inputs we generated!
            
            const hiddenAuth = document.querySelector('input[name="organized_by[]"][value="authority"]');
            const hiddenDept = document.querySelector('input[name="organized_by[]"][value="department"]');
            const hiddenClub = document.querySelector('input[name="organized_by[]"][value="club"]');
            
			if (!hiddenAuth && !hiddenDept && !hiddenClub) {
				e.preventDefault();
				alert('Please select at least one organizer (Authority, Club, or Department)');
				return false;
			}
            
            if(hiddenDept) {
                // Check if text input has value
                let typed = false;
                document.querySelectorAll('input[name="organized_by_department_names[]"]').forEach(i => {
                    if(i.value.trim()) typed = true;
                });
                if(!typed) {
                    e.preventDefault();
                    alert("Please enter a department name.");
                    return false;
                }
            }
		});
	</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit;
}
 ?>
