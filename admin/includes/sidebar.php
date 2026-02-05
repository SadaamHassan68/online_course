<div class="admin-sidebar glass" style="width: 250px; height: 100vh; position: fixed; top: 0; left: 0; padding: 20px; display: flex; flex-direction: column; z-index: 1001; border-right: 1px solid rgba(255,255,255,0.1);">
    <div style="margin-bottom: 40px; text-align: center;">
        <a href="../index.php" class="logo" style="font-size: 1.5rem; justify-content: center;">
            <i class="fas fa-graduation-cap"></i>
            <span>LearnPro</span>
        </a>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 5px;">ADMIN PANEL</div>
    </div>

    <ul class="admin-nav" style="display: flex; flex-direction: column; gap: 10px; flex: 1;">
        <li>
            <a href="dashboard.php" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 10px; color: var(--text-main); transition: all 0.3s;">
                <i class="fas fa-chart-line" style="width: 20px;"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="../instructor/create_course.php" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 10px; color: var(--text-main); transition: all 0.3s;">
                <i class="fas fa-plus-circle" style="width: 20px;"></i> Create Course
            </a>
        </li>
        <li>
            <a href="users.php" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 10px; color: var(--text-main); transition: all 0.3s;">
                <i class="fas fa-users" style="width: 20px;"></i> Users
            </a>
        </li>
        <li>
            <a href="courses.php" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 10px; color: var(--text-main); transition: all 0.3s;">
                <i class="fas fa-book" style="width: 20px;"></i> Courses
            </a>
        </li>
         <li>
            <a href="enrollments.php" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 10px; color: var(--text-main); transition: all 0.3s;">
                <i class="fas fa-clipboard-check" style="width: 20px;"></i> Enrollments
            </a>
        </li>
         <li>
            <a href="#" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 12px 15px; border-radius: 10px; color: var(--text-main); transition: all 0.3s; opacity: 0.5; pointer-events: none;">
                <i class="fas fa-cog" style="width: 20px;"></i> Settings
            </a>
        </li>
    </ul>

    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
            <div style="width: 35px; height: 35px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <div style="font-size: 0.9rem; font-weight: 600;">Admin</div>
                <div style="font-size: 0.7rem; color: var(--success);">Online</div>
            </div>
        </div>
        <a href="../logout.php" class="btn btn-outline" style="width: 100%; border-color: var(--danger); color: var(--danger); gap: 10px;">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<style>
    /* Active Link Styling Logic would normally go here or via PHP class logic */
    .admin-nav .nav-link:hover {
        background: rgba(255,255,255,0.05);
        color: var(--primary) !important;
    }
</style>
