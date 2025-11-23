<?php
session_start();
include '../includes/db_connection.php';
include '../includes/auth_check.php';

check_role(['admin']);

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $sql = "DELETE FROM courses WHERE id = $id";

    if ($conn->query($sql)) {
        $_SESSION['success_message'] = "Course deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting course: " . $conn->error;
    }
} else {
    $_SESSION['error_message'] = "No Course ID provided.";
}

header("Location: courses.php");
exit();
?>