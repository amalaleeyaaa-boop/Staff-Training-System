<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "staff_training_system";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("<div style='padding:20px; color:#721c24; background:#f8d7da; border-radius:8px;'>❌ Sambungan ke pangkalan data gagal: " . mysqli_connect_error() . "</div>");
}
?>