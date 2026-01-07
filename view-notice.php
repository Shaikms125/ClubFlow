<?php 
session_start();
include "DB_connection.php";
include "app/Model/Notice.php";
include "app/Model/Club.php";
include "inc/csrf_helper.php";


if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$notice = get_notice_by_id($conn, $id);

if($notice == 0){
    header("Location: index.php");
    exit();
}

$sources = get_notice_sources($conn, $id);
$source_display = get_source_display($sources);
$has_image = !empty($notice['image']);
$tab = 'notices'; 

// Check authorization to show Edit button
$can_edit = false;
if(isset($_SESSION['role'])){
    if($_SESSION['role'] == 'authority') $can_edit = true;
    else if($_SESSION['role'] == 'club_admin' && $sources){
        $admin_club_id = get_club_id_by_admin($conn, $_SESSION['id']);
        foreach($sources as $s){
            if($s['source_type'] == 'club' && $s['club_id'] == $admin_club_id){
                $can_edit = true; break;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$notice['title']?> | EWU Official Notice</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
    
    <style>
        /* Notice Container Layout - Document Style */
        .notice-document {
            max-width: 850px;
            margin: 40px auto;
            background: white;
            padding: 60px 80px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            border-top: 12px solid #004b8d;
            position: relative;
            min-height: 800px;
        }

        /* Document Header Group */
        .document-header-group {
            text-align: center;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 30px;
            margin-bottom: 30px;
        }

        .uni-name {
            font-size: 32px;
            font-weight: 800;
            color: #004b8d;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0 0 10px 0;
            font-family: var(--ff); /* Ensure it follows global font style */
        }

        .notice-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            font-size: 14px;
            color: #64748b;
            font-weight: 600;
        }

        /* Tag Spacing */
        .notice-tag-row {
            text-align: center;
            padding: 20px 0;
        }

        .notice-tag-row span {
            font-size: 24px;
            font-weight: 900;
            text-decoration: underline;
            text-underline-offset: 12px;
            letter-spacing: 8px;
            color: #1e293b;
            display: inline-block;
        }

        /* Body Spacing */
        .notice-content-group {
            padding: 20px 0;
        }

        .notice-subject {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 25px;
            line-height: 1.5;
            /* text-transform: uppercase; Removed to show user input case */
        }

        .notice-body-text {
            font-size: 17px;
            line-height: 1.8;
            color: #334155;
            text-align: justify;
            white-space: pre-line;
            /* Use standard serif for professional document feel without loading extra fonts */
            font-family: Georgia, 'Times New Roman', Times, serif; 
        }

        /* Footer Spacing */
        .footer-document {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #f1f5f9;
        }

        .auth-block {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .auth-name {
            font-size: 16px;
            font-weight: 800;
            color: #004b8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .auth-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .notice-attachment {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px dashed #e2e8f0;
        }

        .attachment-img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        @media print {
            body { background: white !important; padding: 0 !important; }
            .landing-header { display: none !important; }
            .notice-document { 
                box-shadow: none !important; 
                border: none !important; 
                width: 100% !important; 
                padding: 0 !important; 
                margin: 0 !important;
                min-height: auto !important;
            }
        }

        @media (max-width: 900px) {
            .notice-document { padding: 40px 30px; margin: 20px 15px; }
            .uni-name { font-size: 24px; }
            .notice-tag-row span { font-size: 20px; letter-spacing: 4px; }
        }
    </style>
</head>
<body class="public-page">
    
    <?php include "inc/homepage-header.php"; ?>

    <div class="notice-document">
        <!-- Header Group -->
        <div class="document-header-group">
            <h1 class="uni-name">East West University</h1>
            <div class="notice-meta-row" style="justify-content: flex-end;">
                <span>Date: <?=date('d F, Y', strtotime($notice['created_at']))?></span>
            </div>
        </div>

        <!-- Tag Group -->
        <div class="notice-tag-row">
            <span>NOTICE</span>
        </div>

        <!-- Content Group -->
        <div class="notice-content-group">
            <div class="notice-subject">
                <?=htmlspecialchars($notice['title'])?>
            </div>

            <div class="notice-body-text">
                <?=htmlspecialchars($notice['description'])?>
            </div>
        </div>

        <!-- Authentication Group -->
        <div class="footer-document">
            <div class="auth-block">
                <div>
                    <div class="auth-name">University Authority</div>
                    <div class="auth-label">Issued By: <?=$source_display?></div>
                </div>
                <?php if($can_edit): ?>
                    <a href="notices.php?action=edit&id=<?=$id?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa fa-pencil"></i> Edit Notice
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if($has_image): ?>
            <div class="notice-attachment">
                <div class="auth-label" style="margin-bottom: 20px;"><i class="fa fa-paperclip"></i> Attachment</div>
                <img src="img/notices/<?=$notice['image']?>" alt="Notice Attachment" class="attachment-img">
            </div>
        <?php endif; ?>

    </div>

    <?php include "inc/login-modal.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
