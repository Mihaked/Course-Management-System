<?php
$page_title = 'Add Course';
include 'includes/header.php'; 
check_role(['admin']); 

$instructors = $conn->query("SELECT id, username, full_name FROM users WHERE role='instructor'");
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom shadow">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">➕ Add New Course</h4>
            </div>
            <div class="card-body">
                
                <form action="add_course_process.php" method="POST">
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Course Code (e.g., CS101)</label>
                            <input type="text" name="code" class="form-control" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Course Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Level</label>
                            <select name="level" class="form-select" required>
                                <option value="" selected disabled>Select Level</option>
                                <option value="1">Level 1</option>
                                <option value="2">Level 2</option>
                                <option value="3">Level 3</option>
                                <option value="4">Level 4</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="" selected disabled>Select Semester</option>
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Assign Instructor</label>
                        <select name="instructor_id" class="form-select" required>
                            <option value="" selected disabled>Select Instructor</option>
                            <?php 
                            if ($instructors->num_rows > 0) {
                                while($inst = $instructors->fetch_assoc()) {
                                    $display_name = !empty($inst['full_name']) ? $inst['full_name'] : $inst['username'];
                                    echo "<option value='" . $inst['id'] . "'>" . htmlspecialchars($display_name) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="add_course" class="btn btn-success">Save Course</button>
                    </div>
                </form>

            </div>
        </div>
        <div class="text-center mt-3">
            <a href="courses.php" class="btn btn-outline-secondary">← Back to Courses</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
