<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit;
}

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($course_id > 0) {
    mysqli_query($conn, "DELETE FROM courses WHERE course_id = '$course_id' LIMIT 1");
}

header("Location: list_courses.php");
exit;
?>