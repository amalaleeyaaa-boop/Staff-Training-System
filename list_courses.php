<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit;
}

$sql = "SELECT * FROM courses ORDER BY course_id ASC";
$result = mysqli_query($conn, $sql);

$is_admin = (isset($_SESSION['role']) && strtolower(trim($_SESSION['role'])) === 'admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course List</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: #f0f4f8; font-family: Arial, sans-serif; margin:0; padding:0; }
        .top-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 18px 24px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .top-bar h1 { margin:0; font-size:28px; }
        .btn-back {
            background: white; border-radius: 25px; padding: 8px 20px;
            text-decoration: none; color: #333; font-weight: bold;
        }
        .add-container {
            max-width: 1100px; margin:24px auto; text-align:right;
        }
        .btn-add {
            background: #28a745; color:white; padding:10px 20px;
            border-radius:8px; text-decoration:none; font-weight:bold;
            display:inline-block;
        }
        .table-container {
            max-width: 1100px; margin:0 auto 40px; background:white;
            border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.1);
            overflow:hidden;
        }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:14px 16px; text-align:left; border-bottom:1px solid #eee; }
        th { background:#f8f9fa; font-weight:bold; color:#333; }
        .actions a { margin-right:8px; text-decoration:none; padding:5px 10px; border-radius:4px; font-size:14px; }
        .edit { background:#f39c12; color:white; }
        .delete { background:#dc3545; color:white; }
        .message {
            max-width:1100px; margin:16px auto; padding:12px;
            border-radius:8px; text-align:center; font-weight:bold;
        }
        .success { background:#d4edda; color:#155724; }
        .no-data {
            padding: 30px; text-align:center; color:#777; font-size:17px;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <h1><i class="fa fa-book"></i> Course List</h1>
    <a href="index.php" class="btn-back"><i class="fa fa-arrow-left"></i> Back</a>
</div>

<?php
if(isset($_GET['success'])) {
    echo "<div class='message success'>";
    if($_GET['success'] == 'added') echo "✅ Course added successfully!";
    if($_GET['success'] == 'updated') echo "✅ Course updated successfully!";
    if($_GET['success'] == 'deleted') echo "✅ Course deleted successfully!";
    echo "</div>";
}
?>

<?php if ($is_admin): ?>
<div class="add-container">
    <a href="add_course.php" class="btn-add"><i class="fa fa-plus"></i> Add New Course</a>
</div>
<?php endif; ?>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Lecturer</th>
                <th>Start Date</th>
                <th>End Date</th>
                <?php if ($is_admin): ?><th>Actions</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while($course = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $course['course_id']; ?></td>
                <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                <td><?php echo htmlspecialchars($course['lecturer']); ?></td>
                <td><?php echo $course['start_date']; ?></td>
                <td><?php echo $course['end_date']; ?></td>
                <?php if ($is_admin): ?>
                <td class="actions">
                    <a href="edit_course.php?id=<?php echo $course['course_id']; ?>" class="edit"><i class="fa fa-edit"></i> Edit</a>
                    <a href="delete_course.php?id=<?php echo $course['course_id']; ?>" class="delete" onclick="return confirm('Delete this course?')"><i class="fa fa-trash"></i> Delete</a>
                </td>
                <?php endif; ?>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='".($is_admin ? '6' : '5')."' class='no-data'>No courses have been added yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>