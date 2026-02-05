<?php
include 'includes/db.php';
include 'includes/header.php';
?>

<!-- Hero Section -->
<header class="hero-section" style="padding: 180px 0 100px; position: relative; overflow: hidden;">
    <!-- Abstract Shapes -->
    <div style="position: absolute; top: -20%; left: -10%; width: 50%; height: 50%; background: radial-gradient(circle, rgba(99, 102, 241, 0.2), transparent 70%); border-radius: 50%; filter: blur(80px);"></div>
    <div style="position: absolute; bottom: 10%; right: -10%; width: 40%; height: 60%; background: radial-gradient(circle, rgba(139, 92, 246, 0.15), transparent 70%); border-radius: 50%; filter: blur(80px);"></div>

    <div class="container" style="position: relative; z-index: 2;">
        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 60px; align-items: center;">
            <!-- Left Text -->
            <div>
                <div class="glass" style="display: inline-flex; align-items: center; gap: 10px; padding: 8px 16px; border-radius: 50px; font-size: 0.9rem; margin-bottom: 30px; border: 1px solid rgba(255,255,255,0.1);">
                    <span style="background: var(--success); width: 8px; height: 8px; border-radius: 50%;"></span>
                    <span style="color: var(--text-main); font-weight: 500;">Over 10,000+ Active Students</span>
                </div>
                
                <h1 style="font-size: 4rem; line-height: 1.1; margin-bottom: 25px; font-weight: 800; letter-spacing: -1px;">
                    Unlock Your <br>
                    <span class="gradient-text">Creative Potential</span>
                </h1>
                
                <p style="font-size: 1.25rem; color: var(--text-muted); margin-bottom: 40px; max-width: 550px; line-height: 1.8;">
                    Access world-class courses designed to help you level up your skills. From coding to design, we have everything you need to succeed.
                </p>
                
                <div class="hero-buttons" style="display: flex; gap: 20px;">
                    <a href="register.php" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem; border-radius: 50px;">Start Learning Free</a>
                    <a href="courses.php" class="btn btn-outline" style="padding: 1rem 2.5rem; font-size: 1.1rem; border-radius: 50px;"><i class="fas fa-play" style="font-size: 0.8rem; margin-right: 10px;"></i> Watch Video</a>
                </div>
                
                <div style="margin-top: 60px; display: flex; align-items: center; gap: 40px;">
                    <div>
                        <h2 style="font-size: 2.5rem; font-weight: 700; margin: 0; color: var(--text-main);">250+</h2>
                        <span style="color: var(--text-muted); font-size: 0.9rem;">Expert Instructors</span>
                    </div>
                    <div style="width: 1px; height: 40px; background: rgba(255,255,255,0.1);"></div>
                    <div>
                        <h2 style="font-size: 2.5rem; font-weight: 700; margin: 0; color: var(--text-main);">15k</h2>
                        <span style="color: var(--text-muted); font-size: 0.9rem;">Course Reviews</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Visual -->
            <div style="position: relative;" class="animate-float">
                <!-- Main Card -->
                <div class="glass-card" style="padding: 30px; position: relative; z-index: 2; border: 1px solid rgba(255,255,255,0.1); transform: rotate(-2deg); transition: transform 0.5s;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
                        <h3 style="margin: 0; font-size: 1.5rem;">Web Development</h3>
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);">
                            <i class="fas fa-code"></i>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 5px; margin-bottom: 20px;">
                        <div style="flex: 1; height: 6px; background: var(--bg-surface); border-radius: 3px;"></div>
                        <div style="flex: 1; height: 6px; background: var(--bg-surface); border-radius: 3px;"></div>
                        <div style="flex: 1; height: 6px; background: var(--bg-surface); border-radius: 3px;"></div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
                        <img src="https://i.pravatar.cc/100?img=33" alt="" style="width: 45px; height: 45px; border-radius: 50%; border: 2px solid var(--primary);">
                        <div>
                            <h5 style="margin: 0; font-weight: 600;">Sarah Johnson</h5>
                            <span style="color: var(--text-muted); font-size: 0.8rem;">Sent a lesson update</span>
                        </div>
                    </div>
                    
                    <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <span style="display: block; font-size: 0.8rem; color: var(--text-muted);">Progress</span>
                            <strong style="color: var(--success);">98% Complete</strong>
                        </div>
                        <div style="width: 40px; height: 40px; border-radius: 50%; border: 3px solid var(--success); display: flex; align-items: center; justify-content: center; color: var(--success);">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Elements -->
                <div class="glass" style="position: absolute; top: -30px; right: -20px; padding: 15px 25px; border-radius: 20px; z-index: 3; animation: float 6s infinite reverse;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-star" style="color: #fbbf24;"></i>
                        <span style="font-weight: 700;">4.9 Rating</span>
                    </div>
                </div>

                <div class="glass" style="position: absolute; bottom: -40px; left: -30px; padding: 20px; border-radius: 20px; z-index: 3; display: flex; gap: 15px; align-items: center;">
                    <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: white;">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0;">New Certificate</h4>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Just earned by Alex</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>


<!-- Features Section -->
<section style="padding: 80px 0;">
    <div class="container">
        <h2 class="section-title">Why Choose Us?</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
            <div class="glass-card" style="text-align: center;">
                <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 20px;"><i class="fas fa-chalkboard-teacher"></i></div>
                <h3>Expert Instructors</h3>
                <p style="color: var(--text-muted); margin-top: 10px;">Learn from industry leaders who have real-world experience.</p>
            </div>
            <div class="glass-card" style="text-align: center;">
                <div style="font-size: 2.5rem; color: var(--secondary); margin-bottom: 20px;"><i class="fas fa-certificate"></i></div>
                <h3>Earn Certificates</h3>
                <p style="color: var(--text-muted); margin-top: 10px;">Get recognized for your achievements with verifiable certificates.</p>
            </div>
            <div class="glass-card" style="text-align: center;">
                <div style="font-size: 2.5rem; color: var(--accent); margin-bottom: 20px;"><i class="fas fa-clock"></i></div>
                <h3>Lifetime Access</h3>
                <p style="color: var(--text-muted); margin-top: 10px;">Learn at your own pace with unlimited access to courses.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses -->
<section style="padding: 80px 0; background: linear-gradient(180deg, transparent 0%, rgba(15, 23, 42, 0.5) 100%);">
    <div class="container">
        <h2 class="section-title">Popular Courses</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php
            $sql = "SELECT c.*, u.name as instructor_name FROM courses c JOIN users u ON c.instructor_id = u.id WHERE c.status = 'published' LIMIT 3";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="glass-card" style="padding: 0; overflow: hidden;">
                        <div style="height: 180px; background: #333; position: relative;">
                            <?php if($row['thumbnail']): ?>
                                <img src="<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
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
                // Dummy Data Logic if no courses exist yet
                for ($i = 1; $i <= 3; $i++) {
                    ?>
                    <div class="glass-card" style="padding: 0; overflow: hidden;">
                        <div style="height: 180px; background: linear-gradient(45deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: rgba(255,255,255,0.5);">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div style="padding: 20px;">
                            <h3 style="margin-bottom: 10px;">Complete Web Development Bootcamp 2026</h3>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px;">Become a full-stack web developer with just one course.</p>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 1.2rem; font-weight: 700; color: var(--primary);">$99.00</span>
                                <a href="#" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Enroll Now</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
         <div style="text-align: center; margin-top: 50px;">
            <a href="courses.php" class="btn btn-primary" style="padding: 1rem 3rem;">View All Courses</a>
        </div>
    </div>
</section>

</body>
</html>
