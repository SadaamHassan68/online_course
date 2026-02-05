<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);
    
    // Deleting a course will also delete all related sections, lessons, enrollments, etc. 
    // due to ON DELETE CASCADE constraints in the DB schema.
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    
    if ($stmt->execute()) {
        header("Location: courses.php?msg=Course Deleted");
    } else {
        echo "Error deleting course: " . $conn->error;
    }
} else {
    header("Location: courses.php");
}
?>
