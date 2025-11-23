<?php
$page_title = 'Enroll Student';
include '../includes/header.php'; 
check_role(['admin']); 

$students = $conn->query("SELECT id, username FROM users WHERE role='student'");

$courses = $conn->query("SELECT id, title, course_code FROM courses");
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">➕ Enroll Student in Course</h4>
            </div>
            <div class="card-body">
                
                <form action="add_enrollment_process.php" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label">Select Student</label>
                        <select name="student_id" class="form-select" required>
                            <option value="" selected disabled>Choose a student...</option>
                            <?php while($std = $students->fetch_assoc()): ?>
                                <option value="<?php echo $std['id']; ?>">
                                    <?php echo $std['username']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Select Course</label>
                        <select name="course_id" class="form-select" required>
                            <option value="" selected disabled>Choose a course...</option>
                            <?php while($crs = $courses->fetch_assoc()): ?>
                                <option value="<?php echo $crs['id']; ?>">
                                    <?php echo $crs['course_code'] . ' - ' . $crs['title']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="enroll" class="btn btn-success">Enroll Now</button>
                    </div>
                </form>

            </div>
        </div>
        <div class="text-center mt-3">
            <a href="enrollments.php" class="text-decoration-none">← Back to Enrollments</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>