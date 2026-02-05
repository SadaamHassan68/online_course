<?php
include '../includes/db.php';
include 'includes/header.php';
?>

<div class="container" style="padding: 40px 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 class="gradient-text">User Management</h1>
        <a href="dashboard.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <div class="glass-card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <th style="padding: 15px;">User</th>
                    <th style="padding: 15px;">Role</th>
                    <th style="padding: 15px;">Status</th>
                    <th style="padding: 15px;">Joined</th>
                    <th style="padding: 15px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM users ORDER BY created_at DESC";
                $result = $conn->query($sql);

                while($row = $result->fetch_assoc()):
                    $status_color = ($row['status'] ?? 'active') == 'active' ? 'var(--success)' : 'var(--danger)';
                ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 15px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 30px; height: 30px; background: #444; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($row['name']); ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?php echo htmlspecialchars($row['email']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 15px;">
                         <form action="manage_user.php" method="POST" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="change_role" value="1">
                            <select name="new_role" onchange="this.form.submit()" style="background: rgba(0,0,0,0.2); border: 1px solid var(--border); color: var(--text-main); padding: 5px; border-radius: 5px;">
                                <option value="student" <?php if($row['role']=='student') echo 'selected'; ?>>Student</option>
                                <option value="instructor" <?php if($row['role']=='instructor') echo 'selected'; ?>>Instructor</option>
                                <option value="admin" <?php if($row['role']=='admin') echo 'selected'; ?>>Admin</option>
                            </select>
                        </form>
                    </td>
                    <td style="padding: 15px;">
                        <span style="color: <?php echo $status_color; ?>; font-weight: 600; text-transform: capitalize;">
                            <?php echo $row['status'] ?? 'active'; ?>
                        </span>
                    </td>
                    <td style="padding: 15px; font-size: 0.9rem; color: var(--text-muted);">
                        <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                    </td>
                    <td style="padding: 15px;">
                        <div style="display: flex; gap: 10px;">
                            <!-- Ban/Unban Button -->
                            <form action="manage_user.php" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="toggle_status" value="1">
                                <input type="hidden" name="current_status" value="<?php echo $row['status'] ?? 'active'; ?>">
                                <?php if(($row['status'] ?? 'active') == 'active'): ?>
                                    <button type="submit" class="btn btn-outline" style="padding: 5px 10px; color: var(--danger); border-color: var(--danger);" title="Ban User"><i class="fas fa-ban"></i></button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-outline" style="padding: 5px 10px; color: var(--success); border-color: var(--success);" title="Activate User"><i class="fas fa-check"></i></button>
                                <?php endif; ?>
                            </form>

                            <!-- Reset Password -->
                            <form action="manage_user.php" method="POST" onsubmit="return confirm('Reset password to password123?');">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="reset_password" value="1">
                                <button type="submit" class="btn btn-outline" style="padding: 5px 10px;" title="Reset Password"><i class="fas fa-key"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div> <!-- End admin-content-wrapper -->
</body>
</html>
