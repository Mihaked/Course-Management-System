<?php
$page_title = 'Manage Assignments';
include '../includes/header.php'; 
check_role(['instructor']); 

if (!isset($_GET['course_id'])) {
    header("Location: index.php");
    exit();
}

$course_id = (int)$_GET['course_id'];
$instructor_id = $_SESSION['user_id'];

$check = $conn->query("SELECT title FROM courses WHERE id=$course_id AND instructor_id=$instructor_id");
if ($check->num_rows == 0) {
    echo "<div class='alert alert-danger'>Access Denied.</div>";
    include '../includes/footer.php';
    exit();
}
$course = $check->fetch_assoc();

$assignments = $conn->query("SELECT * FROM assignments WHERE course_id=$course_id ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📝 Assignments for: <?php echo htmlspecialchars($course['title']); ?></h3>
    <div>
        <a href="add_assignment.php?course_id=<?php echo $course_id; ?>" class="btn btn-success">+ Add New Sheet</a>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Sheet File</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($assignments->num_rows > 0): ?>
                        <?php while($assign = $assignments->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($assign['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($assign['description']); ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($assign['file_path']); ?>" class="btn btn-sm btn-info text-white" target="_blank">View Sheet</a>
                            </td>
                            <td>
                                <a href="view_solutions.php?assignment_id=<?php echo $assign['id']; ?>" class="btn btn-sm btn-warning text-dark">
                                    📥 View Solutions
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No assignments uploaded yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>