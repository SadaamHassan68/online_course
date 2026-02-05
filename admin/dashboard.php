<?php
include '../includes/db.php';
include 'includes/header.php';
?>

<div class="container" style="padding: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <h1 class="gradient-text" style="font-size: 2.5rem; margin: 0;">Admin Dashboard</h1>
        <a href="users.php" class="btn btn-primary"><i class="fas fa-users-cog"></i> Manage Users</a>
    </div>

    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div class="glass-card">
            <h4 style="color: var(--text-muted); font-weight: 500;">Total Users</h4>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">
                <?php echo $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c']; ?>
            </div>
        </div>
        <div class="glass-card">
            <h4 style="color: var(--text-muted); font-weight: 500;">Pending Courses</h4>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--warning);">
                <?php echo $conn->query("SELECT COUNT(*) as c FROM courses WHERE status = 'pending'")->fetch_assoc()['c']; ?>
            </div>
        </div>
        <div class="glass-card">
            <h4 style="color: var(--text-muted); font-weight: 500;">Pending Enrollments</h4>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--info);">
                <?php echo $conn->query("SELECT COUNT(*) as c FROM enrollments WHERE status = 'pending'")->fetch_assoc()['c']; ?>
            </div>
        </div>
         <div class="glass-card">
            <h4 style="color: var(--text-muted); font-weight: 500;">Total Sales</h4>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--success);">
                $<?php 
                $earnings = $conn->query("SELECT SUM(amount) as s FROM payments WHERE status = 'completed'")->fetch_assoc()['s'];
                echo number_format($earnings ? $earnings : 0, 2);
                 ?>
            </div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 40px;">
        <!-- Approvals -->
        <div class="glass-card">
            <h3 style="margin-bottom: 20px;">Pending Approvals</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <th style="padding: 10px;">Course</th>
                        <th style="padding: 10px;">Instructor</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_pending = "SELECT c.*, u.name as instructor FROM courses c JOIN users u ON c.instructor_id = u.id WHERE c.status = 'pending'";
                    $res_pending = $conn->query($sql_pending);
                    if ($res_pending->num_rows > 0) {
                        while($row = $res_pending->fetch_assoc()) {
                            echo "<tr style='border-bottom: 1px solid rgba(255,255,255,0.05);'>";
                            echo "<td style='padding: 10px;'>".htmlspecialchars($row['title'])."</td>";
                            echo "<td style='padding: 10px;'>".htmlspecialchars($row['instructor'])."</td>";
                            echo "<td style='padding: 10px;'>
                                    <a href='approve_course.php?id=".$row['id']."&action=approve' class='btn btn-primary' style='padding: 5px 10px; font-size: 0.8rem;'>Approve</a>
                                    <a href='approve_course.php?id=".$row['id']."&action=reject' class='btn btn-outline' style='padding: 5px 10px; font-size: 0.8rem; color: var(--danger); border-color: var(--danger);'>Reject</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' style='padding: 30px; text-align: center; color: var(--text-muted);'>No courses pending approval.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Popular Courses (Analytics) -->
        <div class="glass-card">
            <h3 style="margin-bottom: 20px;">Top Selling Courses</h3>
            <ul>
                <?php
                // Logic: Count enrollments per course
                $sql_top = "SELECT c.title, COUNT(e.id) as enroll_count 
                            FROM courses c 
                            JOIN enrollments e ON c.id = e.course_id 
                            GROUP BY c.id 
                            ORDER BY enroll_count DESC LIMIT 5";
                $res_top = $conn->query($sql_top);
                
                if ($res_top->num_rows > 0) {
                    while ($top = $res_top->fetch_assoc()) {
                        echo "<li style='display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05);'>";
                        echo "<span>".htmlspecialchars($top['title'])."</span>";
                        echo "<span style='color: var(--success); font-weight: bold;'>".$top['enroll_count']." Students</span>";
                        echo "</li>";
                    }
                } else {
                    echo "<li style='color: var(--text-muted);'>No data available yet.</li>";
                }
                ?>
            </ul>
        </div>
    </div>
</div>
</div> <!-- End admin-content-wrapper -->
</body>
</html>
