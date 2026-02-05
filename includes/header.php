<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Learning Platform</title>
    <?php $path_prefix = isset($path_prefix) ? $path_prefix : ''; ?>
    <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Background Gradient Effect -->
    <div class="bg-gradient-circle" style="top: -100px; left: -100px;"></div>
    <div class="bg-gradient-circle" style="bottom: -100px; right: -100px; background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, rgba(15, 23, 42, 0) 70%);"></div>

    <nav class="navbar glass">
        <div class="container nav-content">
            <a href="<?php echo $path_prefix; ?>index.php" class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>LearnPro</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="<?php echo $path_prefix; ?>index.php" class="nav-link">Home</a></li>
                <li><a href="<?php echo $path_prefix; ?>courses.php" class="nav-link">Courses</a></li>
                <!-- <li><a href="#" class="nav-link">Paths</a></li> -->
                <li><a href="#" class="nav-link">Mentors</a></li>
            </ul>

            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php 
                        $dashboard_link = "student/dashboard.php";
                        if ($_SESSION['role'] === 'instructor') $dashboard_link = "instructor/dashboard.php";
                        if ($_SESSION['role'] === 'admin') $dashboard_link = "admin/dashboard.php";
                        // If we are deep, we need to adjust the link or just make it relative to root
                        // Simpler: use path prefix for the link destination logic if it was dynamic, 
                        // but here we just need to ensure the link points to the right place relative to current page.
                        // Actually, dashboard links are tricky because they are hardcoded subfolders.
                        // If I am in student/, dashboard link to 'student/dashboard.php' becomes 'student/student/dashboard.php' if relative.
                        // FIX: Use $path_prefix for all root-based linking.
                    ?>
                    <a href="<?php echo $path_prefix . $dashboard_link; ?>" class="btn btn-primary">Dashboard</a>
                    <a href="<?php echo $path_prefix; ?>logout.php" class="btn btn-outline" style="margin-left: 10px;">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $path_prefix; ?>login.php" class="btn btn-outline">Log In</a>
                    <a href="<?php echo $path_prefix; ?>register.php" class="btn btn-primary" style="margin-left: 10px;">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <div style="height: 80px;"></div> <!-- Spacer for fixed navbar -->
