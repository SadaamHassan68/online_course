<div class="admin-sidebar glass" style="width: 250px; height: 100vh; position: fixed; top: 0; left: 0; padding: 20px; display: flex; flex-direction: column; z-index: 1001; border-right: 1px solid rgba(255,255,255,0.1);">
    <div style="margin-bottom: 40px; text-align: center;">
        <a href="../index.php" class="logo" style="font-size: 1.5rem; justify-content: center;">
            <i class="fas fa-graduation-cap"></i>
            <span>LearnPro</span>
        </a>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 5px;">INSTRUCTOR PANEL</div>
    </div>

    <ul class="admin-nav" style="display: flex; flex-direction: column; gap: 10px; flex: 1;">
        <li>
            <a href="dashboard.php" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 10px; color: var(--text-main); transition: all 0.3s;">
                <i class="fas fa-th-large" style="width: 20px;"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="create_course.php" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 10px; color: var(--text-main); transition: all 0.3s;">
                <i class="fas fa-plus-circle" style="width: 20px;"></i> Create Course
            </a>
        </li>
        <li>
            <a href="dashboard.php#my-courses" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 10px; color: var(--text-main); transition: all 0.3s;">
                <i class="fas fa-book-reader" style="width: 20px;"></i> My Courses
            </a>
        </li>
        <li>
            <a href="#" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 10px; color: var(--text-main); transition: all 0.3s; opacity: 0.5;">
                <i class="fas fa-users" style="width: 20px;"></i> Students
            </a>
        </li>
        <li>
            <a href="#" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 10px; color: var(--text-main); transition: all 0.3s; opacity: 0.5;">
                <i class="fas fa-wallet" style="width: 20px;"></i> Earnings
            </a>
        </li>
    </ul>

    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
            <div style="width: 35px; height: 35px; background: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-chalkboard-teacher text-white"></i>
            </div>
            <div>
                <div style="font-size: 0.9rem; font-weight: 600;"><?php echo explode(' ', $_SESSION['name'])[0]; ?></div>
                <div style="font-size: 0.7rem; color: var(--accent);">Instructor</div>
            </div>
        </div>
        <a href="../logout.php" class="btn btn-outline" style="width: 100%; border-color: var(--danger); color: var(--danger); gap: 10px;">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<style>
    .admin-nav .nav-link:hover {
        background: rgba(255,255,255,0.05);
        color: var(--primary) !important;
    }
</style>
