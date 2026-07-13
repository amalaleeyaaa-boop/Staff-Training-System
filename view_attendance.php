<?php
session_start();
include 'config.php';

// Check if user is logged in
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
    <title>Attendance List | Staff Management & Training System</title>
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
        .status-present { color: #28a745; font-weight: bold; }
        .status-absent { color: #dc3545; font-weight: bold; }
        .status-leave { color: #ffc107; font-weight: bold; }
        .status-duty { color: #17a2b8; font-weight: bold; }
    </style>
</head>
<body>

<div class="w3-bar top-bar w3-padding-16">
    <span class="w3-bar-item w3-xxlarge w3-text-white"><b><i class="fa fa-list-alt w3-margin-right"></i> Attendance List</b></span>
    <div class="w3-right w3-padding-8">
        <a href="index.php" class="w3-bar-item w3-button w3-white w3-round-large w3-margin-right">
            <i class="fa fa-arrow-left"></i> Back
        </a>
        <a href="sign_out.php" class="w3-bar-item w3-button btn-logout">
            <i class="fa fa-sign-out-alt w3-margin-right"></i> Logout
        </a>
    </div>
</div>

<div class="w3-container w3-content w3-padding-32" style="max-width: 950px;">
    <div class="w3-card-4 w3-white w3-padding-32 w3-round-large w3-shadow-3">
        <h2 class="w3-center w3-text-dark-grey w3-margin-bottom-24"><b>Latest Attendance Records</b></h2>
        <hr class="w3-margin-bottom-24">

        <?php
        // === UBAH DI SINI: Gabung jadual supaya hanya ambil ID yang berdaftar sahaja ===
        $sql = "SELECT a.* 
                FROM attendance a
                INNER JOIN users u ON a.staff_id = u.staff_id
                ORDER BY a.tarikh DESC";
        // =================================================================================

        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
        ?>
        <table class="w3-table w3-striped w3-bordered w3-hoverable w3-centered w3-round-large">
            <tr>
                <th>No.</th>
                <th>Staff ID</th>
                <th>Date</th>
                <th>Attendance Status</th>
            </tr>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($res)) {
                if ($row['status'] == "Hadir") {
                    $class = "status-present";
                    $display_status = "Present";
                } elseif ($row['status'] == "Tidak Hadir") {
                    $class = "status-absent";
                    $display_status = "Absent";
                } elseif ($row['status'] == "Cuti") {
                    $class = "status-leave";
                    $display_status = "On Leave";
                } elseif ($row['status'] == "Tugas Rasmi") {
                    $class = "status-duty";
                    $display_status = "Official Duty";
                } else {
                    $class = "";
                    $display_status = $row['status'];
                }
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['staff_id']); ?></td>
                <td><?php echo date("d/m/Y", strtotime($row['tarikh'])); ?></td>
                <td class="<?php echo $class; ?>"><?php echo $display_status; ?></td>
            </tr>
            <?php } ?>
        </table>
        <?php
        } else {
            echo "<div class='w3-panel w3-light-grey w3-center w3-padding-24 w3-round-large'>
                    <i class='fa fa-info-circle w3-xxlarge w3-text-grey'></i><br>
                    <h4>No attendance records found for registered staff.</h4>
                    <a href='add_attendance.php' class='w3-button w3-orange w3-round-large w3-margin-top'>Add New Record</a>
                  </div>";
        }
        ?>
    </div>
</div>

</body>
</html>