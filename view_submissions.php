<?php
$page_title = 'Student Submissions';
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

$sql = "SELECT submissions.*, users.username 
        FROM submissions 
        JOIN users ON submissions.student_id = users.id 
        WHERE submissions.course_id = $course_id 
        ORDER BY submissions.id DESC";
$result = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📥 Submissions for: <?php echo htmlspecialchars($course['title']); ?></h3>
    <a href="index.php" class="btn btn-secondary">Back to Courses</a>
</div>

<div class="card card-custom">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Student Name</th>
                        <th>Submission Date</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>👤 <strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                            <td><?php echo $row['submission_date']; ?></td>
                            <td>
                                <a href="<?php echo $row['file_path']; ?>" class="btn btn-sm btn-primary" target="_blank">Download File</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">No submissions yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>