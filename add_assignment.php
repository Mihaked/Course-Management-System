<?php
$page_title = 'Add Assignment';
include 'includes/header.php'; 
check_role(['instructor']); 

if (!isset($_GET['course_id'])) { header("Location: index.php"); exit(); }
$course_id = (int)$_GET['course_id'];

if (isset($_POST['upload_sheet'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $desc = $conn->real_escape_string($_POST['description']);
    
    $target_dir = "../uploads/sheets/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
    
    $file_name = time() . "_" . basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO assignments (course_id, title, description, file_path) 
                VALUES ($course_id, '$title', '$desc', '$target_file')";
        if ($conn->query($sql)) {
            $success = "Sheet uploaded successfully!";
        }
    } else {
        $error = "Failed to upload file.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header bg-primary text-white">📄 Upload New Sheet</div>
            <div class="card-body">
                <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Sheet Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description (Optional)</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Sheet File (PDF/Doc)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <button type="submit" name="upload_sheet" class="btn btn-primary w-100">Upload Sheet</button>
                </form>
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary mt-3">Back to Courses</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>