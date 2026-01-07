<?php
session_start();
include 'DB_connection.php';
include 'app/Model/Notice.php';
include 'app/Model/Club.php';
include 'inc/csrf_helper.php';

// Check if user is logged in and has proper role
if (!isset($_SESSION['id']) || !in_array($_SESSION['role'], ['club_admin', 'authority'])) {
    header("Location: index.php");
    exit;
}

$user_role = $_SESSION['role'];
$user_id = $_SESSION['id'];

// Get action from URL
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$notice_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Messages
$success_msg = isset($_GET['success']) ? urldecode($_GET['success']) : '';
$error_msg = isset($_GET['error']) ? urldecode($_GET['error']) : '';

// For list action - pagination and search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$admin_club_id = ($user_role == 'club_admin') ? get_club_id_by_admin($conn, $user_id) : null;

if (!empty($search)) {
    $total_notices = count_search_notices($conn, $search);
    $notices = search_notices_paginated($conn, $search, $offset, $limit);
} else {
    $total_notices = count_all_notices($conn);
    $notices = get_all_notices_paginated($conn, $offset, $limit);
}

$total_pages = ceil($total_notices / $limit);

// Departments List
$departments = ["CSE", "BBA", "EEE", "Pharmacy", "English", "Economics", "Law", "Social Science"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notices | EWU Club Event Organizer</title>
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
                        <i class="fa fa-bullhorn"></i>
                        Notice Management
                    </div>
                    <div class="search-box mb-4">
                        <form action="notices.php" method="GET" style="display: flex; gap: 5px;">
                            <input type="hidden" name="action" value="list">
                            <input type="text" name="search" value="<?=htmlspecialchars($search)?>" placeholder="Search notices by title..." class="input-1" style="padding: 8px 15px; min-width: 500px; background: #e9eaffff;">
                            <button type="submit" class="btn btn-primary" style="padding: 8px 15px;">
                                <i class="fa fa-search"></i>
                            </button>
                            <?php if(!empty($search)): ?>
                                <a href="notices.php?action=list" class="btn btn-secondary" style="padding: 8px 15px;" title="Clear Search">
                                    <i class="fa fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="action-buttons">
                        <?php if ($action === 'list'): ?>
                            <a href="notices.php?action=create" class="btn-primary">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        <?php else: ?>
                            <a href="notices.php?action=list" class="btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($success_msg): ?>
                    <div class="success"><i class="fa fa-check-circle"></i> <?=$success_msg?></div>
                <?php endif; ?>

                <?php if ($error_msg): ?>
                    <div class="danger"><i class="fa fa-exclamation-circle"></i> <?=$error_msg?></div>
                <?php endif; ?>

                <!-- LIST VIEW -->
                <?php if ($action === 'list'): ?>
                    

                    <div class="table-container">
                        <table class="main-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Source</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($notices != 0): foreach ($notices as $notice): 
                                    $sources = get_notice_sources($conn, $notice['id']);
                                    $sources_display = get_source_display($sources);
                                ?>
                                <tr>
                                    <td>#<?=$notice['id']?></td>
                                    <td style="font-weight: 600;"><?=$notice['title']?></td>
                                    <td>
                                        <div class="status-badge in_progress" style="background: var(--primary-light); color: var(--primary-color);">
                                            <?=$sources_display?>
                                        </div>
                                    </td>
                                    <td><?=date('M d, Y', strtotime($notice['created_at']))?></td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="view-notice.php?id=<?=$notice['id']?>" class="icon-btn" style="background: #f1f5f9; color: #475569;" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <?php 
                                                $can_manage = false;
                                                if($user_role == 'authority') $can_manage = true;
                                                else {
                                                    if($sources){
                                                        foreach($sources as $s){
                                                            if($s['source_type'] == 'club' && $s['club_id'] == $admin_club_id){
                                                                $can_manage = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                if($can_manage):
                                            ?>
                                            <a href="notices.php?action=edit&id=<?=$notice['id']?>" class="icon-btn edit" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <button onclick="confirmDelete(<?=$notice['id']?>)" class="icon-btn delete" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px;">
                                        <div class="empty-state">
                                            <i class="fa fa-folder-open-o"></i>
                                            <h3><?= !empty($search) ? "No notices match your search" : "No notices found" ?></h3>
                                            <p><?= !empty($search) ? "Try using different keywords or clear the search." : "Create your first notice to share with university community." ?></p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
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
                                        <a class="page-link" href="notices.php?action=list&page=<?=$page-1?><?=!empty($search)?'&search='.urlencode($search):''?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php } ?>

                                <?php for($i=1; $i<=$total_pages; $i++) { ?>
                                    <li class="page-item <?=$i==$page?'active':''?>">
                                        <a class="page-link" href="notices.php?action=list&page=<?=$i?><?=!empty($search)?'&search='.urlencode($search):''?>"><?=$i?></a>
                                    </li>
                                <?php } ?>

                                <?php if($page < $total_pages) { ?>
                                    <li class="page-item">
                                        <a class="page-link" href="notices.php?action=list&page=<?=$page+1?><?=!empty($search)?'&search='.urlencode($search):''?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>

                <!-- CREATE / EDIT VIEW -->
                <?php elseif ($action === 'create' || ($action === 'edit' && $notice_id)): 
                    $notice_data = null;
                    $current_source_val = '';
                    $current_source_name = '';

                    if ($action === 'edit') {
                        $notice_data = get_notice_by_id($conn, $notice_id);
                        if (!$notice_data) {
                            echo "<script>window.location.href='notices.php?error=Notice not found';</script>";
                            exit;
                        }
                        $current_sources_raw = get_notice_sources($conn, $notice_id);
                        
                        // Security Check
                        $can_edit = ($user_role == 'authority');
                        if(!$can_edit && $current_sources_raw){
                            foreach($current_sources_raw as $s){
                                if($s['source_type'] == 'club' && $s['club_id'] == $admin_club_id){
                                    $can_edit = true; break;
                                }
                            }
                        }
                        if(!$can_edit) {
                            echo "<script>window.location.href='notices.php?error=Unauthorized';</script>"; exit;
                        }

                        if ($current_sources_raw && isset($current_sources_raw[0])) {
                            $src = $current_sources_raw[0];
                            if ($src['source_type'] == 'authority') {
                                $current_source_val = 'authority';
                                $current_source_name = 'East West Authority';
                            } else if ($src['source_type'] == 'club') {
                                $current_source_val = 'club_' . $src['club_id'];
                                $current_source_name = $src['club_name'];
                            } else if ($src['source_type'] == 'department') {
                                $current_source_val = 'dept_' . $src['department_name'];
                                $current_source_name = $src['department_name'] . ' Dept';
                            }
                        }
                    }
                ?>
                    <div class="form-container">
                        <form class="form-1" action="app/<?=($action == 'edit') ? 'update-notice.php' : 'add-notice.php'?>" method="POST" enctype="multipart/form-data">
                            <?php csrf_token(); ?>
                            <?php if($action == 'edit'): ?>
                                <input type="hidden" name="id" value="<?=$notice_id?>">
                            <?php endif; ?>

                            <div class="input-holder">
                                <label>Notice Title</label>
                                <input type="text" name="title" class="input-1" value="<?=$notice_data['title'] ?? ''?>" placeholder="e.g. Annual General Meeting 2026" required>
                            </div>

                            <div class="input-holder">
                                <label>Description</label>
                                <textarea name="description" class="input-1" placeholder="Write full details about the notice..." required><?=$notice_data['description'] ?? ''?></textarea>
                            </div>

                            <div class="input-holder">
                                <label>Notice From (Select Source)</label>
                                <div class="assign-container">
                                    <div class="selected-pills" id="sourcePills">
                                        <?php if($current_source_val): ?>
                                            <div class="user-pill">
                                                <?=$current_source_name?>
                                                <i class="fa fa-times" onclick="removeSource(event)"></i>
                                            </div>
                                        <?php else: ?>
                                            <span class="placeholder-text" style="color: #94a3b8; font-size: 14px;">Select posting authority...</span>
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="add-btn" id="sourceBtn">
                                        <i class="fa fa-chevron-down"></i>
                                    </button>
                                    
                                    <div class="user-dropdown" id="sourceDropdown">
                                        <?php if($user_role == 'authority'): ?>
                                            <div class="source-group-title" style="padding: 10px 15px; background: #f8fafc; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">University</div>
                                            <label class="dropdown-item" style="display: flex; align-items: center; justify-content: space-between;">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div class="avatar-small" style="width: 30px; height: 30px; background: #e0e7ff; color: #6366f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                                        <i class="fa fa-university"></i>
                                                    </div>
                                                    <span>East West University Authority</span>
                                                </div>
                                                <input type="radio" name="source_radio" value="authority" data-name="East West Authority" <?=$current_source_val=='authority'?'checked':''?> style="accent-color: var(--primary-color);">
                                            </label>
                                        <?php endif; ?>

                                        <?php 
                                            // Check clubs
                                            $clubs = get_all_clubs($conn);
                                            $has_clubs = false;
                                            if ($clubs) {
                                                foreach ($clubs as $c) {
                                                    if ($user_role == 'authority' || $admin_club_id == $c['id']) { $has_clubs = true; break; }
                                                }
                                            }
                                        ?>

                                        <?php if($has_clubs): ?>
                                        <div class="source-group-title" style="padding: 10px 15px; background: #f8fafc; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">My Clubs</div>
                                        <?php 
                                            foreach($clubs as $club): 
                                                $can_select = false;
                                                if($user_role == 'authority') $can_select = true;
                                                else if($admin_club_id == $club['id']) $can_select = true;
                                                
                                                if($can_select):
                                        ?>
                                            <label class="dropdown-item" style="display: flex; align-items: center; justify-content: space-between;">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div class="avatar-small" style="width: 30px; height: 30px; background: #f0fdf4; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                                        <i class="fa fa-users"></i>
                                                    </div>
                                                    <span><?=$club['name']?></span>
                                                </div>
                                                <input type="radio" name="source_radio" value="club_<?=$club['id']?>" data-name="<?=$club['name']?>" <?=$current_source_val=='club_'.$club['id']?'checked':''?> style="accent-color: var(--primary-color);">
                                            </label>
                                        <?php endif; endforeach; endif; ?>

                                        <?php if($user_role == 'authority'): ?>
                                            <div class="source-group-title" style="padding: 10px 15px; background: #f8fafc; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Departments</div>
                                            <?php foreach($departments as $dept): ?>
                                            <label class="dropdown-item" style="display: flex; align-items: center; justify-content: space-between;">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div class="avatar-small" style="width: 30px; height: 30px; background: #fff7ed; color: #ea580c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                                        <i class="fa fa-building-o"></i>
                                                    </div>
                                                    <span><?=$dept?> Department</span>
                                                </div>
                                                <input type="radio" name="source_radio" value="dept_<?=$dept?>" data-name="<?=$dept?> Dept" <?=$current_source_val=='dept_'.$dept?'checked':''?> style="accent-color: var(--primary-color);">
                                            </label>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <input type="hidden" name="source" id="sourceInput" value="<?=$current_source_val?>" required>
                                </div>
                            </div>

                            <div class="input-holder">
                                <label>Attachment (Image)</label>
                                <?php if($action == 'edit' && !empty($notice_data['image'])): ?>
                                    <div style="margin-bottom: 10px;">
                                        <img src="img/notices/<?=$notice_data['image']?>" style="width: 120px; border-radius: 8px; border: 1px solid #ddd;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="image" class="input-1" accept="image/*">
                            </div>

                            <div style="margin-top: 30px;">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    <i class="fa fa-paper-plane"></i> <?=($action == 'edit') ? 'Update Notice' : 'Publish Notice'?>
                                </button>
                            </div>
                        </form>
                    </div>

                    <script>
                        const sourceBtn = document.getElementById('sourceBtn');
                        const sourceDropdown = document.getElementById('sourceDropdown');
                        const sourcePills = document.getElementById('sourcePills');
                        const sourceInput = document.getElementById('sourceInput');
                        const sourceRadios = document.querySelectorAll('input[name="source_radio"]');

                        const toggleDropdown = (e) => {
                            e.stopPropagation();
                            sourceDropdown.classList.toggle('show');
                        };

                        sourceBtn.addEventListener('click', toggleDropdown);
                        sourcePills.addEventListener('click', toggleDropdown);

                        document.addEventListener('click', (e) => {
                            if (!sourceDropdown.contains(e.target) && !sourceBtn.contains(e.target) && !sourcePills.contains(e.target)) {
                                sourceDropdown.classList.remove('show');
                            }
                        });

                        sourceRadios.forEach(radio => {
                            radio.addEventListener('change', () => {
                                if(radio.checked) {
                                    sourcePills.innerHTML = `
                                        <div class="user-pill">
                                            ${radio.dataset.name}
                                            <i class="fa fa-times" onclick="removeSource(event)"></i>
                                        </div>
                                    `;
                                    sourceInput.value = radio.value;
                                    sourceDropdown.classList.remove('show');
                                }
                            });
                        });

                        window.removeSource = function(e) {
                            e.stopPropagation();
                            sourcePills.innerHTML = '<span class="placeholder-text" style="color: #94a3b8; font-size: 14px;">Select posting authority...</span>';
                            sourceInput.value = '';
                            sourceRadios.forEach(r => r.checked = false);
                        };
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm("Permanently delete this notice? This cannot be undone.")) {
                window.location.href = 'app/delete-notice.php?id=' + id + '&csrf_token=<?=generate_csrf_token()?>';
            }
        }
    </script>
</body>
</html>
