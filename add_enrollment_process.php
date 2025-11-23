<?php
session_start();
include '../includes/db_connection.php';
include '../includes/auth_check.php';

check_role(['admin']);

if (!isset($_POST['enroll'])) {
    header("Location: enrollments.php");
    exit();
}

$student_id = (int)$_POST['student_id'];
$course_id = (int)$_POST['course_id'];

$check = $conn->query("SELECT * FROM enrollments WHERE student_id=$student_id AND course_id=$course_id");

if ($check->num_rows > 0) {
    $_SESSION['error_message'] = "Error: Student is already enrolled in this course!";
    header("Location: add_enrollment.php");
    exit();
}

$sql = "INSERT INTO enrollments (student_id, course_id) VALUES ($student_id, $course_id)";

if ($conn->query($sql)) {
    $_SESSION['success_message'] = "Student enrolled successfully!";
    header("Location: enrollments.php");
} else {
    $_SESSION['error_message'] = "Database Error: " . $conn->error;
    header("Location: add_enrollment.php");
}
exit();
?>