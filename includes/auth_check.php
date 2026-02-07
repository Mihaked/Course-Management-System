<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_role($allowed_roles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
        
        if ($role === 'admin') {
            header("Location: dashboard.php");
        } elseif ($role === 'instructor') {
            header("Location: manage_materials.php");
        } elseif ($role === 'student') {
            header("Location: student_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    }
}
?>
