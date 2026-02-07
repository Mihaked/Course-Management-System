<?php
$page_title = 'Course Materials';
include 'includes/header.php'; 
check_role(['student']); 

if (!isset($_GET['course_id'])) { header("Location: index.php"); exit(); }
$course_id = (int)$_GET['course_id'];
$student_id = $_SESSION['user_id'];

$course = $conn->query("SELECT * FROM courses WHERE id=$course_id")->fetch_assoc();
if (!$course) {
    echo "<div class='alert alert-danger'>Course not found.</div>";
    include 'includes/footer.php';
    exit();
}

$check = $conn->query("SELECT * FROM enrollments WHERE student_id=$student_id AND course_id=$course_id");
$profile = $conn->query("SELECT level, semester FROM student_profiles WHERE student_id=$student_id")->fetch_assoc();
$matches_track = $profile && (int)$profile['level'] === (int)$course['level'] && (int)$profile['semester'] === (int)$course['semester'];

if ($check->num_rows == 0 && !$matches_track) {
    echo "<div class='alert alert-danger'>Access Denied.</div>";
    include 'includes/footer.php';
    exit();
}
$materials = $conn->query("SELECT * FROM materials WHERE course_id=$course_id ORDER BY id DESC");
?>

<style>
    .enter-animation {
        opacity: 0;
        transform: translateY(50px);
        animation: slideUpFade 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }

    @keyframes slideUpFade {
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="enter-animation">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="student_dashboard.php" class="text-dark text-decoration-none">My Courses</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($course['course_code']); ?></li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark" style="text-shadow: 0 2px 10px rgba(255,255,255,0.5);">
                📖 <?php echo htmlspecialchars($course['title']); ?>
            </h2>
        </div>
        
        <a href="course_assignments.php?course_id=<?php echo $course_id; ?>" class="btn btn-warning fw-bold shadow-sm px-4 py-2 rounded-pill">
            Assignments / Sheets <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>

    <div class="card card-custom shadow-lg border-0">
        <div class="card-header bg-dark text-white py-3" style="border-radius: 15px 15px 0 0;">
            <h5 class="mb-0"><i class="bi bi-folder2-open me-2"></i> Lecture Materials</h5>
        </div>
        <div class="card-body p-0">
            <?php if ($materials->num_rows > 0): ?>
                <div class="list-group list-group-flush">
                    <?php while($mat = $materials->fetch_assoc()): ?>
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-2 rounded-circle me-3 shadow-sm">
                                <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($mat['title']); ?></h6>
                                <small class="text-muted">File: <?php echo htmlspecialchars(basename($mat['file_path'])); ?></small>
                            </div>
                        </div>
                        <a href="<?php echo htmlspecialchars($mat['file_path']); ?>" target="_blank" class="btn btn-dark btn-sm px-4 rounded-pill">
                            Download <i class="bi bi-download ms-1"></i>
                        </a>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                    No materials uploaded yet.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
