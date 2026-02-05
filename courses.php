<?php
include 'includes/db.php';
include 'includes/header.php';
?>

<div class="container" style="padding: 40px 0;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h1 class="section-title">Browse Courses</h1>
        <div class="glass" style="display: inline-flex; padding: 5px; border-radius: 50px; width: 100%; max-width: 500px;">
            <input type="text" placeholder="Search for courses..." style="flex: 1; background: transparent; border: none; padding: 10px 20px; color: var(--text-main); outline: none;">
            <button class="btn btn-primary" style="border-radius: 50px; padding: 10px 25px;"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <?php
        $sql = "SELECT c.*, u.name as instructor_name FROM courses c JOIN users u ON c.instructor_id = u.id WHERE c.status = 'published'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Course Card (Reuse from Index)
                ?>
                <div class="glass-card" style="padding: 0; overflow: hidden;">
                    <div style="height: 180px; background: #333; position: relative;">
                         <?php if($row['thumbnail']): ?>
                            <img src="<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                             <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(45deg, var(--primary), var(--secondary)); font-size: 3rem;">
                                <i class="fas fa-book-open"></i>
                            </div>
                        <?php endif; ?>
                        <span style="position: absolute; top: 15px; left: 15px; background: rgba(0,0,0,0.7); padding: 5px 10px; border-radius: 20px; font-size: 0.8rem;"><?php echo htmlspecialchars($row['category'] ?? 'General'); ?></span>
                    </div>
                    <div style="padding: 20px;">
                         <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: var(--warning); font-size: 0.9rem;">
                            <span><i class="fas fa-star"></i> 4.8</span>
                            <span style="color: var(--text-muted);">(120 Reviews)</span>
                        </div>
                        <h3 style="margin-bottom: 10px;"><a href="course_details.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h3>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars($row['description']); ?></p>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                            <div style="width: 25px; height: 25px; background: var(--text-muted); border-radius: 50%;"></div>
                            <span style="font-size: 0.9rem; color: var(--text-muted);"><?php echo htmlspecialchars($row['instructor_name']); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                            <span style="font-size: 1.2rem; font-weight: 700; color: var(--primary);">$<?php echo htmlspecialchars($row['price']); ?></span>
                             <a href="course_details.php?id=<?php echo $row['id']; ?>" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">View Details</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
             echo '<div class="glass-card" style="grid-column: 1/-1; text-align: center; padding: 50px; color: var(--text-muted);">No courses found. Check back later!</div>';
        }
        ?>
    </div>
</div>
</body>
</html>
