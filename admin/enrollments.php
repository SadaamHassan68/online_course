<?php
include '../includes/db.php';
include 'includes/header.php';

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $enrollment_id = intval($_GET['id']);
    $action = $_GET['action'];
    
    // Get enrollment details to find related payment
    $res = $conn->query("SELECT user_id, course_id FROM enrollments WHERE id = $enrollment_id");
    if ($res && $res->num_rows > 0) {
        $enr = $res->fetch_assoc();
        
        $conn->begin_transaction();
        try {
            if ($action == 'approve') {
                $conn->query("UPDATE enrollments SET status = 'active' WHERE id = $enrollment_id");
                // Update payment to completed
                $conn->query("UPDATE payments SET status = 'completed' WHERE user_id = {$enr['user_id']} AND course_id = {$enr['course_id']}");
                $msg = "Enrollment approved successfully.";
            } elseif ($action == 'reject') {
                $conn->query("UPDATE enrollments SET status = 'rejected' WHERE id = $enrollment_id");
                // Update payment to failed/rejected
                $conn->query("UPDATE payments SET status = 'failed' WHERE user_id = {$enr['user_id']} AND course_id = {$enr['course_id']}");
                 $msg = "Enrollment rejected.";
            }
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Action failed: " . $e->getMessage();
        }
    }
}
?>

<div class="container" style="padding: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 class="gradient-text">Enrollment Management</h1>
            <p style="color: var(--text-muted);">Manage student enrollments and verify payments</p>
        </div>
        
        <!-- Revenue Card -->
        <div class="glass-card" style="padding: 15px 25px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; background: rgba(46, 204, 113, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--success); font-size: 1.5rem;">
                <i class="fas fa-coins"></i>
            </div>
            <div>
                <div style="font-size: 0.9rem; color: var(--text-muted);">Total Revenue</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);">
                    <?php
                    $rev_sql = "SELECT SUM(amount) as total FROM payments WHERE status = 'completed'";
                    $rev_res = $conn->query($rev_sql);
                    $revenue = $rev_res->fetch_assoc()['total'] ?? 0;
                    echo '$' . number_format($revenue, 2);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($msg)): ?>
        <div style="background: rgba(46, 204, 113, 0.2); color: var(--success); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <div class="glass-card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <th style="padding: 15px;">Student</th>
                    <th style="padding: 15px;">Course</th>
                    <th style="padding: 15px;">Date</th>
                    <th style="padding: 15px;">Amount</th>
                    <th style="padding: 15px;">Status</th>
                    <th style="padding: 15px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch enrollments with related data
                $sql = "SELECT e.*, u.name as student_name, u.email, c.title as course_title, c.price 
                        FROM enrollments e 
                        JOIN users u ON e.user_id = u.id 
                        JOIN courses c ON e.course_id = c.id 
                        ORDER BY FIELD(e.status, 'pending', 'active', 'rejected'), e.enrolled_at DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()):
                        // Color coding for status
                         $status_color = match($row['status']) {
                            'active' => 'var(--success)',
                            'pending' => 'var(--warning)',
                            'rejected' => 'var(--danger)',
                            default => 'var(--text-muted)'
                        };
                ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 15px;">
                        <div style="font-weight: 500;"><?php echo htmlspecialchars($row['student_name']); ?></div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);"><?php echo htmlspecialchars($row['email']); ?></div>
                    </td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($row['course_title']); ?></td>
                    <td style="padding: 15px; font-size: 0.9rem; color: var(--text-muted);"><?php echo date('M j, Y H:i', strtotime($row['enrolled_at'])); ?></td>
                    <td style="padding: 15px; font-weight: 600;">$<?php echo number_format($row['price'], 2); ?></td>
                    <td style="padding: 15px;">
                        <span style="color: <?php echo $status_color; ?>; text-transform: capitalize; font-weight: 600; padding: 5px 10px; background: rgba(255,255,255,0.05); border-radius: 5px;">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td style="padding: 15px;">
                        <div style="display: flex; gap: 10px;">
                            <?php if($row['status'] == 'pending'): ?>
                                <a href="enrollments.php?id=<?php echo $row['id']; ?>&action=approve" class="btn btn-primary" style="padding: 5px 15px; font-size: 0.8rem; background: var(--success); border-color: var(--success);">
                                    <i class="fas fa-check"></i> Accept
                                </a>
                                <a href="enrollments.php?id=<?php echo $row['id']; ?>&action=reject" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.8rem; color: var(--danger); border-color: var(--danger);">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 0.8rem;">-</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; 
                } else {
                    echo '<tr><td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">No enrollments found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div> <!-- End admin-content-wrapper -->
</body>
</html>
