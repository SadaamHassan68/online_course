<?php
// fix_passwords.php
include 'includes/db.php';

// Credentials to set
$users = [
    'admin@example.com' => 'admin123',
    'instructor@example.com' => 'instructor123',
    'student@example.com' => 'student123'
];

foreach ($users as $email => $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hash, $email);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Updated password for $email to '$password'<br>";
        } else {
            // It might already be correct, or user doesn't exist.
            // Let's try to insert if not exists just in case.
             // (Simple check)
             $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
             if ($check->num_rows > 0) {
                 echo "Password for $email was already correct or unchanged.<br>";
             } else {
                 echo "User $email not found.<br>";
             }
        }
    } else {
        echo "Error updating $email: " . $conn->error . "<br>";
    }
}

echo "Done.";
?>
