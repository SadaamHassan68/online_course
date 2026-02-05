<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Verify enrollment
$enrollment = $conn->query("SELECT * FROM enrollments WHERE user_id = $user_id AND course_id = $course_id")->fetch_assoc();
if ((!$enrollment || $enrollment['status'] !== 'active') && $_SESSION['role'] == 'student') {
    die("<div class='container' style='padding: 50px; text-align: center;'><h2>Access Denied</h2><p>Your enrollment is pending approval or has been suspended.</p><a href='student/dashboard.php' class='btn btn-primary'>Back to Dashboard</a></div>");
}

$enrollment_id = $enrollment ? $enrollment['id'] : null;

// Get Course Info
$course = $conn->query("SELECT * FROM courses WHERE id = $course_id")->fetch_assoc();

// Get Lessons
$sections = [];
$sql_sections = "SELECT * FROM sections WHERE course_id = $course_id ORDER BY sort_order";
$res_sections = $conn->query($sql_sections);
while($sec = $res_sections->fetch_assoc()) {
    $sec_id = $sec['id'];
    $lessons = [];
    $res_lessons = $conn->query("SELECT * FROM lessons WHERE section_id = $sec_id ORDER BY sort_order");
    while($l = $res_lessons->fetch_assoc()) {
        $lessons[] = $l;
    }
    $sec['lessons'] = $lessons;
    $sections[] = $sec;
}

// Get Active Lesson
$active_lesson_id = isset($_GET['lesson']) ? intval($_GET['lesson']) : 0;
$active_lesson = null;

// Find first lesson if none selected
if ($active_lesson_id == 0 && !empty($sections)) {
    if (!empty($sections[0]['lessons'])) {
        $active_lesson = $sections[0]['lessons'][0];
        $active_lesson_id = $active_lesson['id'];
    }
} else {
    // Fetch specific lesson
    $active_lesson = $conn->query("SELECT * FROM lessons WHERE id = $active_lesson_id")->fetch_assoc();
}

// Mark progress logic (Simple: Mark as viewed when loaded)
if ($active_lesson && $enrollment_id) {
    // Check if progress exists
    $check_prog = $conn->query("SELECT * FROM lesson_progress WHERE enrollment_id = $enrollment_id AND lesson_id = $active_lesson_id");
    if ($check_prog->num_rows == 0) {
        $conn->query("INSERT INTO lesson_progress (enrollment_id, lesson_id) VALUES ($enrollment_id, $active_lesson_id)");
        
        // Update total progress
        $total_lessons = $conn->query("SELECT COUNT(*) as c FROM lessons l JOIN sections s ON l.section_id = s.id WHERE s.course_id = $course_id")->fetch_assoc()['c'];
        $completed_lessons = $conn->query("SELECT COUNT(*) as c FROM lesson_progress WHERE enrollment_id = $enrollment_id")->fetch_assoc()['c'];
        
        if ($total_lessons > 0) {
            $new_progress = ($completed_lessons / $total_lessons) * 100;
            $conn->query("UPDATE enrollments SET progress = $new_progress WHERE id = $enrollment_id");
        }
    }
}
?>

<div class="container-fluid" style="display: flex; min-height: calc(100vh - 80px);">
    <!-- Sidebar: Course Content -->
    <div style="width: 350px; background: var(--bg-surface); border-right: 1px solid var(--border); overflow-y: auto; height: calc(100vh - 80px); flex-shrink: 0;">
        <div style="padding: 20px; border-bottom: 1px solid var(--border);">
            <h4 style="margin-bottom: 5px;"><?php echo htmlspecialchars($course['title']); ?></h4>
            <div style="height: 4px; background: rgba(255,255,255,0.1); border-radius: 2px; overflow: hidden;">
                <?php 
                 // Get fresh progress
                 if ($_SESSION['role'] == 'student') {
                    $prog = $conn->query("SELECT progress FROM enrollments WHERE id = $enrollment_id")->fetch_assoc()['progress'];
                    echo "<div style='width: {$prog}%; background: var(--success); height: 100%; transition: width 0.5s;'></div>";
                 }
                ?>
            </div>
            <span style="font-size: 0.8rem; color: var(--text-muted);"><?php echo isset($prog) ? intval($prog) : 0; ?>% Complete</span>
        </div>

        <div class="curriculum-list">
            <?php foreach($sections as $section): ?>
                <div style="padding: 15px 20px; background: rgba(255,255,255,0.02); font-weight: 600; font-size: 0.9rem;">
                    <?php echo htmlspecialchars($section['title']); ?>
                </div>
                <div>
                    <?php foreach($section['lessons'] as $lesson): ?>
                        <a href="?id=<?php echo $course_id; ?>&lesson=<?php echo $lesson['id']; ?>" style="display: flex; align-items: center; padding: 12px 20px; color: var(--text-muted); text-decoration: none; border-left: 3px solid transparent; <?php if($lesson['id'] == $active_lesson_id) echo 'background: rgba(99, 102, 241, 0.1); color: var(--text-main); border-left-color: var(--primary);'; ?>">
                            <i class="fas <?php echo $lesson['type'] == 'video' ? 'fa-play-circle' : 'fa-file-alt'; ?>" style="margin-right: 10px; width: 20px;"></i>
                            <span style="font-size: 0.9rem;"><?php echo htmlspecialchars($lesson['title']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Main Content: Player -->
    <div style="flex: 1; padding: 40px; overflow-y: auto;">
        <?php if ($active_lesson): ?>
            <div class="glass-card" style="padding: 0; overflow: hidden; margin-bottom: 30px;">
                <?php if ($active_lesson['type'] == 'video'): ?>
                    <div style="position: relative; padding-bottom: 56.25%; height: 0; background: #000;">
                        <?php 
                        $content_url = $active_lesson['content'];
                        if (strpos($content_url, 'uploads/') === 0) {
                            // Local Video File
                            $ext = strtolower(pathinfo($content_url, PATHINFO_EXTENSION));
                            $mime_map = [
                                'mp4' => 'video/mp4',
                                'webm' => 'video/webm',
                                'ogg' => 'video/ogg',
                                'mov' => 'video/quicktime',
                                'mkv' => 'video/x-matroska'
                            ];
                            $mime_type = isset($mime_map[$ext]) ? $mime_map[$ext] : 'video/mp4';
                            ?>
                            <video controls style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                <source src="<?php echo htmlspecialchars($content_url); ?>" type="<?php echo $mime_type; ?>">
                                Your browser does not support the video tag.
                            </video>
                            <?php
                        } else {
                            // External Video (YouTube/Embed)
                            ?>
                            <iframe src="<?php echo htmlspecialchars($content_url); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allowfullscreen></iframe>
                            <?php
                        }
                        ?>
                    </div>
                <?php else: ?>
                    <div style="padding: 40px; min-height: 400px; color: var(--text-main);">
                        <?php echo nl2br(htmlspecialchars($active_lesson['content'])); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;"><?php echo htmlspecialchars($active_lesson['title']); ?></h2>
                <?php if($active_lesson['attachment_url']): ?>
                    <a href="<?php echo htmlspecialchars($active_lesson['attachment_url']); ?>" class="btn btn-outline" download><i class="fas fa-download"></i> Download Resources</a>
                <?php endif; ?>
            </div>
            
            <!-- Navigation Buttons -->
            <!-- Logic to find next lesson ID could go here -->

        <?php else: ?>
            <div class="glass-card" style="padding: 0; overflow: hidden; margin-bottom: 30px;">
                <div style="position: relative; padding-bottom: 56.25%; height: 0; background: #000;">
                    <video 
                        controls 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                        poster="<?php echo htmlspecialchars($course['thumbnail']); ?>"
                    >
                         <source src="" type="video/mp4">
                         Your browser does not support the video tag.
                    </video>
                    <!-- Overlay Play Icon -->
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; text-align: center; pointer-events: none;">
                         <i class="fas fa-play-circle" style="font-size: 5rem; opacity: 0.8; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));"></i>
                    </div>
                </div>
                <div style="padding: 30px;">
                    <h2 style="margin-bottom: 10px;">Welcome to <?php echo htmlspecialchars($course['title']); ?>!</h2>
                    <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.6;">
                        We're excited to have you here. <br>
                        <strong>How to start:</strong> Please select the first lesson from the sidebar menu to begin your learning journey.
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
