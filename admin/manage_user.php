<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = intval($_POST['user_id']);
    
    // Ban / Unban
    if (isset($_POST['toggle_status'])) {
        $current_status = $_POST['current_status'];
        $new_status = ($current_status == 'active') ? 'banned' : 'active';
        $conn->query("UPDATE users SET status = '$new_status' WHERE id = $user_id");
    }
    
    // Change Role
    if (isset($_POST['change_role'])) {
        $new_role = $_POST['new_role'];
        $conn->query("UPDATE users SET role = '$new_role' WHERE id = $user_id");
    }
    
    // Reset Password
    if (isset($_POST['reset_password'])) {
        $default_pass = password_hash('password123', PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password = '$default_pass' WHERE id = $user_id");
    }
}

header("Location: users.php");
?>
