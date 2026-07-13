<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit;
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $purpose = mysqli_real_escape_string($conn, trim($_POST['tujuan']));
    $user_id = $_SESSION['user_id'];

    if (!empty($course_id) && !empty($purpose)) {
        $sql = "INSERT INTO applications (staff_id, course_id, tujuan)
                VALUES ('$user_id', '$course_id', '$purpose')";
        if (mysqli_query($conn, $sql)) {
            $message = "<div class='w3-panel w3-green w3-round-large w3-padding-16'>✅ Application submitted successfully!</div>";
        } else {
            $message = "<div class='w3-panel w3-red w3-round-large w3-padding-16'>❌ Error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='w3-panel w3-orange w3-round-large w3-padding-16'>⚠️ Please fill in all required fields marked with *</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Course | Staff Management & Training System</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #667eea;
            --background: #f0f4f8;
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
        input, select, textarea {
            box-sizing: border-box;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="w3-bar top-bar w3-padding-16">
    <span class="w3-bar-item w3-xxlarge w3-text-white"><b><i class="fa fa-file-alt w3-margin-right"></i> Course Application Form</b></span>
    <div class="w3-right w3-padding-8">
        <a href="index.php" class="w3-bar-item w3-button w3-white w3-round-large w3-margin-right">
            <i class="fa fa-arrow-left"></i> Back
        </a>
        <a href="sign_out.php" class="w3-bar-item w3-button btn-logout">
            <i class="fa fa-sign-out-alt w3-margin-right"></i> Logout
        </a>
    </div>
</div>

<div class="w3-container w3-content w3-padding-32" style="max-width: 750px;">
    <div class="w3-card-4 w3-white w3-padding-36 w3-round-large w3-shadow-3">
        <h2 class="w3-center w3-text-dark-grey w3-margin-bottom-28"><b>Apply to Join a Course</b></h2>
        <hr class="w3-margin-bottom-28">

        <?php echo $message; ?>

        <form method="post" class="w3-container">
            <label class="w3-text-dark-grey w3-large"><b>Select Course *</b></label>
            <select name="course_id" class="w3-select w3-border w3-round-large w3-padding-16 w3-margin-bottom-24" required>
                <option value="">-- Please Select --</option>
                <?php
                $sql = "SELECT course_id, course_name, lecturer, start_date FROM courses ORDER BY start_date DESC";
                $courses = mysqli_query($conn, $sql);
                if ($courses && mysqli_num_rows($courses) > 0) {
                    while ($c = mysqli_fetch_assoc($courses)) {
                        echo "<option value='{$c['course_id']}'>
                                {$c['course_name']} - Lecturer: {$c['lecturer']}
                              </option>";
                    }
                } else {
                    echo "<option value='' disabled>⚠️ No courses available at the moment</option>";
                }
                ?>
            </select>

            <label class="w3-text-dark-grey w3-large"><b>Purpose of Joining *</b></label>
            <textarea name="tujuan" rows="6" class="w3-input w3-border w3-round-large w3-padding-16 w3-margin-bottom-24" placeholder="Explain why you wish to join this course..." required></textarea>

            <button type="submit" class="w3-button w3-teal w3-block w3-round-large w3-padding-18 w3-hover-shadow w3-xlarge w3-margin-top-16">
                <i class="fa fa-paper-plane w3-margin-right"></i> Submit Application
            </button>
        </form>
    </div>
</div>

</body>
</html>