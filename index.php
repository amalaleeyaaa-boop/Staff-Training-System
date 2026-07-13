<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu | Staff Management & Training System</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --main-color: #667eea;
            --bg-color: #f0f4f8;
        }
        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            margin: 0;
            padding: 0;
        }
        .top-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }
        .menu-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px 15px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: none;
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            background: #f8fbfe;
        }
        .menu-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
        .menu-text {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }
        .btn-logout {
            background: #ffffff;
            color: #dc3545;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
            transition: 0.2s;
        }
        .btn-logout:hover {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

<div class="w3-bar top-bar w3-padding-16">
    <span class="w3-bar-item w3-xxlarge w3-text-white"><b><i class="fa fa-home w3-margin-right"></i> Staff Management & Training System</b></span>
    <div class="w3-right w3-padding-8">
        <span class="w3-bar-item w3-text-white w3-large">👋 Welcome, <b><?php echo $_SESSION['name']; ?></b> (<?php echo ($_SESSION['role'] == 'admin') ? 'Administrator' : 'Staff'; ?>)</span>
        <a href="sign_out.php" class="w3-bar-item w3-button btn-logout w3-margin-right">
            <i class="fa fa-sign-out-alt w3-margin-right"></i> Logout
        </a>
    </div>
</div>

<div class="w3-container w3-content w3-padding-32" style="max-width: 1050px;">
    <h2 class="w3-center w3-text-dark-grey w3-margin-bottom-32"><b>Main Menu</b></h2>

    <div class="w3-row-padding w3-margin-bottom">

        <!-- ✅ MANAGE STAFF - ADMIN ONLY -->
        <?php 
        if ($_SESSION['role'] == 'admin') {
        ?>
        <div class="w3-third w3-margin-bottom">
            <a href="view_staff.php" class="w3-button w3-block menu-card">
                <i class="fa fa-users menu-icon w3-text-blue"></i>
                <p class="menu-text">Manage Staff</p>
            </a>
        </div>
        <?php 
        }
        ?>

        <!-- Manage Courses -->
        <div class="w3-third w3-margin-bottom">
            <a href="list_courses.php" class="w3-button w3-block menu-card">
                <i class="fa fa-book menu-icon w3-text-green"></i>
                <p class="menu-text">Manage Courses</p>
            </a>
        </div>

        <!-- Apply for Course -->
        <div class="w3-third w3-margin-bottom">
            <a href="apply_course.php" class="w3-button w3-block menu-card">
                <i class="fa fa-file-alt menu-icon w3-text-teal"></i>
                <p class="menu-text">Apply for Course</p>
            </a>
        </div>

        <!-- Review & Approve Applications - Admin Only -->
        <?php 
        if ($_SESSION['role'] == 'admin') {
        ?>
        <div class="w3-third w3-margin-bottom">
            <a href="approve_application.php" class="w3-button w3-block menu-card">
                <i class="fa fa-check-double menu-icon w3-text-red"></i>
                <p class="menu-text">Review & Approve Applications</p>
            </a>
        </div>
        <?php 
        }
        ?>

        <!-- Record Attendance -->
        <div class="w3-third w3-margin-bottom">
            <a href="add_attendance.php" class="w3-button w3-block menu-card">
                <i class="fa fa-check-square menu-icon w3-text-orange"></i>
                <p class="menu-text">Record Attendance</p>
            </a>
        </div>

        <!-- View Attendance -->
        <div class="w3-third w3-margin-bottom">
            <a href="view_attendance.php" class="w3-button w3-block menu-card">
                <i class="fa fa-list-alt menu-icon w3-text-cyan"></i>
                <p class="menu-text">View Attendance</p>
            </a>
        </div>

        <!-- Search Staff -->
        <div class="w3-third w3-margin-bottom">
            <a href="searchStaff.php" class="w3-button w3-block menu-card">
                <i class="fa fa-search menu-icon w3-text-purple"></i>
                <p class="menu-text">Search Staff</p>
            </a>
        </div>

        <!-- My Courses -->
        <div class="w3-third w3-margin-bottom">
            <a href="list_staff_course.php" class="w3-button w3-block menu-card">
                <i class="fa fa-graduation-cap menu-icon w3-text-indigo"></i>
                <p class="menu-text">My Courses</p>
            </a>
        </div>

    </div>
</div>

</body>
</html>