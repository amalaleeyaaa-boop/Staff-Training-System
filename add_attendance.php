<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit;
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil input pengguna
    $staff_id_input = trim($_POST['staff_id']);
    $tarikh   = $_POST['tarikh'];
    $status   = $_POST['status'];

    // ===== BAHAGIAN BARU: Semak sama ada Staff ID ada dalam senarai pengguna =====
    $check_sql = "SELECT staff_id FROM users WHERE staff_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt, "s", $staff_id_input);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 0) {
        // Kalau ID tiada dalam sistem
        $message = "<div class='w3-panel w3-red w3-round-large w3-padding-16'>❌ Staff ID <strong>$staff_id_input</strong> is NOT registered! Please enter a valid registered ID.</div>";
    } else {
        // Kalau ID sah, baru masukkan rekod kehadiran
        $staff_id = mysqli_real_escape_string($conn, $staff_id_input);
        $sql = "INSERT INTO attendance (staff_id, tarikh, status) VALUES ('$staff_id', '$tarikh', '$status')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "<div class='w3-panel w3-green w3-round-large w3-padding-16'>✅ Attendance record saved successfully!</div>";
        } else {
            $message = "<div class='w3-panel w3-red w3-round-large w3-padding-16'>❌ Error: " . mysqli_error($conn) . "</div>";
        }
    }
    mysqli_stmt_close($stmt);
    // ============================================================================
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Record | Staff Management & Training System</title>
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
    </style>
</head>
<body>

<div class="w3-bar top-bar w3-padding-16">
    <span class="w3-bar-item w3-xxlarge w3-text-white"><b><i class="fa fa-check-square w3-margin-right"></i> Attendance Record</b></span>
    <div class="w3-right w3-padding-8">
        <a href="index.php" class="w3-bar-item w3-button w3-white w3-round-large w3-margin-right">
            <i class="fa fa-arrow-left"></i> Back
        </a>
        <a href="sign_out.php" class="w3-bar-item w3-button btn-logout">
            <i class="fa fa-sign-out-alt w3-margin-right"></i> Logout
        </a>
    </div>
</div>

<div class="w3-container w3-content w3-padding-32" style="max-width: 650px;">
    <div class="w3-card-4 w3-white w3-padding-32 w3-round-large w3-shadow-3">
        <h2 class="w3-center w3-text-dark-grey w3-margin-bottom-24"><b>Attendance Entry Form</b></h2>
        <hr class="w3-margin-bottom-24">

        <?php echo $message; ?>

        <form method="post" class="w3-container">
            <label class="w3-text-dark-grey"><b>Staff ID / No. *</b></label>
            <input type="text" name="staff_id" class="w3-input w3-border w3-round-large w3-padding w3-margin-bottom" required placeholder="Enter only registered Staff ID">

            <label class="w3-text-dark-grey"><b>Date *</b></label>
            <input type="date" name="tarikh" class="w3-input w3-border w3-round-large w3-padding w3-margin-bottom" required>

            <label class="w3-text-dark-grey"><b>Attendance Status *</b></label>
            <select name="status" class="w3-select w3-border w3-round-large w3-padding w3-margin-bottom" required>
                <option value="Hadir">Present</option>
                <option value="Tidak Hadir">Absent</option>
                <option value="Cuti">On Leave</option>
                <option value="Tugas Rasmi">Official Duty</option>
            </select>

            <button type="submit" class="w3-button w3-orange w3-block w3-round-large w3-padding w3-hover-shadow w3-margin-top">
                <i class="fa fa-save w3-margin-right"></i> Save Record
            </button>
        </form>
    </div>
</div>

</body>
</html>