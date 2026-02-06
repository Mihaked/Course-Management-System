<?php
$page_title = 'View Solutions & Grade';
include 'includes/header.php'; 
check_role(['instructor']); 

$assignment_id = (int)$_GET['assignment_id'];

if (isset($_POST['save_grade'])) {
    $sub_id = (int)$_POST['submission_id'];
    $mark = (int)$_POST['mark'];
    $feedback = $conn->real_escape_string($_POST['feedback']);
    
    $sql = "UPDATE submissions SET mark=$mark, feedback='$feedback' WHERE id=$sub_id";
    if ($conn->query($sql)) {
        $success_msg = "Grade updated successfully!";
    }
}

$assign_info = $conn->query("SELECT * FROM assignments WHERE id=$assignment_id")->fetch_assoc();

$solutions = $conn->query("
    SELECT submissions.*, users.username 
    FROM submissions 
    JOIN users ON submissions.student_id = users.id 
    WHERE submissions.assignment_id = $assignment_id
");
?>

<h3>🎓 Grading: <?php echo htmlspecialchars($assign_info['title']); ?></h3>
<a href="manage_assignments.php?course_id=<?php echo $assign_info['course_id']; ?>" class="btn btn-secondary mb-3">Back</a>

<?php if(isset($success_msg)) echo "<div class='alert alert-success py-1'>$success_msg</div>"; ?>

<div class="card card-custom">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>File</th>
                        <th>Date</th>
                        <th width="40%">Grade & Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($solutions->num_rows > 0): ?>
                        <?php while($row = $solutions->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            
                            <td>
                                <a href="<?php echo htmlspecialchars($row['file_path']); ?>" class="btn btn-sm btn-info text-white" target="_blank">View File</a>
                            </td>
                            
                            <td><small><?php echo $row['submission_date']; ?></small></td>
                            
                            <td>
                                <form method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="submission_id" value="<?php echo $row['id']; ?>">
                                    
                                    <input type="number" name="mark" class="form-control form-control-sm" style="width: 70px;" placeholder="Mark" value="<?php echo $row['mark']; ?>" required>
                                    
                                    <input type="text" name="feedback" class="form-control form-control-sm" placeholder="Feedback..." value="<?php echo htmlspecialchars($row['feedback']); ?>">
                                    
                                    <button type="submit" name="save_grade" class="btn btn-sm btn-success">Save</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No solutions submitted yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>