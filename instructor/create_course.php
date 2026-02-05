<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['instructor', 'admin'])) {
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

// Move POST handling to the very top, before any HTML
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $instructor_id = $_SESSION['user_id'];
    $thumbnail = $_POST['thumbnail']; 

    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO courses (instructor_id, title, description, price, category, thumbnail, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("issdss", $instructor_id, $title, $description, $price, $category, $thumbnail);
        
        if ($stmt->execute()) {
            $new_course_id = $stmt->insert_id;
            // If admin, auto-approve their own course
            if ($_SESSION['role'] == 'admin') {
                $conn->query("UPDATE courses SET status = 'published' WHERE id = $new_course_id");
            }
            header("Location: manage_curriculum.php?id=" . $new_course_id);
            exit;
        } else {
            $error = "Error: " . $conn->error;
        }
    } else {
        $error = "Title is required.";
    }
}

$dashboard_link = ($_SESSION['role'] == 'admin') ? '../admin/dashboard.php' : 'dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course - LearnPro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { padding-left: 250px; }</style>
</head>
<body>
    <?php 
    if ($_SESSION['role'] == 'admin') {
        include '../admin/includes/sidebar.php';
    } else {
        include 'includes/sidebar.php';
    }
    ?>
    <div class="admin-content-wrapper">
        <div class="container" style="padding: 40px; max-width: 800px;">
            <h1 class="gradient-text" style="font-size: 2.5rem; margin-bottom: 20px;">Create New Course</h1>
            
            <div class="glass-card">
                <?php if ($error): ?>
                    <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--danger); padding: 10px; border-radius: var(--radius-sm); color: #fca5a5; margin-bottom: 20px; text-align: center;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="form-group">
                        <label class="form-label">Course Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Master React JS" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control">
                            <option value="Development">Development</option>
                            <option value="Design">Design</option>
                            <option value="Business">Business</option>
                            <option value="Marketing">Marketing</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="What will students learn?"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Price ($)</label>
                        <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Thumbnail URL</label>
                        <input type="text" name="thumbnail" class="form-control" placeholder="https://example.com/image.jpg">
                        <small style="color: var(--text-muted);">Enter a direct image link or leave blank.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Next: Add Curriculum <i class="fas fa-arrow-right"></i></button>
                </form>
            </div>
        </div>
    </div> <!-- End admin-content-wrapper -->
</body>
</html>
