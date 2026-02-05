<?php
include 'includes/db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=Please login to enroll");
    exit;
}

$user_id = $_SESSION['user_id'];
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($course_id > 0) {
    // 1. Check if already enrolled
    $check = $conn->query("SELECT * FROM enrollments WHERE user_id = $user_id AND course_id = $course_id");
    
    if ($check && $check->num_rows > 0) {
        $enrollment = $check->fetch_assoc();
        if ($enrollment['status'] == 'active') {
            header("Location: course_player.php?id=$course_id");
        } else {
            header("Location: student/dashboard.php?msg=pending_approval");
        }
        exit;
    }

    // 2. Get course price
    $course_res = $conn->query("SELECT price FROM courses WHERE id = $course_id");
    $course = $course_res->fetch_assoc();
    
    if (!$course) {
        die("Course not found.");
    }

    $price = $course['price'];

    // 3. Create pending enrollment and payment
    
    $conn->begin_transaction();

    try {
        // Create enrollment with pending status
        $status_enroll = 'pending';
        $stmt_enroll = $conn->prepare("INSERT INTO enrollments (user_id, course_id, status) VALUES (?, ?, ?)");
        $stmt_enroll->bind_param("iis", $user_id, $course_id, $status_enroll);
        $stmt_enroll->execute();

        // Create a record in payments table (pending)
        $stmt_pay = $conn->prepare("INSERT INTO payments (user_id, course_id, amount, status) VALUES (?, ?, ?, ?)");
        $status_pay = 'pending';
        $stmt_pay->bind_param("iids", $user_id, $course_id, $price, $status_pay);
        $stmt_pay->execute();

        $conn->commit();
        header("Location: student/dashboard.php?msg=enroll_success_pending");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        die("Enrollment failed: " . $e->getMessage());
    }

} else {
    header("Location: courses.php");
}
?>
