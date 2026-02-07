<?php
session_start();
include 'includes/db_connection.php'; 

if (isset($_POST['login'])) {

    $input = $conn->real_escape_string($_POST['username']); 
    $password = $_POST['password']; 
    
    $sql = "SELECT * FROM users WHERE username = '$input' OR student_code = '$input'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
            $db_password = $user['password']; 

        $is_valid_password = false;

        if ($password === $db_password) {
            $is_valid_password = true;
        }
        elseif (password_verify($password, $db_password)) {
            $is_valid_password = true;
        }

        if ($is_valid_password) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            $_SESSION['full_name'] = !empty($user['full_name']) ? $user['full_name'] : $user['username'];
            
            $role = $user['role'];
            if ($role === 'admin') {
                header("Location: dashboard.php");
            } elseif ($role === 'instructor') {
                header("Location: manage_materials.php");
            } elseif ($role === 'student') {
                header("Location: student_dashboard.php");
            }
            exit();

        } else {
            $_SESSION['error'] = "كلمة المرور غير صحيحة (Invalid Password)";
        }
    } else {
        $_SESSION['error'] = "اسم المستخدم أو الكود غير صحيح (User not found)";
    }
}

header("Location: index.php");
exit();
?>
