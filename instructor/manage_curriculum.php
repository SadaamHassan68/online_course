<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['instructor', 'admin'])) {
    header("Location: ../login.php");
    exit;
}

$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Verify ownership (Admin can manage any course, Instructor only their own)
$ownership_sql = ($_SESSION['role'] == 'admin') ? "SELECT * FROM courses WHERE id = $course_id" : "SELECT * FROM courses WHERE id = $course_id AND instructor_id = " . $_SESSION['user_id'];
$check = $conn->query($ownership_sql);

if ($check->num_rows == 0) {
    die("Access Denied");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Curriculum - LearnPro</title>
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
<?php

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_section'])) {
        $title = trim($_POST['section_title']);
        $conn->query("INSERT INTO sections (course_id, title) VALUES ($course_id, '$title')");
    }
    
    if (isset($_POST['add_lesson'])) {
        $section_id = intval($_POST['section_id']);
        $title = trim($_POST['lesson_title']);
        $type = $_POST['lesson_type'];
        $content = $_POST['lesson_content']; // URL or Text
        
        // Handle Video File Upload
        if ($type == 'video_file' && isset($_FILES['lesson_file'])) {
            if ($_FILES['lesson_file']['error'] == 0) {
                $allowed = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv', 'wmv'];
                $filename = $_FILES['lesson_file']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($ext, $allowed)) {
                    $new_filename = uniqid('vid_', true) . '.' . $ext;
                    $upload_path = '../uploads/videos/' . $new_filename;
                    
                    if (move_uploaded_file($_FILES['lesson_file']['tmp_name'], $upload_path)) {
                        $content = 'uploads/videos/' . $new_filename;
                        $type = 'video'; // Store as video type for player compatibility
                    } else {
                        $error = "Failed to move uploaded file. Check directory permissions.";
                    }
                } else {
                    $error = "Invalid file type. Allowed: MP4, WebM, Ogg, MOV, AVI, MKV, WMV.";
                }
            } else {
                // Handle specific upload errors
                switch ($_FILES['lesson_file']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        $error = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $error = "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form.";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $error = "The uploaded file was only partially uploaded.";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $error = "No file was uploaded.";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $error = "Missing a temporary folder.";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $error = "Failed to write file to disk.";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $error = "A PHP extension stopped the file upload.";
                        break;
                    default:
                        $error = "Unknown file upload error.";
                        break;
                }
            }
        }
        // Handle YouTube URL
        elseif ($type == 'video') {
            // Simple YouTube URL converter
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $content, $matches)) {
                $video_id = $matches[1];
                $content = "https://www.youtube.com/embed/" . $video_id;
            }
        }
        
        if (!isset($error)) {
            $stmt = $conn->prepare("INSERT INTO lessons (section_id, title, type, content) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $section_id, $title, $type, $content);
            $stmt->execute();
        }
    }
}

$course = $check->fetch_assoc();
?>

<div class="container" style="padding: 40px 0;">
    <div style="margin-bottom: 30px;">
        <h1 class="gradient-text">Manage Curriculum</h1>
        <h3 style="color: var(--text-muted);"><?php echo htmlspecialchars($course['title']); ?></h3>
    </div>
    
    <?php if(isset($error)): ?>
        <div style="background: rgba(231, 76, 60, 0.2); color: var(--danger); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        <!-- Left: Course Structure -->
        <div style="flex: 2; min-width: 300px;">
            <?php
            $sections = $conn->query("SELECT * FROM sections WHERE course_id = $course_id ORDER BY sort_order");
            while($sec = $sections->fetch_assoc()):
            ?>
            <div class="glass-card" style="margin-bottom: 20px; padding: 0;">
                <div style="padding: 15px 20px; background: rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                    <h4 style="margin: 0;"><?php echo htmlspecialchars($sec['title']); ?></h4>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Section ID: <?php echo $sec['id']; ?></span>
                </div>
                <div style="padding: 20px;">
                    <!-- Lessons List -->
                    <?php
                    $lessons = $conn->query("SELECT * FROM lessons WHERE section_id = " . $sec['id'] . " ORDER BY sort_order");
                    if ($lessons->num_rows > 0) {
                        while($l = $lessons->fetch_assoc()) {
                            echo "<div style='padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center;'>";
                            echo "<i class='fas fa-".($l['type']=='video'?'video':'file-alt')."' style='margin-right: 10px; color: var(--text-muted);'></i>";
                            echo "<span>".htmlspecialchars($l['title'])."</span>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p style='color: var(--text-muted); font-size: 0.9rem;'>No lessons yet.</p>";
                    }
                    ?>
                    
                    <!-- Add Lesson Form -->
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px dashed var(--border);">
                        <h5 style="margin-bottom: 10px;">Add Lesson</h5>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="section_id" value="<?php echo $sec['id']; ?>">
                            <input type="hidden" name="add_lesson" value="1">
                            <div style="display: grid; gap: 10px;">
                                <input type="text" name="lesson_title" class="form-control" placeholder="Lesson Title" required>
                                <select name="lesson_type" class="form-control" onchange="toggleLessonInput(this)">
                                    <option value="video">Video (YouTube URL)</option>
                                    <option value="video_file">Upload Video File</option>
                                    <option value="text">Text Content</option>
                                </select>
                                
                                <!-- Content Input (URL/Text) -->
                                <div class="content-input-group">
                                    <textarea name="lesson_content" class="form-control" placeholder="Paste YouTube URL..." rows="2"></textarea>
                                </div>
                                
                                <!-- File Input (Hidden by default) -->
                                <div class="file-input-group" style="display: none;">
                                    <input type="file" name="lesson_file" class="form-control" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-msvideo,video/x-matroska,video/x-ms-wmv,video/*">
                                </div>

                                <button type="submit" class="btn btn-outline" style="width: 100%;">+ Add Lesson</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            
            <script>
            function toggleLessonInput(select) {
                const form = select.closest('form');
                const contentGroup = form.querySelector('.content-input-group');
                const fileGroup = form.querySelector('.file-input-group');
                const textarea = contentGroup.querySelector('textarea');
                
                if (select.value === 'video_file') {
                    contentGroup.style.display = 'none';
                    fileGroup.style.display = 'block';
                    textarea.required = false;
                } else {
                    contentGroup.style.display = 'block';
                    fileGroup.style.display = 'none';
                    textarea.required = true;
                    
                    if (select.value === 'video') {
                        textarea.placeholder = 'Paste YouTube URL (e.g. https://www.youtube.com/watch?v=...)';
                    } else {
                        textarea.placeholder = 'Enter text content here...';
                    }
                }
            }
            </script>

            <!-- Add Section -->
            <div class="glass-card" style="text-align: center; border: 2px dashed var(--border); background: transparent;">
                <form action="" method="POST" style="display: flex; gap: 10px; justify-content: center;">
                    <input type="hidden" name="add_section" value="1">
                    <input type="text" name="section_title" class="form-control" placeholder="New Section Title" style="width: auto;" required>
                    <button type="submit" class="btn btn-primary">Add Section</button>
                </form>
            </div>
        </div>

        <!-- Right: Actions -->
        <div style="flex: 1; min-width: 250px;">
            <div class="glass-card">
                <h3>Course Actions</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px;">Use the preview button to see how your course looks to students.</p>
                <a href="../course_details.php?id=<?php echo $course_id; ?>" class="btn btn-outline" style="width: 100%; margin-bottom: 10px;">Preview Course</a>
                <a href="<?php echo ($_SESSION['role'] == 'admin') ? '../admin/dashboard.php' : 'dashboard.php'; ?>" class="btn btn-primary" style="width: 100%;">Done & Return</a>
            </div>
        </div>
    </div>
</div>
</div> <!-- End admin-content-wrapper -->
</body>
</html>
