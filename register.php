<?php
session_start();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') header("Location: dashboard.php");
    elseif ($_SESSION['role'] === 'instructor') header("Location: manage_materials.php");
    elseif ($_SESSION['role'] === 'student') header("Location: student_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration | Course Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ecf0f1;
        }

        #particles-js {
            position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: 0;
            background-color: #eef2f3;
        }

        .register-card {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 1);
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            border: 1px solid #d1d1d1;
            width: 100%;
            max-width: 520px;
            text-align: center;
        }

        .register-icon {
            background: #2c3e50;
            width: 80px; height: 80px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: -80px auto 20px auto;
            color: white; font-size: 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border: 3px solid #fff;
        }

        .form-control {
            border-radius: 25px;
            padding: 12px 20px;
            background-color: #f9f9f9;
            border: 2px solid #ccc;
            color: #333;
            font-weight: 500;
        }
        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: none;
            background-color: #fff;
        }

        .btn-register {
            background: #2c3e50;
            color: white;
            border-radius: 25px;
            padding: 12px;
            width: 100%;
            margin-top: 10px;
            font-weight: bold;
            transition: 0.3s;

            border: 2px solid #000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-register:hover {
            background: #857b7bff;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(149, 149, 149, 0.5);
        }
    </style>
</head>
<body>

    <div id="particles-js"></div>

    <div class="register-card">
        <div class="register-icon"><i class="bi bi-person-plus-fill"></i></div>
        <h3 class="mb-3 fw-bold" style="color: #333;">Create Your Student Account</h3>
        <p class="text-muted mb-4">Register once, then choose your level and semester.</p>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success py-2 mb-3" style="font-size: 0.9rem; border-radius: 15px;">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger py-2 mb-3" style="font-size: 0.9rem; border-radius: 15px;">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="register_process.php" method="POST" autocomplete="off">
            <div class="mb-3 text-start">
                <label class="form-label ms-2 text-muted small">Full Name</label>
                <input type="text" class="form-control" name="full_name" placeholder="Your real name" required>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label ms-2 text-muted small">Username / Code</label>
                <input type="text" class="form-control" name="username" placeholder="Choose a username" required>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label ms-2 text-muted small">Student Code (Optional)</label>
                <input type="text" class="form-control" name="student_code" placeholder="Optional student code">
            </div>
            <div class="mb-3 text-start">
                <label class="form-label ms-2 text-muted small">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Create a password" required>
            </div>

            <button type="submit" name="register" class="btn btn-register">Register Now</button>
        </form>

        <div class="mt-3">
            <a href="index.php" class="text-decoration-none fw-semibold">Already have an account? Login</a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 40,
                    "density": { "enable": true, "value_area": 800 }
                },
                "color": { "value": "#333333" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.5, "random": false },
                "size": { "value": 3, "random": true },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#333333",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 2,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "grab"
                    },
                    "onclick": { "enable": true, "mode": "push" },
                    "resize": true
                },
                "modes": {
                    "grab": { "distance": 140, "line_linked": { "opacity": 1 } }
                }
            },
            "retina_detect": false
        });
    </script>
</body>
</html>
