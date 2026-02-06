<?php

if (session_status() === PHP_SESSION_NONE) { session_start(); }

include dirname(__FILE__) . '/auth_check.php';
include dirname(__FILE__) . '/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . $path_prefix . "index.php");
    exit();
}

$current_role = $_SESSION['role'];
$page_title = isset($page_title) ? $page_title : "CMS Dashboard";
$nav_items = [];

$role_home = [
    'admin' => 'dashboard.php',
    'instructor' => 'manage_materials.php',
    'student' => 'student_dashboard.php',
];

if ($current_role == 'admin') {
    $nav_items = [
        'Dashboard' => 'dashboard.php',
        'Manage Users' => 'users.php',
        'Manage Courses' => 'courses.php',
        'Enrollments' => 'enrollments.php'
    ];
} elseif ($current_role == 'instructor') {
    $nav_items = [
        'Manage Materials' => 'manage_materials.php',
        'Manage Assignments' => 'manage_assignments.php'
    ];
} elseif ($current_role == 'student') {
    $nav_items = [
        'Dashboard' => 'student_dashboard.php'
    ];
}

$display_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> | CMS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6; 
            min-height: 100vh;
            color: #333;
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: linear-gradient(to bottom, #ffffff, #eef2f3); 
            z-index: -1;
        }

        .card-custom {
            background: #ffffff;
            border: 1px solid #e0e0e0; 
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .card-custom:hover { 
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-color: #333;
        }

        .navbar {
            background: #212529 !important; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .page-content { padding-top: 30px; padding-bottom: 50px; }
        
        a { text-decoration: none; }
    </style>
</head>
<body>

<div id="particles-js"></div>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?php echo $role_home[$current_role] ?? 'index.php'; ?>">
            <i class="bi bi-mortarboard-fill me-2"></i>CMS System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach ($nav_items as $label => $link): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $link; ?>"><?php echo $label; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" class="rounded-circle me-2" width="30">
                        <?php echo htmlspecialchars($display_name); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <?php if($current_role == 'student'): ?>
                            <li><a class="dropdown-item" href="index.php?view=profile">Profile</a></li>
                            <li><a class="dropdown-item" href="index.php?view=grades">Grades</a></li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container page-content">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
