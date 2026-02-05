<?php
include 'includes/db.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['name'] = $name;
                $_SESSION['role'] = $role;
                
                if ($role == 'admin') {
                    header("Location: admin/dashboard.php");
                } elseif ($role == 'instructor') {
                    header("Location: instructor/dashboard.php");
                } else {
                    header("Location: student/dashboard.php");
                }
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with this email.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LearnPro</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="bg-gradient-circle" style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 600px; height: 600px; opacity: 0.3;"></div>

    <div class="login-container">
        <div class="glass-card">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2 class="gradient-text" style="font-size: 2rem; margin-bottom: 10px;">Welcome Back</h2>
                <p style="color: var(--text-muted);">Login to continue learning</p>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div style="background: rgba(59, 130, 246, 0.2); border: 1px solid var(--primary); padding: 10px; border-radius: var(--radius-sm); color: #93c5fd; margin-bottom: 20px; text-align: center;">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--danger); padding: 10px; border-radius: var(--radius-sm); color: #fca5a5; margin-bottom: 20px; text-align: center;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div style="position: relative;">
                        <i class="fas fa-envelope" style="position: absolute; left: 15px; top: 12px; color: var(--text-muted);"></i>
                        <input type="email" name="email" class="form-control" placeholder="you@example.com" style="padding-left: 45px;" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div style="position: relative;">
                        <i class="fas fa-lock" style="position: absolute; left: 15px; top: 12px; color: var(--text-muted);"></i>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" style="padding-left: 45px;" required>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 0.9rem;">
                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer; color: var(--text-muted);">
                        <input type="checkbox"> Remember me
                    </label>
                    <a href="#" style="color: var(--primary);">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Log In</button>
            </form>

            <div style="text-align: center; margin-top: 20px; color: var(--text-muted); font-size: 0.9rem;">
                Don't have an account? <a href="register.php" style="color: var(--primary); font-weight: 600;">Sign up</a>
            </div>
            
            <div style="margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                 <p style="text-align: center; margin-bottom: 15px; font-size: 0.8em; color: var(--text-muted);">Demo Credentials:</p>
                 <div style="display: grid; gap: 10px; font-size: 0.8em; color: var(--text-muted);">
                     <div style="display:flex; justify-content: space-between;"><span>Admin:</span> <code>admin@example.com / admin123</code></div>
                     <div style="display:flex; justify-content: space-between;"><span>Instructor:</span> <code>instructor@example.com / instructor123</code></div>
                     <div style="display:flex; justify-content: space-between;"><span>Student:</span> <code>student@example.com / student123</code></div>
                 </div>
            </div>
        </div>
    </div>
</body>
</html>
