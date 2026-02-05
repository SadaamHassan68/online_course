<?php
include 'includes/db.php';

// Add status column to enrollments table if it doesn't exist
$sql = "SHOW COLUMNS FROM enrollments LIKE 'status'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    $alterSql = "ALTER TABLE enrollments ADD COLUMN status ENUM('pending', 'active', 'rejected', 'canceled') DEFAULT 'pending'";
    if ($conn->query($alterSql) === TRUE) {
        echo "Successfully added 'status' column to enrollments table.<br>";
    } else {
        echo "Error adding column: " . $conn->error . "<br>";
    }
} else {
    echo "'status' column already exists in enrollments table.<br>";
}

echo "Database update check complete.";
?>
