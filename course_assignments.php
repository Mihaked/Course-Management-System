<?php
$page_title = 'Course Assignments';
include 'includes/header.php'; 
check_role(['student']); 

$course_id = (int)$_GET['course_id'];
$student_id = $_SESSION['user_id'];
if (isset($_POST['submit_solution'])) {
    $assignment_id = (int)$_POST['assignment_id'];
    
    $target_dir = "../uploads/solutions/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
    
    $file_name = time() . "_" . $student_id . "_" . basename($_FILES["solution_file"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["solution_file"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO submissions (assignment_id, student_id, file_path) 
                VALUES ($assignment_id, $student_id, '$target_file')";
        $conn->query($sql);
        echo "<script>alert('Solution uploaded successfully!');</script>";
    }
}

$assignments = $conn->query("SELECT * FROM assignments WHERE course_id=$course_id ORDER BY id DESC");
?>

<h2 class="mb-4">📝 Assignments / Sheets</h2>

<div class="row">
    <?php while($assign = $assignments->fetch_assoc()): ?>
        <div class="col-md-12 mb-4">
            <div class="card card-custom border-left-primary shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        
                        <div class="col-md-6">
                            <h4 class="text-primary"><?php echo htmlspecialchars($assign['title']); ?></h4>
                            <p class="text-muted"><?php echo htmlspecialchars($assign['description']); ?></p>
                            <a href="<?php echo htmlspecialchars($assign['file_path']); ?>" class="btn btn-sm btn-info text-white" target="_blank">
                                ⬇ Download Sheet
                            </a>
                        </div>

                        <div class="col-md-6 border-start">
                            <h5>📤 Upload Your Solution</h5>
                            
                           <?php
$check_sub = $conn->query("SELECT * FROM submissions WHERE student_id=$student_id AND assignment_id=".$assign['id']);

if($check_sub->num_rows > 0) {
    $my_sub = $check_sub->fetch_assoc();
    
    echo "<div class='alert alert-success py-1 mb-2'>✅ Submitted on: " . $my_sub['submission_date'] . "</div>";
    
    if ($my_sub['mark'] !== NULL) {
        echo "<div class='card bg-light mb-2 border-success'>";
        echo "<div class='card-body py-2 px-3'>";
        echo "<strong>🎓 Grade: </strong> <span class='badge bg-success fs-6'>" . $my_sub['mark'] . " / 100</span><br>";
        echo "<strong>📝 Feedback: </strong> " . htmlspecialchars($my_sub['feedback']);
        echo "</div></div>";
    } else {
        echo "<div class='text-muted mb-2'><small>⏳ Waiting for grading...</small></div>";
    }

    echo "<a href='".$my_sub['file_path']."' target='_blank' class='btn btn-sm btn-outline-primary'>View My File</a>";

} else {
?>
    <form method="POST" enctype="multipart/form-data" class="d-flex gap-2">
        <input type="hidden" name="assignment_id" value="<?php echo $assign['id']; ?>">
        <input type="file" name="solution_file" class="form-control form-control-sm" required>
        <button type="submit" name="submit_solution" class="btn btn-sm btn-success">Submit</button>
    </form>
<?php 
}
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>