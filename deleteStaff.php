<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit;
}

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id <= 0) {
    header("Location: view_staff.php");
    exit;
}

$sql = "DELETE FROM users WHERE user_id = '$user_id' LIMIT 1";

if (mysqli_query($conn, $sql)) {
    header("Location: view_staff.php");
    exit;
} else {
    header("Location: view_staff.php?error=delete_failed");
    exit;
}
?>