<?php
$page_title = 'Manage Courses';
include '../includes/header.php'; 
check_role(['admin']); 

$sql = "SELECT courses.*, 
               users.username, 
               users.full_name 
        FROM courses 
        LEFT JOIN users ON courses.instructor_id = users.id
        ORDER BY courses.id DESC";

$result = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📚 Manage Courses</h2>
    <a href="add_course.php" class="btn btn-success">➕ Add New Course</a>
</div>

<div class="card card-custom">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Instructor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        
                        <?php 
                            
                            $instr_display = !empty($row['full_name']) ? $row['full_name'] : $row['username'];
                            
                            if (empty($instr_display)) {
                                $instr_display = "Not Assigned";
                            }
                        ?>

                        <tr>
                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($row['course_code']); ?></span></td>
                            
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            
                            <td>
                                👨‍🏫 <?php echo htmlspecialchars($instr_display); ?>
                            </td>
                            
                            <td>
                                <a href="edit_course.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete_course.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will delete the course and all its materials!');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No courses found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>