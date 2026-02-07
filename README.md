# 🎓 Course Management System (CMS)

A comprehensive web-based Course Management System built from scratch using **PHP** and **MySQL**. This system facilitates seamless interaction between Admins, Instructors, and Students with a modern, responsive user interface.

## 🚀 Features

### 👤 Admin Panel
- **User Management:** Create, edit, and delete users (Students, Instructors, Admins).
- **Course Management:** Create courses and assign instructors.
- **Enrollment System:** Enroll students in specific courses.
- **Dashboard:** View system statistics and real-time activity logs.

### 👨‍🏫 Instructor Panel
- **Course Overview:** View assigned courses.
- **Material Management:** Upload and manage course materials (PDFs, Docs).
- **Assignments:** Create assignments and upload question sheets.
- **Grading:** View student submissions, grade them, and provide feedback.

### 🎓 Student Panel
- **My Courses:** View enrolled courses with a modern card layout.
- **Materials:** Download lecture notes and study materials.
- **Assignments:** Upload solutions for assignments.
- **Grades:** View marks and instructor feedback.
- **Profile:** Manage profile details.
- **Self Registration:** Students can create their own accounts and then choose their level/semester on first login.

## 🛠️ Technologies Used
- **Backend:** Native PHP
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, Bootstrap 5
- **Animation:** Particles.js, CSS Animations
- **Server:** Apache (XAMPP)

## ⚙️ Installation Guide

1. **Download:**
   Download the repository and extract it into your server folder (e.g., `htdocs` in XAMPP).

2. **Database Setup:**
   - Open `phpMyAdmin`.
   - Create a new database named `cms_db1`.
   - Import the `cms_db.sql` file found in the project folder.

3. **Configuration:**
   - Open `includes/db_connection.php`.
   - Ensure database credentials match your local setup (default user: `root`, password: empty).

4. **Run:**
   - Open your browser and navigate to `http://localhost/Course-Management-System`.

## ♻️ Updating an Existing Copy
If you already have an older copy of this project and want to add the new registration + level/semester flow:

1. Copy these **new files** into your existing project:
   - `register.php`
   - `register_process.php`
   - `student_dashboard.php`

2. Replace these **updated files** (they now include level/semester and redirect changes):
   - `index.php`
   - `add_course.php`
   - `add_course_process.php`
   - `courses.php`
   - `edit_course.php`
   - `view_course.php`
   - `includes/header.php`
   - `includes/db_connection.php`

3. Re-import the database schema from `cms_db.sql` (or manually add `level`, `semester`, and `student_profiles` if you prefer to keep your data).

---
### 👨‍💻 Developed by: **Mohamed Abd Elrady**
