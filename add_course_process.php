<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_POST['add_course'])) {
    header("Location: courses.php");
    exit();
}

$code = $conn->real_escape_string($_POST['code']);
$title = $conn->real_escape_string($_POST['title']);
$desc = $conn->real_escape_string($_POST['description']);
$instructor_id = (int)$_POST['instructor_id'];

$check = $conn->query("SELECT * FROM courses WHERE course_code='$code'");if ($check->num_rows > 0) {
    $_SESSION['error_message'] = "Course Code already exists!";
    header("Location: add_course.php");
    exit();
}

$sql = "INSERT INTO courses (course_code, title, description, instructor_id) 
        VALUES ('$code', '$title', '$desc', $instructor_id)";

if ($conn->query($sql)) {
    $_SESSION['success_message'] = "Course added successfully!";
    header("Location: courses.php");
} else {
    $_SESSION['error_message'] = "Error: " . $conn->error;
    header("Location: add_course.php");
}
exit();
?>