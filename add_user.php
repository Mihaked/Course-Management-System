<?php
$page_title = 'Add User';
include 'includes/header.php'; 
check_role(['admin']); 
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">➕ Add New User</h4>
            </div>
            <div class="card-body">
                
                <form action="add_user_process.php" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
    <label class="form-label">Full Name (Real Name)</label>
    <input type="text" name="full_name" class="form-control" placeholder="e.g. Ahmed Mohamed Ali">
</div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="" selected disabled>Select Role</option>
                            <option value="student">Student</option>
                            <option value="instructor">Instructor</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="add_user" class="btn btn-success">Save User</button>
                    </div>
                </form>

            </div>
        </div>
        <div class="text-center mt-3">
            <a href="users.php" class="text-decoration-none">← Back to Users</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>