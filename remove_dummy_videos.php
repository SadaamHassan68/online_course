<?php
include 'includes/db.php';

$titles = [
    '1. Course Overview',
    '2. Setting Up Your Environment',
    '3. HTML5 Basics',
    '4. CSS Styling Fundamentals',
    '5. Your First Webpage'
];

$titles_str = "'" . implode("','", $titles) . "'";

// Delete the lessons
$sql = "DELETE FROM lessons WHERE title IN ($titles_str)";
if ($conn->query($sql) === TRUE) {
    echo "Deleted " . $conn->affected_rows . " video lessons.<br>";
} else {
    echo "Error deleting lessons: " . $conn->error . "<br>";
}

// Delete the section if empty (Optional, but good for cleanup)
$section_title = "Module 1: Introduction to Web Development";
// First check if it exists and has no other lessons
$check_sql = "SELECT id FROM sections WHERE title = '$section_title'";
$res = $conn->query($check_sql);
if ($res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        $sec_id = $row['id'];
        $count_sql = "SELECT COUNT(*) as c FROM lessons WHERE section_id = $sec_id";
        $count = $conn->query($count_sql)->fetch_assoc()['c'];
        
        if ($count == 0) {
            $conn->query("DELETE FROM sections WHERE id = $sec_id");
            echo "Deleted empty section: $section_title (ID: $sec_id)<br>";
        }
    }
}

echo "Cleanup complete.";
?>
