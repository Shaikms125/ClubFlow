<?php 
session_start();
include "DB_connection.php";
include "app/Model/Notice.php";

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

$source_display = get_source_display($notice);
$has_image = !empty($notice['image']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$notice['title']?> | EWU Notices</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px 0;
        }
        
        .notice-container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .notice-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            display: block;
        }

        .notice-header {
            padding: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .notice-title {
            font-size: 32px;
            font-weight: bold;
            margin: 0 0 20px 0;
        }

        .notice-meta {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            font-size: 14px;
        }

        .notice-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notice-body {
            padding: 40px;
            line-height: 1.8;
            color: #333;
            font-size: 16px;
        }

        .notice-footer {
            padding: 20px 40px;
            background-color: #f9f9f9;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .source-badge {
            background-color: #e8f4f8;
            color: #0066cc;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background-color: #5a6268;
            color: white;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #ffc107;
            color: black;
        }

        .btn-edit:hover {
            background-color: #e0a800;
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="notice-container">
        <?php if($has_image): ?>
            <img src="img/notices/<?=$notice['image']?>" alt="<?=$notice['title']?>" class="notice-image">
        <?php endif; ?>

        <div class="notice-header">
            <h1 class="notice-title"><?=$notice['title']?></h1>
            <div class="notice-meta">
                <div class="notice-meta-item">
                    <i class="fa fa-calendar"></i>
                    <span><?=date('F d, Y', strtotime($notice['created_at']))?></span>
                </div>
                <div class="notice-meta-item">
                    <span class="source-badge"><?=$source_display?></span>
                </div>
            </div>
        </div>

        <div class="notice-body">
            <?=nl2br(htmlspecialchars($notice['description']))?>
        </div>

        <div class="notice-footer">
            <a href="index.php" class="btn btn-back">
                <i class="fa fa-arrow-left"></i> Back to Home
            </a>
            <?php if(isset($_SESSION['role']) && in_array($_SESSION['role'], ['authority', 'club_admin'])): ?>
                <a href="notices.php?action=edit&id=<?=$id?>" class="btn btn-edit">
                    <i class="fa fa-pencil"></i> Edit Notice
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
