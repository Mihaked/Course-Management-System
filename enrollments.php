<?php
$page_title = 'Manage Enrollments';
include '../includes/header.php'; 
check_role(['admin']); 

$sql = "SELECT enrollments.enrollment_date, 
               users.username AS student_name, users.id AS student_id,
               courses.title AS course_name, courses.id AS course_id
        FROM enrollments
        JOIN users ON enrollments.student_id = users.id
        JOIN courses ON enrollments.course_id = courses.id
        ORDER BY enrollments.enrollment_date DESC";

$result = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>🎓 Manage Enrollments</h2>
    <a href="add_enrollment.php" class="btn btn-success">➕ Enroll Student</a>
</div>

<div class="card card-custom">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Student Name</th>
                        <th>Course Title</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>👤 <?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td>📚 <?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td><?php echo $row['enrollment_date']; ?></td>
                            <td>
                                <a href="delete_enrollment.php?student_id=<?php echo $row['student_id']; ?>&course_id=<?php echo $row['course_id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to un-enroll this student?');">Un-enroll</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No enrollments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>