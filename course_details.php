<?php
include 'includes/db.php';
include 'includes/header.php';

$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$course = $conn->query("SELECT c.*, u.name as instructor_name, u.avatar FROM courses c JOIN users u ON c.instructor_id = u.id WHERE c.id = $course_id")->fetch_assoc();

if (!$course) {
    die("Course not found.");
}

$is_enrolled = false;
if (isset($_SESSION['user_id'])) {
    $check = $conn->query("SELECT * FROM enrollments WHERE user_id = " . $_SESSION['user_id'] . " AND course_id = $course_id");
    if ($check->num_rows > 0) {
        $is_enrolled = true;
    }
}
?>

<!-- Course Header -->
<div style="background: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.9)), url('<?php echo $course['thumbnail'] ? htmlspecialchars($course['thumbnail']) : 'assets/img/course-bg.jpg'; ?>'); background-size: cover; padding: 60px 0; color: white;">
    <div class="container">
        <div style="max-width: 800px;">
            <span style="background: var(--primary); padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;"><?php echo htmlspecialchars($course['category']); ?></span>
            <h1 style="font-size: 3rem; margin: 20px 0;"><?php echo htmlspecialchars($course['title']); ?></h1>
            <p style="font-size: 1.2rem; margin-bottom: 30px; line-height: 1.6;"><?php echo htmlspecialchars($course['description']); ?></p>
            
            <div style="display: flex; align-items: center; gap: 20px; font-size: 0.9rem;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 30px; height: 30px; background: #ccc; border-radius: 50%;"></div>
                    <span>Created by <?php echo htmlspecialchars($course['instructor_name']); ?></span>
                </div>
                <span><i class="fas fa-calendar"></i> Last updated <?php echo date('M Y', strtotime($course['created_at'])); ?></span>
                <span><i class="fas fa-globe"></i> English</span>
            </div>
            
            <div style="margin-top: 40px;">
                <span style="font-size: 2.5rem; font-weight: 700; margin-right: 20px;">$<?php echo htmlspecialchars($course['price']); ?></span>
                <?php if ($is_enrolled): ?>
                    <a href="course_player.php?id=<?php echo $course_id; ?>" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.2rem;">Go to Course</a>
                <?php else: ?>
                    <a href="enroll.php?id=<?php echo $course_id; ?>" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.2rem;">Enroll Now</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding: 60px 0; display: flex; gap: 50px; flex-wrap: wrap;">
    <!-- Main Content -->
    <div style="flex: 2; min-width: 300px;">
        <div class="glass-card" style="margin-bottom: 40px;">
            <h2 style="margin-bottom: 20px;">What you'll learn</h2>
            <ul style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <li style="display: flex; gap: 10px;"><i class="fas fa-check" style="color: var(--success); margin-top: 5px;"></i> Comprehensive understanding of the subject</li>
                <li style="display: flex; gap: 10px;"><i class="fas fa-check" style="color: var(--success); margin-top: 5px;"></i> Real-world examples and projects</li>
                <li style="display: flex; gap: 10px;"><i class="fas fa-check" style="color: var(--success); margin-top: 5px;"></i> Expert tips and best practices</li>
                <li style="display: flex; gap: 10px;"><i class="fas fa-check" style="color: var(--success); margin-top: 5px;"></i> Lifetime access to resources</li>
            </ul>
        </div>

        <h2 style="margin-bottom: 20px;">Course Content</h2>
        <div class="glass-card" style="padding: 0;">
            <?php
            $sections = $conn->query("SELECT * FROM sections WHERE course_id = $course_id ORDER BY sort_order");
            while($sec = $sections->fetch_assoc()):
            ?>
            <div>
                <div style="padding: 15px 20px; background: rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); font-weight: 600;">
                    <?php echo htmlspecialchars($sec['title']); ?>
                </div>
                <div style="padding: 10px 20px;">
                    <?php
                    $lessons = $conn->query("SELECT * FROM lessons WHERE section_id = " . $sec['id'] . " ORDER BY sort_order");
                    while($l = $lessons->fetch_assoc()):
                    ?>
                    <div style="padding: 8px 0; display: flex; justify-content: space-between; color: var(--text-muted); font-size: 0.9rem;">
                        <span><i class="fas fa-play-circle" style="margin-right: 10px;"></i> <?php echo htmlspecialchars($l['title']); ?></span>
                        <span><?php echo ($l['type'] == 'video') ? 'Video' : 'Text'; ?></span>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div style="flex: 1; min-width: 250px;">
        <div class="glass-card">
            <h3>This course includes:</h3>
            <ul style="margin-top: 20px; display: grid; gap: 15px; color: var(--text-muted);">
                <li><i class="fas fa-video" style="width: 20px;"></i> On-demand video</li>
                <li><i class="fas fa-file-alt" style="width: 20px;"></i> Articles</li>
                <li><i class="fas fa-download" style="width: 20px;"></i> Downloadable resources</li>
                <li><i class="fas fa-mobile-alt" style="width: 20px;"></i> Access on mobile and TV</li>
                <li><i class="fas fa-certificate" style="width: 20px;"></i> Certificate of completion</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
