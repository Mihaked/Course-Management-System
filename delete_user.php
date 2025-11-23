<?php
include '../includes/db_connection.php';
include '../includes/auth_check.php';

check_role(['admin']);

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if ($id == $_SESSION['user_id']) {
        $_SESSION['error_message'] = "You cannot delete your own account!";
        header("Location: users.php");
        exit();
    }

    try {
        $sql = "DELETE FROM users WHERE id = $id";
        
        if ($conn->query($sql)) {
            $_SESSION['success_message'] = "User deleted successfully.";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) {
            $_SESSION['error_message'] = "Cannot delete this user! They are assigned to a Course or have Enrollments. Please delete their related data first.";
        } else {
            $_SESSION['error_message'] = "Database Error: " . $e->getMessage();
        }
    }
}

header("Location: users.php");
exit();
?>