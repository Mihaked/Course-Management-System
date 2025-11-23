<?php

$page_title = 'Dashboard';

include '../includes/header.php'; 

check_role(['admin']); 


$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_students = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='student'")->fetch_assoc()['total'];
$total_instructors = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='instructor'")->fetch_assoc()['total'];
$total_courses = $conn->query("SELECT COUNT(*) AS total FROM courses")->fetch_assoc()['total'];

$stats = [
    ['title' => 'Total Users', 'value' => $total_users, 'color' => 'primary', 'link' => 'users.php'],
    ['title' => 'Total Students', 'value' => $total_students, 'color' => 'info', 'link' => 'users.php?role=student'],
    ['title' => 'Total Instructors', 'value' => $total_instructors, 'color' => 'warning', 'link' => 'users.php?role=instructor'],
    ['title' => 'Total Courses', 'value' => $total_courses, 'color' => 'success', 'link' => 'courses.php'],
];
?>

<h2 class="mb-4">System Overview</h2>

<div class="row">
    <?php foreach ($stats as $stat): ?>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-<?php echo $stat['color']; ?> card-custom h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <h1 class="display-4 fw-bold"><?php echo $stat['value']; ?></h1>
                    <p class="card-text fs-5"><?php echo $stat['title']; ?></p>
                </div>
            </div>
            <a href="<?php echo $stat['link']; ?>" class="card-footer text-white text-center text-decoration-none small-box-footer">
                More info &rarr;
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<h3 class="mt-5 mb-3">Recent Activities</h3>
<div class="card card-custom shadow-sm">
    <div class="card-body">
        <p class="text-muted">System is ready. Start managing users and courses.</p>
    </div>
</div>

<?php 
include '../includes/activity_stack.php';
$activities = get_stack_activities(); 
?>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">🕹️ Activity Log (Stack Implementation - LIFO)</h5>
                <span class="badge bg-secondary"><?php echo count($activities); ?> Items</span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $log): ?>
                            <li class="list-group-item">
                                <i class="bi bi-clock-history text-primary me-2"></i> 
                                <?php echo htmlspecialchars($log); ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-center text-muted">Stack is empty. No actions yet.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<br>

<?php 
include '../includes/footer.php'; 
?>