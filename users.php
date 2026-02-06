<?php
$page_title = 'Manage Users';
include 'includes/header.php'; 
check_role(['admin']); 

$filter_role = isset($_GET['role']) ? $_GET['role'] : 'all';

$sql = "SELECT * FROM users";

if ($filter_role == 'student') {
    $sql .= " WHERE role = 'student'";
} elseif ($filter_role == 'instructor') {
    $sql .= " WHERE role = 'instructor'";
} elseif ($filter_role == 'admin') {
    $sql .= " WHERE role = 'admin'";
}

$sql .= " ORDER BY id DESC"; 

$result = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>👥 Manage Users</h2>
    <a href="add_user.php" class="btn btn-success">➕ Add New User</a>
</div>

<div class="mb-4">
    <div class="btn-group shadow-sm" role="group">
        <a href="users.php" class="btn btn-outline-primary <?php echo ($filter_role == 'all') ? 'active' : ''; ?>">
            All Users
        </a>
        <a href="users.php?role=student" class="btn btn-outline-primary <?php echo ($filter_role == 'student') ? 'active' : ''; ?>">
            🎓 Students
        </a>
        <a href="users.php?role=instructor" class="btn btn-outline-primary <?php echo ($filter_role == 'instructor') ? 'active' : ''; ?>">
            👨‍🏫 Instructors
        </a>
        <a href="users.php?role=admin" class="btn btn-outline-primary <?php echo ($filter_role == 'admin') ? 'active' : ''; ?>">
            ⚙️ Admins
        </a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Username / Code</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                            <td>
                                <?php 
                                    $badge = 'secondary';
                                    if ($row['role'] == 'admin') $badge = 'danger';
                                    elseif ($row['role'] == 'instructor') $badge = 'warning text-dark';
                                    elseif ($row['role'] == 'student') $badge = 'success';
                                ?>
                                <span class="badge bg-<?php echo $badge; ?>"><?php echo ucfirst($row['role']); ?></span>
                            </td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No users found in this category.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>