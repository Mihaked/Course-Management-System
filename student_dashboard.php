<?php
$page_title = 'Student Dashboard';
include 'includes/header.php';
check_role(['student']);

$student_id = $_SESSION['user_id'];
$valid_levels = [1, 2, 3, 4];
$valid_semesters = [1, 2];

if (isset($_POST['save_track'])) {
    $level = (int)$_POST['level'];
    $semester = (int)$_POST['semester'];

    if (!in_array($level, $valid_levels, true) || !in_array($semester, $valid_semesters, true)) {
        $_SESSION['error_message'] = "Please choose a valid level and semester.";
        header("Location: student_dashboard.php?edit=1");
        exit();
    }

    $existing = $conn->query("SELECT student_id FROM student_profiles WHERE student_id=$student_id");
    if ($existing && $existing->num_rows > 0) {
        $conn->query("UPDATE student_profiles SET level=$level, semester=$semester WHERE student_id=$student_id");
    } else {
        $conn->query("INSERT INTO student_profiles (student_id, level, semester) VALUES ($student_id, $level, $semester)");
    }

    $_SESSION['success_message'] = "Your level and semester have been saved.";
    header("Location: student_dashboard.php");
    exit();
}

$profile = $conn->query("SELECT level, semester FROM student_profiles WHERE student_id=$student_id")->fetch_assoc();
$edit_mode = isset($_GET['edit']) || !$profile;

$level_label = $profile ? 'Level ' . (int)$profile['level'] : null;
$semester_label = $profile ? 'Semester ' . (int)$profile['semester'] : null;

$courses = [];
if ($profile) {
    $level = (int)$profile['level'];
    $semester = (int)$profile['semester'];
    $sql = "SELECT courses.*, users.full_name, users.username
            FROM courses
            LEFT JOIN users ON courses.instructor_id = users.id
            WHERE courses.level = $level AND courses.semester = $semester
            ORDER BY courses.title";
    $result = $conn->query($sql);
    if ($result) {
        $courses = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">🎓 Student Dashboard</h2>
        <p class="text-muted mb-0">Choose your academic level and semester to access materials and exams.</p>
    </div>
    <?php if ($profile): ?>
        <a class="btn btn-outline-primary" href="student_dashboard.php?edit=1">Change Level/Semester</a>
    <?php endif; ?>
</div>

<?php if ($edit_mode): ?>
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <div class="card card-custom">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Select Your Level & Semester</h5>
                </div>
                <div class="card-body">
                    <form method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Level</label>
                            <select name="level" class="form-select" required>
                                <option value="" disabled <?php echo $profile ? '' : 'selected'; ?>>Choose level</option>
                                <?php foreach ($valid_levels as $lvl): ?>
                                    <option value="<?php echo $lvl; ?>" <?php echo ($profile && (int)$profile['level'] === $lvl) ? 'selected' : ''; ?>>
                                        Level <?php echo $lvl; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="" disabled <?php echo $profile ? '' : 'selected'; ?>>Choose semester</option>
                                <?php foreach ($valid_semesters as $sem): ?>
                                    <option value="<?php echo $sem; ?>" <?php echo ($profile && (int)$profile['semester'] === $sem) ? 'selected' : ''; ?>>
                                        Semester <?php echo $sem; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="save_track" class="btn btn-success">Save Selection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($profile): ?>
    <div class="card card-custom mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Current Track</h5>
                <p class="mb-0 text-muted"><?php echo htmlspecialchars($level_label); ?> • <?php echo htmlspecialchars($semester_label); ?></p>
            </div>
            <span class="badge bg-primary">Active</span>
        </div>
    </div>

    <h4 class="mb-3">📚 Courses & Materials</h4>
    <div class="row">
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <?php
                    $instructor = !empty($course['full_name']) ? $course['full_name'] : $course['username'];
                    if (empty($instructor)) {
                        $instructor = 'Not Assigned';
                    }
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-custom h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3">
                                <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($course['course_code']); ?></span>
                                <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($course['title']); ?></h5>
                                <p class="text-muted mb-0">👨‍🏫 <?php echo htmlspecialchars($instructor); ?></p>
                            </div>
                            <p class="text-muted flex-grow-1"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                            <a href="view_course.php?course_id=<?php echo $course['id']; ?>" class="btn btn-dark mt-auto">Open Materials</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning">No courses found for this level and semester yet.</div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
