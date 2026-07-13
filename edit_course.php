<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit;
}

if (!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: list_courses.php");
    exit;
}

$old_course_id = (int)$_GET['id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_course_id = mysqli_real_escape_string($conn, trim($_POST['course_id']));
    $course_name   = mysqli_real_escape_string($conn, trim($_POST['course_name']));
    $lecturer      = mysqli_real_escape_string($conn, trim($_POST['lecturer']));
    $description   = mysqli_real_escape_string($conn, trim($_POST['description']));
    $start_date    = mysqli_real_escape_string($conn, trim($_POST['start_date']));
    $end_date      = mysqli_real_escape_string($conn, trim($_POST['end_date']));

    if ($new_course_id != $old_course_id) {
        $check = mysqli_query($conn, "SELECT course_id FROM courses WHERE course_id = '$new_course_id' LIMIT 1");
        if (mysqli_num_rows($check) > 0) {
            $message = "<div class='w3-panel w3-red w3-round-large w3-padding-16 w3-margin-bottom-24'>❌ Error: Course ID $new_course_id is already in use!</div>";
        }
    }

    if (empty($message)) {
        $sql = "UPDATE courses 
                SET course_id = '$new_course_id', 
                    course_name = '$course_name', 
                    lecturer = '$lecturer', 
                    description = '$description', 
                    start_date = '$start_date', 
                    end_date = '$end_date'
                WHERE course_id = '$old_course_id'";

        if (mysqli_query($conn, $sql)) {
            header("Location: list_courses.php?success=updated");
            exit;
        } else {
            $message = "<div class='w3-panel w3-red w3-round-large w3-padding-16 w3-margin-bottom-24'>❌ Error: " . mysqli_error($conn) . "</div>";
        }
    }
}

$sql = "SELECT * FROM courses WHERE course_id = $old_course_id LIMIT 1";
$result = mysqli_query($conn, $sql);
$course = mysqli_fetch_assoc($result);

if (!$course) {
    header("Location: list_courses.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --primary: #667eea; --background: #f0f4f8; }
        body { background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%); font-family: Arial, sans-serif; margin:0; padding:0; }
        .top-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 18px 24px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-bar h1 { color: white; font-size: 28px; display: flex; align-items: center; gap: 12px; margin:0; }
        .btn-back, .btn-logout {
            background: white; border-radius: 25px; padding: 8px 20px;
            font-weight: bold; text-decoration: none; color: #333;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-back:hover { background: #e2e6ea; }
        .btn-logout:hover { background: #dc3545; color: white; }
        .form-container {
            max-width: 750px; margin: 32px auto; background: white;
            padding: 36px; border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #333; margin-bottom: 24px; }
        .divider { height: 2px; background: #eee; margin-bottom: 28px; }
        label { font-weight: bold; color: #444; display: block; margin-bottom: 8px; font-size: 17px; }
        input, textarea {
            width: 100%; padding: 14px 16px; margin-bottom: 24px;
            border: 1px solid #ddd; border-radius: 10px; font-size: 16px;
            box-sizing: border-box;
        }
        textarea { resize: vertical; min-height: 100px; }
        input:focus, textarea:focus {
            outline: none; border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102,126,234,0.2);
        }
        .btn-save {
            width: 100%; padding: 16px; background: #f39c12; border: none;
            border-radius: 10px; color: white; font-size: 19px; font-weight: bold;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-save:hover { background: #e67e22; }
    </style>
</head>
<body>

<div class="top-bar">
    <h1><i class="fa fa-edit"></i> Edit Course</h1>
    <div>
        <a href="list_courses.php" class="btn-back"><i class="fa fa-arrow-left"></i> Back</a>
        <a href="sign_out.php" class="btn-logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="form-container">
    <h2>Update Course Details</h2>
    <div class="divider"></div>

    <?php echo $message; ?>

    <form method="post">
        <label>Course ID *</label>
        <input type="number" name="course_id" value="<?php echo $course['course_id']; ?>" required>

        <label>Course Name *</label>
        <input type="text" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>

        <label>Lecturer / Instructor *</label>
        <input type="text" name="lecturer" value="<?php echo htmlspecialchars($course['lecturer']); ?>" required>

        <label>Course Description</label>
        <textarea name="description"><?php echo htmlspecialchars($course['description']); ?></textarea>

        <label>Start Date *</label>
        <input type="date" name="start_date" value="<?php echo $course['start_date']; ?>" required>

        <label>End Date *</label>
        <input type="date" name="end_date" value="<?php echo $course['end_date']; ?>" required>

        <button type="submit" class="btn-save">
            <i class="fa fa-save"></i> Save Changes
        </button>
    </form>
</div>

</body>
</html>