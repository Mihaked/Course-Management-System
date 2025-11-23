<?php
session_start();
include '../includes/db_connection.php';
include '../includes/auth_check.php';

check_role(['admin']);

if (isset($_GET['student_id']) && isset($_GET['course_id'])) {
    $s_id = (int)$_GET['student_id'];
    $c_id = (int)$_GET['course_id'];

    $sql = "DELETE FROM enrollments WHERE student_id = $s_id AND course_id = $c_id";

    if ($conn->query($sql)) {
        $_SESSION['success_message'] = "Enrollment cancelled successfully.";
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
}

header("Location: enrollments.php");
exit();
?>