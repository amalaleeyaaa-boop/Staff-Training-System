<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: signIn.php");
    exit;
}

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$course_title = "All Courses";

if ($course_id > 0) {
    $check = mysqli_query($conn, "SELECT course_name FROM courses WHERE course_id = '$course_id' LIMIT 1");
    if ($data = mysqli_fetch_assoc($check)) {
        $course_title = $data['course_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Participants | Staff Management & Training System</title>
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
        .w3-table th {
            background-color: #2d3748;
            color: white;
        }
    </style>
</head>
<body>

<div class="w3-bar top-bar w3-padding-16">
    <span class="w3-bar-item w3-xxlarge w3-text-white"><b><i class="fa fa-users w3-margin-right"></i> Course Participants</b></span>
    <div class="w3-right w3-padding-8">
        <a href="list_courses.php" class="w3-bar-item w3-button w3-white w3-round-large w3-margin-right">
            <i class="fa fa-arrow-left"></i> Back
        </a>
        <a href="sign_out.php" class="w3-bar-item w3-button btn-logout">
            <i class="fa fa-sign-out-alt w3-margin-right"></i> Logout
        </a>
    </div>
</div>

<div class="w3-container w3-content w3-padding-32" style="max-width: 1000px;">
    <h2 class="w3-center w3-text-dark-grey w3-margin-bottom-24"><b>Participants: <?php echo htmlspecialchars($course_title); ?></b></h2>
    <hr class="w3-margin-bottom-24">

    <div class="w3-card-4 w3-white w3-padding-32 w3-round-large w3-shadow-3">
        <table class="w3-table w3-striped w3-bordered w3-hoverable w3-centered w3-round-large">
            <tr>
                <th>No.</th>
                <th>Staff Name</th>
                <th>Staff ID</th>
                <th>Department</th>
                <th>Application Date</th>
                <th>Status</th>
            </tr>
            <?php
            if ($course_id > 0) {
                $sql = "SELECT a.*, u.name, u.staff_id, u.department 
                        FROM applications a
                        JOIN users u ON a.staff_id = u.user_id
                        WHERE a.course_id = '$course_id'
                        ORDER BY a.tarikh_mohon DESC";
            } else {
                $sql = "SELECT a.*, u.name, u.staff_id, u.department, c.course_name
                        FROM applications a
                        JOIN users u ON a.staff_id = u.user_id
                        JOIN courses c ON a.course_id = c.course_id
                        ORDER BY a.tarikh_mohon DESC";
            }

            $res = mysqli_query($conn, $sql);
            $no = 1;
            if ($res && mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    if ($row['status'] == 'Menunggu') {
                        $text_class = 'w3-text-orange';
                        $display_status = 'Pending';
                    } elseif ($row['status'] == 'Diluluskan') {
                        $text_class = 'w3-text-green';
                        $display_status = 'Approved';
                    } else {
                        $text_class = 'w3-text-red';
                        $display_status = 'Rejected';
                    }
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['staff_id']); ?></td>
                <td><?php echo htmlspecialchars($row['department']); ?></td>
                <td><?php echo date("d/m/Y", strtotime($row['tarikh_mohon'])); ?></td>
                <td class="<?php echo $text_class; ?>"><b><?php echo $display_status; ?></b></td>
            </tr>
            <?php }
            } else {
                echo "<tr><td colspan='6' class='w3-center w3-padding-24 w3-text-grey'>❌ No participants have registered for this course yet.</td></tr>";
            } ?>
        </table>
    </div>
</div>

</body>
</html>