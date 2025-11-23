<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_role($allowed_roles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
        
        if ($role === 'admin') {
            header("Location: ../admin/dashboard.php");
        } elseif ($role === 'instructor') {
            header("Location: ../instructor/index.php");
        } elseif ($role === 'student') {
            header("Location: ../student/index.php");
        } else {
            header("Location: ../index.php");
        }
        exit();
    }
}
?>