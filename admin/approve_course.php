<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $course_id = intval($_GET['id']);
    $action = $_GET['action'];
    
    $status = ($action == 'approve') ? 'published' : 'rejected';
    
    $stmt = $conn->prepare("UPDATE courses SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $course_id);
    
    if ($stmt->execute()) {
        // Success
        header("Location: dashboard.php?msg=Course " . ucfirst($status));
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    header("Location: dashboard.php");
}
?>
