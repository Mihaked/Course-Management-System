<?php
$page_title = 'Edit Course';
include '../includes/header.php';
check_role(['admin']);

if (!isset($_GET['id'])) {
    header("Location: courses.php");
    exit();
}

$id = (int)$_GET['id'];

if (isset($_POST['update_course'])) {
    $code = $conn->real_escape_string($_POST['code']);
    $title = $conn->real_escape_string($_POST['title']);
    $desc = $conn->real_escape_string($_POST['description']);
    $instructor_id = (int)$_POST['instructor_id'];

    $check = $conn->query("SELECT id FROM courses WHERE course_code='$code' AND id != $id");
    
    if ($check->num_rows > 0) {
        $error = "Error: Course Code '$code' already exists!";
    } else {
        $sql = "UPDATE courses SET 
                course_code='$code', 
                title='$title', 
                description='$desc', 
                instructor_id=$instructor_id 
                WHERE id=$id";

        if ($conn->query($sql)) {
            $_SESSION['success_message'] = "Course updated successfully!";
            echo "<script>window.location.href='courses.php';</script>";
            exit();
        } else {
            $error = "Error updating: " . $conn->error;
        }
    }
}

$course = $conn->query("SELECT * FROM courses WHERE id=$id")->fetch_assoc();
if (!$course) {
    echo "<div class='alert alert-danger'>Course not found.</div>";
    include '../includes/footer.php';
    exit();
}

$instructors = $conn->query("SELECT id, username FROM users WHERE role='instructor'");
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">✏️ Edit Course: <?php echo htmlspecialchars($course['title']); ?></h4>
            </div>
            <div class="card-body">
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($course['title']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($course['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Instructor</label>
                        <select name="instructor_id" class="form-select" required>
                            <?php while($inst = $instructors->fetch_assoc()): ?>
                                <option value="<?php echo $inst['id']; ?>" <?php if($inst['id'] == $course['instructor_id']) echo 'selected'; ?>>
                                    <?php echo $inst['username']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="update_course" class="btn btn-primary">Save Changes</button>
                        <a href="courses.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>