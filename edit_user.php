<?php
$page_title = 'Edit User';
include '../includes/header.php';
check_role(['admin']);

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$id = (int)$_GET['id'];

if (isset($_POST['update_user'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $role = $_POST['role'];
    
    $password_query = "";
    if (!empty($_POST['password'])) {
        $password = $_POST['password']; 
       
        $password_query = ", password='$password'";
    }

    $sql = "UPDATE users SET username='$username', role='$role' $password_query WHERE id=$id";

    if ($conn->query($sql)) {
        $_SESSION['success_message'] = "User updated successfully!";
        echo "<script>window.location.href='users.php';</script>";
        exit();
    } else {
        $error = "Error updating user: " . $conn->error;
    }
}

$sql_get = "SELECT * FROM users WHERE id=$id";
$result = $conn->query($sql_get);

if ($result->num_rows == 0) {
    echo "<div class='alert alert-danger'>User not found.</div>";
    include '../includes/footer.php';
    exit();
}

$user = $result->fetch_assoc();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">✏️ Edit User: <?php echo htmlspecialchars($user['username']); ?></h4>
            </div>
            <div class="card-body">
                
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

                <form method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                        <small class="text-muted">Only type here if you want to change it.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="student" <?php if($user['role']=='student') echo 'selected'; ?>>Student</option>
                            <option value="instructor" <?php if($user['role']=='instructor') echo 'selected'; ?>>Instructor</option>
                            <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="update_user" class="btn btn-primary">Save Changes</button>
                        <a href="users.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>