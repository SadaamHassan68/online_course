<?php
include 'includes/db.php';

// 1. Get the latest course
$sql_course = "SELECT id, title FROM courses ORDER BY id DESC LIMIT 1";
$res_course = $conn->query($sql_course);

if ($res_course->num_rows == 0) {
    die("No courses found. Please create a course first via the instructor panel.");
}

$course = $res_course->fetch_assoc();
$course_id = $course['id'];
echo "Adding videos to course: " . $course['title'] . " (ID: $course_id)<br>";

// 2. Create a Section for these videos
$section_title = "Module 1: Introduction to Web Development";
$conn->query("INSERT INTO sections (course_id, title, sort_order) VALUES ($course_id, '$section_title', 1)");
$section_id = $conn->insert_id;
echo "Created Section: $section_title (ID: $section_id)<br>";

// 3. Add 5 Video Lessons
$videos = [
    [
        'title' => '1. Course Overview',
        'content' => 'https://www.youtube.com/embed/cbH2QlQdJ2M', // Example video
        'type' => 'video'
    ],
    [
        'title' => '2. Setting Up Your Environment',
        'content' => 'https://www.youtube.com/embed/cbH2QlQdJ2M',
        'type' => 'video'
    ],
    [
        'title' => '3. HTML5 Basics',
        'content' => 'https://www.youtube.com/embed/cbH2QlQdJ2M',
        'type' => 'video'
    ],
    [
        'title' => '4. CSS Styling Fundamentals',
        'content' => 'https://www.youtube.com/embed/cbH2QlQdJ2M',
        'type' => 'video'
    ],
    [
        'title' => '5. Your First Webpage',
        'content' => 'https://www.youtube.com/embed/cbH2QlQdJ2M',
        'type' => 'video'
    ]
];

$order = 1;
foreach ($videos as $video) {
    $stmt = $conn->prepare("INSERT INTO lessons (section_id, title, type, content, sort_order) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $section_id, $video['title'], $video['type'], $video['content'], $order);
    $stmt->execute();
    echo "Added Lesson: " . $video['title'] . "<br>";
    $order++;
}

echo "<br>All 5 sample videos added successfully! <a href='course_player.php?id=$course_id'>Go to Course Player</a>";
?>
