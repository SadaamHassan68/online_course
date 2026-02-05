<?php
include '../includes/db.php';
include 'includes/header.php';
?>

<div class="container" style="padding: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 class="gradient-text">Course Management</h1>
        <p style="color: var(--text-muted);">Manage and review all platform courses</p>
    </div>

    <div class="glass-card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <th style="padding: 15px;">Course</th>
                    <th style="padding: 15px;">Instructor</th>
                    <th style="padding: 15px;">Category</th>
                    <th style="padding: 15px;">Price</th>
                    <th style="padding: 15px;">Status</th>
                    <th style="padding: 15px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT c.*, u.name as instructor FROM courses c JOIN users u ON c.instructor_id = u.id ORDER BY c.created_at DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()):
                        $status_color = match($row['status']) {
                            'published' => 'var(--success)',
                            'pending' => 'var(--warning)',
                            'rejected' => 'var(--danger)',
                            default => 'var(--text-muted)'
                        };
                ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 15px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <?php if($row['thumbnail']): ?>
                                <img src="<?php echo htmlspecialchars($row['thumbnail']); ?>" style="width: 40px; height: 40px; border-radius: 5px; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 40px; height: 40px; background: #333; border-radius: 5px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-book"></i></div>
                            <?php endif; ?>
                            <span style="font-weight: 500;"><?php echo htmlspecialchars($row['title']); ?></span>
                        </div>
                    </td>
                    <td style="padding: 15px; font-size: 0.9rem;"><?php echo htmlspecialchars($row['instructor']); ?></td>
                    <td style="padding: 15px; font-size: 0.9rem; color: var(--text-muted);"><?php echo htmlspecialchars($row['category']); ?></td>
                    <td style="padding: 15px; font-weight: 600;">$<?php echo number_format($row['price'], 2); ?></td>
                    <td style="padding: 15px;">
                        <span style="color: <?php echo $status_color; ?>; text-transform: capitalize; font-weight: 600;"><?php echo $row['status']; ?></span>
                    </td>
                    <td style="padding: 15px;">
                        <div style="display: flex; gap: 10px;">
                            <?php if($row['status'] == 'pending'): ?>
                                <a href="approve_course.php?id=<?php echo $row['id']; ?>&action=approve" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;">Approve</a>
                            <?php endif; ?>
                            <a href="../course_details.php?id=<?php echo $row['id']; ?>" class="btn btn-outline" style="padding: 5px 10px; font-size: 0.8rem;" title="View" target="_blank"><i class="fas fa-eye"></i></a>
                            <a href="../instructor/manage_curriculum.php?id=<?php echo $row['id']; ?>" class="btn btn-outline" style="padding: 5px 10px; font-size: 0.8rem;" title="Edit Curriculum"><i class="fas fa-edit"></i></a>
                            <a href="delete_course.php?id=<?php echo $row['id']; ?>" class="btn btn-outline" style="padding: 5px 10px; font-size: 0.8rem; color: var(--danger); border-color: var(--danger);" title="Delete" onclick="return confirm('Are you sure you want to delete this course? All lessons and enrollments will be lost.')"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; 
                } else {
                    echo '<tr><td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">No courses found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div> <!-- End admin-content-wrapper -->
</body>
</html>
