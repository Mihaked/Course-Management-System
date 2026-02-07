<?php
$page_title = 'Manage Materials';
include 'includes/header.php'; 
check_role(['instructor']); 

if (!isset($_GET['course_id'])) {
    header("Location: index.php");
    exit();
}

$course_id = (int)$_GET['course_id'];
$instructor_id = $_SESSION['user_id'];

$check = $conn->query("SELECT title FROM courses WHERE id=$course_id AND instructor_id=$instructor_id");
if ($check->num_rows == 0) {
    echo "<div class='alert alert-danger'>Access Denied! You do not teach this course.</div>";
    include 'includes/footer.php';
    exit();
}
$course = $check->fetch_assoc();


if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    $file_query = $conn->query("SELECT file_path FROM materials WHERE id=$delete_id");
    if ($file_query->num_rows > 0) {
        $file_data = $file_query->fetch_assoc();
        $file_to_delete = $file_data['file_path'];
        
        if (file_exists($file_to_delete)) {
            unlink($file_to_delete);
        }
        
        $conn->query("DELETE FROM materials WHERE id=$delete_id");
        $success_msg = "Material deleted successfully.";
    }
}


if (isset($_POST['add_material'])) {
    $title = $conn->real_escape_string($_POST['title']);
    
    $target_dir = "../uploads/materials/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    $file_extension = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
    $new_file_name = time() . "_" . rand(1000, 9999) . "." . $file_extension;
    $target_file = $target_dir . $new_file_name;
    
    $allowed_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'png'];
    
    if (in_array($file_extension, $allowed_types)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO materials (course_id, title, file_path) VALUES ($course_id, '$title', '$target_file')";
            if ($conn->query($sql)) {
                $success_msg = "File uploaded successfully!";
            } else {
                $error_msg = "Database Error: " . $conn->error;
            }
        } else {
            $error_msg = "Failed to move uploaded file.";
        }
    } else {
        $error_msg = "Invalid file type. Only PDF, Word, PowerPoint, and Images are allowed.";
    }
}

$materials = $conn->query("SELECT * FROM materials WHERE course_id=$course_id ORDER BY id DESC");
?>

<div class="row">
    <div class="col-md-4">
        <div class="card card-custom mb-3 shadow-sm">
            <div class="card-header bg-success text-white fw-bold">
                <i class="bi bi-cloud-upload me-2"></i> Upload Material
            </div>
            <div class="card-body">
                <?php if(isset($success_msg)) echo "<div class='alert alert-success py-2 small'>$success_msg</div>"; ?>
                <?php if(isset($error_msg)) echo "<div class='alert alert-danger py-2 small'>$error_msg</div>"; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Material Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Lecture 1 PDF" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select File</label>
                        <input type="file" name="file" class="form-control" required>
                        <div class="form-text">PDF, Word, PowerPoint (Max: 50MB)</div>
                    </div>
                    <button type="submit" name="add_material" class="btn btn-success w-100">Upload</button>
                </form>
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary w-100">Back to Courses</a>
    </div>

    <div class="col-md-8">
        <h4 class="mb-3 text-dark">
            <i class="bi bi-folder2-open text-warning me-2"></i> Materials for: <?php echo htmlspecialchars($course['title']); ?>
        </h4>
        
        <div class="card card-custom shadow-sm">
            <div class="card-body p-0">
                <?php if ($materials->num_rows > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php while($mat = $materials->fetch_assoc()): ?>
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-text fs-3 text-primary me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($mat['title']); ?></h6>
                                    <small class="text-muted" style="font-size: 0.8rem;">
                                        <?php echo basename($mat['file_path']); ?>
                                    </small>
                                </div>
                            </div>
                            
                            <div class="btn-group">
                                <a href="<?php echo htmlspecialchars($mat['file_path']); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                    Download
                                </a>
                                <a href="manage_materials.php?course_id=<?php echo $course_id; ?>&delete_id=<?php echo $mat['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Are you sure you want to delete this file?');">
                                    Delete
                                </a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        No materials uploaded yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>