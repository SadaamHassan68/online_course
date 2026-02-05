<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LearnPro</title>
    <!-- Adjust path for admin folder -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Admin specific overrides */
        body { padding-left: 250px; } /* Space for sidebar */
    </style>
</head>
<body>
    <!-- Background -->
    <div class="bg-gradient-circle" style="top: -100px; left: -100px;"></div>
    
    <?php include 'sidebar.php'; ?>
    
    <div class="admin-content-wrapper">
