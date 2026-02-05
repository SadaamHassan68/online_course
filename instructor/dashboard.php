<?php
include '../includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
?>

<div class="container" style="padding: 40px; max-width: 1200px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 class="gradient-text" style="font-size: 2.5rem;">Instructor Dashboard</h1>
            <p style="color: var(--text-muted);">Manage your courses and track revenue</p>
        </div>
        <a href="create_course.php" class="btn btn-primary"><i class="fas fa-plus"></i> Create New Course</a>
    </div>

    <!-- Stats Row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div class="glass-card">
            <h4 style="color: var(--text-muted); font-weight: 500;">Total Revenue</h4>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--success);">
                $<?php
                // Logic for total earnings (sum of payments for courses owned by this instructor)
                // Simplified: Sum of course price * enrollments for now
                $sql_earn = "SELECT SUM(c.price) as total FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE c.instructor_id = $user_id";
                $res_earn = $conn->query($sql_earn);
                $earn = $res_earn->fetch_assoc()['total'];
                echo number_format($earn ? $earn : 0, 2);
                ?>
            </div>
        </div>
        <div class="glass-card">
            <h4 style="color: var(--text-muted); font-weight: 500;">Total Students</h4>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">
                <?php
                $sql_stud = "SELECT COUNT(DISTINCT e.user_id) as count FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE c.instructor_id = $user_id";
                $res_stud = $conn->query($sql_stud);
                echo $res_stud->fetch_assoc()['count'];
                ?>
            </div>
        </div>
         <div class="glass-card">
            <h4 style="color: var(--text-muted); font-weight: 500;">Active Courses</h4>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--warning);">
                <?php
                $sql_course = "SELECT COUNT(*) as count FROM courses WHERE instructor_id = $user_id AND status = 'published'";
                $res_course = $conn->query($sql_course);
                echo $res_course->fetch_assoc()['count'];
                ?>
            </div>
        </div>
    </div>

    <!-- My Courses -->
    <h3 style="margin-bottom: 20px; color: var(--text-main);">My Courses</h3>
    <div class="glass-card" style="padding: 20px; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <th style="padding: 15px; color: var(--text-muted);">Course Title</th>
                    <th style="padding: 15px; color: var(--text-muted);">Enrolled</th>
                    <th style="padding: 15px; color: var(--text-muted);">Price</th>
                    <th style="padding: 15px; color: var(--text-muted);">Status</th>
                    <th style="padding: 15px; color: var(--text-muted);">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_courses = "SELECT * FROM courses WHERE instructor_id = $user_id ORDER BY created_at DESC";
                $result = $conn->query($sql_courses);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $status_color = match($row['status']) {
                            'published' => 'var(--success)',
                            'pending' => 'var(--warning)',
                            'rejected' => 'var(--danger)',
                            default => 'var(--text-muted)'
                        };
                        
                        // Count enrollments
                        $course_id = $row['id'];
                        $enrolled = $conn->query("SELECT COUNT(*) as c FROM enrollments WHERE course_id = $course_id")->fetch_assoc()['c'];
                        ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <?php if($row['thumbnail']): ?>
                                        <img src="<?php echo htmlspecialchars($row['thumbnail']); ?>" style="width: 40px; height: 40px; border-radius: 5px; object-fit: cover;">
                                    <?php else: ?>
                                        <div style="width: 40px; height: 40px; background: #333; border-radius: 5px;"></div>
                                    <?php endif; ?>
                                    <span style="font-weight: 500;"><?php echo htmlspecialchars($row['title']); ?></span>
                                </div>
                            </td>
                            <td style="padding: 15px;"><?php echo $enrolled; ?></td>
                            <td style="padding: 15px;">$<?php echo $row['price']; ?></td>
                            <td style="padding: 15px;"><span style="color: <?php echo $status_color; ?>; text-transform: capitalize;"><?php echo $row['status']; ?></span></td>
                            <td style="padding: 15px;">
                                <a href="edit_course.php?id=<?php echo $row['id']; ?>" class="btn btn-outline" style="padding: 5px 10px; font-size: 0.8rem;"><i class="fas fa-edit"></i></a>
                                <a href="manage_curriculum.php?id=<?php echo $row['id']; ?>" class="btn btn-outline" style="padding: 5px 10px; font-size: 0.8rem;"><i class="fas fa-list"></i></a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="5" style="padding: 30px; text-align: center; color: var(--text-muted);">You haven\'t created any courses yet.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div> <!-- End admin-content-wrapper -->
</body>
</html>
