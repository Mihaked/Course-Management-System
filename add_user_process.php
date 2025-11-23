<?php
session_start();
include '../includes/db_connection.php';
include '../includes/activity_stack.php'; 

if (!isset($_POST['add_user'])) {
    header("Location: users.php");
    exit();
}

$username = $conn->real_escape_string($_POST['username']);
$password = $_POST['password']; 
$role = $conn->real_escape_string($_POST['role']);

$full_name = $conn->real_escape_string($_POST['full_name']);

$student_code = !empty($_POST['student_code']) ? "'" . $conn->real_escape_string($_POST['student_code']) . "'" : "NULL";

$check = $conn->query("SELECT id FROM users WHERE username='$username'");
if ($check->num_rows > 0) {
    $_SESSION['error_message'] = "Error: Username '$username' already exists!";
    header("Location: add_user.php");
    exit();
}

$sql = "INSERT INTO users (username, password, role, student_code, full_name) 
        VALUES ('$username', '$password', '$role', $student_code, '$full_name')";

if ($conn->query($sql)) {
    
    push_activity("Added new User: $username ($role) - Name: $full_name"); 

    $_SESSION['success_message'] = "User added successfully!";
    header("Location: users.php");
} else {
    $_SESSION['error_message'] = "Database Error: " . $conn->error;
    header("Location: add_user.php");
}
exit();
?>