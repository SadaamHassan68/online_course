<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Panel - LearnPro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { padding-left: 250px; } 
    </style>
</head>
<body>
    <div class="bg-gradient-circle" style="top: -100px; right: -100px; background: radial-gradient(closest-side, rgba(236, 72, 153, 0.1), transparent);"></div>
    
    <?php include 'sidebar.php'; ?>
    
    <div class="admin-content-wrapper">
