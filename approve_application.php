<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header("Location: signIn.php");
    exit;
}

if (isset($_GET['action'], $_GET['app_id'])) {
    $app_id = (int)$_GET['app_id'];
    $status = ($_GET['action'] == 'approve') ? 'Diluluskan' : 'Ditolak';
    mysqli_query($conn, "UPDATE applications SET status = '$status' WHERE app_id = '$app_id'");
    header("Location: approve_application.php");
    exit;
}

$sql = "SELECT a.*, u.name, c.course_name 
        FROM applications a
        JOIN users u ON a.staff_id = u.user_id
        JOIN courses c ON a.course_id = c.course_id
        ORDER BY a.tarikh_mohon DESC";
$res = mysqli_query($conn, $sql);

if (!$res) {
    die("Ralat pangkalan data: " . mysqli_error($conn));
}

$no = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Applications | Staff Management & Training System</title>
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
        .status-pending { color: #ff9800; font-weight: bold; }
        .status-approved { color: #28a745; font-weight: bold; }
        .status-rejected { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

<div class="w3-bar top-bar w3-padding-16">
    <span class="w3-bar-item w3-xxlarge w3-text-white"><b><i class="fa fa-check-double w3-margin-right"></i> Manage Course Applications</b></span>
    <div class="w3-right w3-padding-8">
        <a href="index.php" class="w3-bar-item w3-button w3-white w3-round-large w3-margin-right">
            <i class="fa fa-arrow-left"></i> Back
        </a>
        <a href="sign_out.php" class="w3-bar-item w3-button btn-logout">
            <i class="fa fa-sign-out-alt w3-margin-right"></i> Logout
        </a>
    </div>
</div>

<div class="w3-container w3-content w3-padding-32" style="max-width: 1100px;">
    <div class="w3-card-4 w3-white w3-padding-32 w3-round-large w3-shadow-3">
        <h2 class="w3-center w3-text-dark-grey w3-margin-bottom-24"><b>Application List</b></h2>
        <hr class="w3-margin-bottom-24">

        <table class="w3-table w3-striped w3-bordered w3-hoverable w3-centered w3-round-large">
            <tr>
                <th>No.</th>
                <th>Staff Name</th>
                <th>Course Name</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                <td><?php echo htmlspecialchars(substr($row['tujuan'], 0, 50)) . "..."; ?></td>
                <td class="<?php 
                    if ($row['status'] == 'Menunggu') echo 'status-pending';
                    elseif ($row['status'] == 'Diluluskan') echo 'status-approved';
                    else echo 'status-rejected';
                ?>">
                <?php 
                    if ($row['status'] == 'Menunggu') echo 'Pending';
                    elseif ($row['status'] == 'Diluluskan') echo 'Approved';
                    else echo 'Rejected';
                ?>
                </td>
                <td>
                    <?php if ($row['status'] == 'Menunggu'): ?>
                    <a href="?action=approve&app_id=<?php echo $row['app_id']; ?>" class="w3-button w3-small w3-green w3-round w3-hover-shadow">✅ Approve</a>
                    <a href="?action=reject&app_id=<?php echo $row['app_id']; ?>" class="w3-button w3-small w3-red w3-round w3-hover-shadow">❌ Reject</a>
                    <?php else: ?>
                    -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

</body>
</html>