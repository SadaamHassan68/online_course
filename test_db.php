<?php
include 'includes/db.php';
$conn->query("SELECT * FROM enrollments LIMIT 1");
if ($conn->error) {
    echo "Enrollments Table Error: " . $conn->error;
} else {
    echo "Enrollments Table OK";
}
echo "<br>";
$conn->query("SELECT * FROM payments LIMIT 1");
if ($conn->error) {
    echo "Payments Table Error: " . $conn->error;
} else {
    echo "Payments Table OK";
}
?>
