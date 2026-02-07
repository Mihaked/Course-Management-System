<?php
session_start();
include 'includes/db_connection.php';

if (!isset($_POST['register'])) {
    header("Location: register.php");
    exit();
}

$full_name = $conn->real_escape_string(trim($_POST['full_name']));
$username = $conn->real_escape_string(trim($_POST['username']));
$student_code = isset($_POST['student_code']) ? $conn->real_escape_string(trim($_POST['student_code'])) : '';
$password = $_POST['password'];

if ($full_name === '' || $username === '' || $password === '') {
    $_SESSION['error'] = "All required fields must be filled.";
    header("Location: register.php");
    exit();
}

$check = $conn->query("SELECT id FROM users WHERE username = '$username'");
if ($check && $check->num_rows > 0) {
    $_SESSION['error'] = "Username already exists. Please choose another.";
    header("Location: register.php");
    exit();
}

if (!empty($student_code)) {
    $check_code = $conn->query("SELECT id FROM users WHERE student_code = '$student_code'");
    if ($check_code && $check_code->num_rows > 0) {
        $_SESSION['error'] = "Student code already exists. Please verify your code.";
        header("Location: register.php");
        exit();
    }
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, full_name, student_code, password, role)
        VALUES ('$username', '$full_name', " . (!empty($student_code) ? "'$student_code'" : "NULL") . ", '$hashed_password', 'student')";

if ($conn->query($sql)) {
    $_SESSION['success_message'] = "Registration successful! You can now log in.";
    header("Location: register.php");
    exit();
}

$_SESSION['error'] = "Registration failed: " . $conn->error;
header("Location: register.php");
exit();
?>
