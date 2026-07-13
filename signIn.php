<?php
session_start();
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT user_id, name, role FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['role'] = $row['role'];
        header("Location: index.php");
        exit;
    } else {
        $message = "<div class='w3-panel w3-red w3-round w3-padding'>❌ Invalid email or password!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Staff Management System</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body>

<div class="w3-container w3-content w3-padding-64" style="max-width:420px;">
    <div class="w3-card-4 w3-white w3-padding-32 w3-round-xlarge w3-shadow-4">
        <div class="w3-center">
            <i class="fa fa-user-circle w3-text-blue w3-jumbo"></i>
            <h2 class="w3-text-dark-grey w3-margin-top"><b>Login</b></h2>
            <p class="w3-opacity">Staff Information Management System</p>
        </div>
        <hr class="w3-margin-bottom">

        <?php echo $message; ?>

        <form method="post" class="w3-container">
            <label class="w3-text-dark-grey"><b>Email Address</b></label>
            <div class="w3-input-group w3-border w3-round w3-margin-bottom">
                <i class="fa fa-envelope w3-padding w3-text-grey"></i>
                <input type="email" name="email" class="w3-input w3-border-0" placeholder="example@gmail.com" required>
            </div>

            <label class="w3-text-dark-grey"><b>Password</b></label>
            <div class="w3-input-group w3-border w3-round w3-margin-bottom">
                <i class="fa fa-lock w3-padding w3-text-grey"></i>
                <input type="password" name="password" class="w3-input w3-border-0" placeholder="Enter your password" required>
            </div>

            <button class="w3-button w3-blue w3-block w3-round-large w3-padding w3-hover-shadow w3-margin-top" type="submit">
                <i class="fa fa-sign-in-alt"></i> Login
            </button>
        </form>
    </div>
</div>

</body>
</html>