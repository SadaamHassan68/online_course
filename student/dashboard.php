<?php
$path_prefix = '../';
include '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<div class="container" style="padding: 40px 0;">
    <div style="margin-bottom: 40px;">
        <h1 class="gradient-text" style="font-size: 2.5rem;">My Dashboard</h1>
        <p style="color: var(--text-muted);">Welcome back, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
    </div>

    <!-- Stats Row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div class="glass-card">
            <h4 style="color: var(--text-muted); font-weight: 500;">Enrolled Courses</h4>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">
                <?php
                $sql_count = "SELECT COUNT(*) as count FROM enrollments WHERE user_id = $user_id";
                $res_count = $conn->query($sql_count);
                echo $res_count->fetch_assoc()['count'];
                ?>
            </div>
        </div>
        <div class="glass-card">
            <h4 style="color: var(--text-muted); font-weight: 500;">In Progress</h4>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--warning);">
                <?php
                $sql_prog = "SELECT COUNT(*) as count FROM enrollments WHERE user_id = $user_id AND progress < 100";
                $res_prog = $conn->query($sql_prog);
                echo $res_prog->fetch_assoc()['count'];
                ?>
            </div>
        </div>
        <div class="glass-card">
            <h4 style="color: var(--text-muted); font-weight: 500;">Completed</h4>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--success);">
                <?php
                $sql_comp = "SELECT COUNT(*) as count FROM enrollments WHERE user_id = $user_id AND progress = 100";
                $res_comp = $conn->query($sql_comp);
                echo $res_comp->fetch_assoc()['count'];
                ?>
            </div>
        </div>
        <div class="glass-card">
            <h4 style="color: var(--text-muted); font-weight: 500;">Certificates</h4>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--secondary);">
                 <?php
                $sql_cert = "SELECT COUNT(*) as count FROM certificates WHERE user_id = $user_id";
                $res_cert = $conn->query($sql_cert);
                echo $res_cert->fetch_assoc()['count'];
                ?>
            </div>
        </div>
    </div>

    <!-- Enrolled Courses -->
    <h3 style="margin-bottom: 20px; color: var(--text-main);">My Learning</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <?php
        $sql = "SELECT e.*, c.title, c.thumbnail, c.instructor_id, u.name as instructor_name 
                FROM enrollments e 
                JOIN courses c ON e.course_id = c.id 
                JOIN users u ON c.instructor_id = u.id 
                WHERE e.user_id = $user_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="glass-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="height: 150px; background: #333; position: relative;">
                         <?php if($row['thumbnail']): ?>
                            <img src="<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                             <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(45deg, var(--primary), var(--secondary)); font-size: 2rem;">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                        <h4 style="margin-bottom: 10px;"><?php echo htmlspecialchars($row['title']); ?></h4>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px;">By <?php echo htmlspecialchars($row['instructor_name']); ?></p>
                        
                        <div style="margin-top: auto;">
                            <?php if (isset($row['status']) && $row['status'] == 'pending'): ?>
                                <div style="background: rgba(255, 193, 7, 0.2); border: 1px solid var(--warning); color: var(--warning); padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 10px;">
                                    <i class="fas fa-clock"></i> Enrollment Pending
                                </div>
                                <button class="btn btn-secondary" style="width: 100%; padding: 0.5rem; opacity: 0.7; cursor: not-allowed;" disabled>Waiting for Approval</button>
                            <?php else: ?>
                                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 5px;">
                                    <span>Progress</span>
                                    <span><?php echo $row['progress']; ?>%</span>
                                </div>
                                <div style="background: rgba(255,255,255,0.1); height: 6px; border-radius: 3px; overflow: hidden; margin-bottom: 15px;">
                                    <div style="width: <?php echo $row['progress']; ?>%; background: var(--success); height: 100%;"></div>
                                </div>
                                <a href="../course_player.php?id=<?php echo $row['course_id']; ?>" class="btn btn-primary" style="width: 100%; padding: 0.5rem;">Continue Learning</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="glass-card" style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                <div style="font-size: 3rem; color: var(--text-muted); margin-bottom: 20px;"><i class="fas fa-folder-open"></i></div>
                <h3>You haven't enrolled in any courses yet.</h3>
                <p style="color: var(--text-muted); margin-bottom: 30px;">Explore our catalog and start learning today!</p>
                <a href="../courses.php" class="btn btn-primary">Browse Courses</a>
            </div>
            <?php
        }
        ?>
    </div>
</div>

</body>
</html>
