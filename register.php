<?php
include 'includes/db.php';
session_start();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'student'; // Default role

    if (!empty($name) && !empty($email) && !empty($password)) {
        if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            // Check if email exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Email already registered.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                $insert->bind_param("ssss", $name, $email, $hashed_password, $role);
                
                if ($insert->execute()) {
                    $success = "Registration successful! You can now login.";
                } else {
                    $error = "Error: " . $conn->error;
                }
                $insert->close();
            }
            $stmt->close();
        }
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - LearnPro</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: radial-gradient(circle at bottom right, #1e293b, #0f172a);
        }
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="bg-gradient-circle" style="bottom: 50%; right: 50%; transform: translate(50%, 50%); width: 600px; height: 600px; opacity: 0.3;"></div>

    <div class="login-container">
        <div class="glass-card">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2 class="gradient-text" style="font-size: 2rem; margin-bottom: 10px;">Create Account</h2>
                <p style="color: var(--text-muted);">Start your learning journey today</p>
            </div>

            <?php if ($error): ?>
                <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--danger); padding: 10px; border-radius: var(--radius-sm); color: #fca5a5; margin-bottom: 20px; text-align: center;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--success); padding: 10px; border-radius: var(--radius-sm); color: #6ee7b7; margin-bottom: 20px; text-align: center;">
                    <?php echo $success; ?> <a href="login.php" style="color: #fff; text-decoration: underline;">Login here</a>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <div style="position: relative;">
                        <i class="fas fa-user" style="position: absolute; left: 15px; top: 12px; color: var(--text-muted);"></i>
                        <input type="text" name="name" class="form-control" placeholder="John Doe" style="padding-left: 45px;" required>
                    </div>
                </div>

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
                
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <div style="position: relative;">
                        <i class="fas fa-lock" style="position: absolute; left: 15px; top: 12px; color: var(--text-muted);"></i>
                        <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" style="padding-left: 45px;" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Sign Up</button>
            </form>

            <div style="text-align: center; margin-top: 20px; color: var(--text-muted); font-size: 0.9rem;">
                Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 600;">Log in</a>
            </div>
        </div>
    </div>
</body>
</html>
