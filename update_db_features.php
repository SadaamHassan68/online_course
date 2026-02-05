<?php
// update_db_features.php
// Database Setup Script for Online Course Platform

$host = 'localhost';
$db   = 'onlinecourses';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Connect to MySQL server first to create DB if not exists
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Database
$sql = "CREATE DATABASE IF NOT EXISTS $db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select Database
$conn->select_db($db);

// SQL to create tables
$tables = [
    // Users Table
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('student', 'instructor', 'admin') DEFAULT 'student',
        avatar VARCHAR(255) DEFAULT 'default_avatar.png',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    // Courses Table
    "CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        instructor_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) DEFAULT 0.00,
        thumbnail VARCHAR(255),
        category VARCHAR(100),
        status ENUM('pending', 'approved', 'published', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE CASCADE
    )",

    // Sections (Modules) Table
    "CREATE TABLE IF NOT EXISTS sections (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        sort_order INT DEFAULT 0,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    )",

    // Lessons Table
    "CREATE TABLE IF NOT EXISTS lessons (
        id INT AUTO_INCREMENT PRIMARY KEY,
        section_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        type ENUM('video', 'text', 'quiz') NOT NULL,
        content TEXT, -- Video URL or Text Content
        attachment_url VARCHAR(255),
        sort_order INT DEFAULT 0,
        FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE
    )",

    // Enrollments Table
    "CREATE TABLE IF NOT EXISTS enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        progress DECIMAL(5, 2) DEFAULT 0.00,
        completed_at TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    )",

    // Lesson Progress Tracking
    "CREATE TABLE IF NOT EXISTS lesson_progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        enrollment_id INT NOT NULL,
        lesson_id INT NOT NULL,
        completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
        FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
    )",

    // Quiz Questions Table
    "CREATE TABLE IF NOT EXISTS quiz_questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        lesson_id INT NOT NULL,
        question TEXT NOT NULL,
        options JSON NOT NULL, -- Stored as JSON array
        correct_answer VARCHAR(255) NOT NULL,
        FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
    )",

    // Reviews Table
    "CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        rating INT CHECK (rating BETWEEN 1 AND 5),
        comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    )",

    // Payments Table
    "CREATE TABLE IF NOT EXISTS payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        transaction_id VARCHAR(255),
        payment_method VARCHAR(50),
        status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL
    )",
    
     // Certificates Table
    "CREATE TABLE IF NOT EXISTS certificates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        issue_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        pdf_url VARCHAR(255),
        certificate_code VARCHAR(100) UNIQUE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    )"
];

// Execute table creation
foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Insert Dummy Data for Testing
// 1. Admin User
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
$check_admin = "SELECT * FROM users WHERE email = 'admin@example.com'";
if ($conn->query($check_admin)->num_rows == 0) {
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('Admin User', 'admin@example.com', '$admin_pass', 'admin')";
    $conn->query($sql);
    echo "Admin user created.<br>";
}

// 2. Instructor User
$instructor_pass = password_hash('instructor123', PASSWORD_DEFAULT);
$check_instr = "SELECT * FROM users WHERE email = 'instructor@example.com'";
if ($conn->query($check_instr)->num_rows == 0) {
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('John Instructor', 'instructor@example.com', '$instructor_pass', 'instructor')";
    $conn->query($sql);
    echo "Instructor user created.<br>";
}

// 3. Student User
$student_pass = password_hash('student123', PASSWORD_DEFAULT);
$check_student = "SELECT * FROM users WHERE email = 'student@example.com'";
if ($conn->query($check_student)->num_rows == 0) {
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('Jane Student', 'student@example.com', '$student_pass', 'student')";
    $conn->query($sql);
    echo "Student user created.<br>";
}

echo "Database setup complete.";
$conn->close();
?>
