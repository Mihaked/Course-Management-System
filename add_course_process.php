<?php
session_start();
include 'includes/db_connection.php';

if (!isset($_POST['add_course'])) {
    header("Location: courses.php");
    exit();
}

$code = $conn->real_escape_string($_POST['code']);
$title = $conn->real_escape_string($_POST['title']);
$desc = $conn->real_escape_string($_POST['description']);
$level = (int)$_POST['level'];
$semester = (int)$_POST['semester'];
$instructor_id = (int)$_POST['instructor_id'];

$valid_levels = [1, 2, 3, 4];
$valid_semesters = [1, 2];

if (!in_array($level, $valid_levels, true) || !in_array($semester, $valid_semesters, true)) {
    $_SESSION['error_message'] = "Please select a valid level and semester.";
    header("Location: add_course.php");
    exit();
}

$check = $conn->query("SELECT * FROM courses WHERE course_code='$code'");if ($check->num_rows > 0) {
    $_SESSION['error_message'] = "Course Code already exists!";
    header("Location: add_course.php");
    exit();
}

$sql = "INSERT INTO courses (course_code, title, description, level, semester, instructor_id) 
        VALUES ('$code', '$title', '$desc', $level, $semester, $instructor_id)";

if ($conn->query($sql)) {
    $_SESSION['success_message'] = "Course added successfully!";
    header("Location: courses.php");
} else {
    $_SESSION['error_message'] = "Error: " . $conn->error;
    header("Location: add_course.php");
}
exit();
?>
