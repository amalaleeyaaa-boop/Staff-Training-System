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

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name        = mysqli_real_escape_string($conn, trim($_POST['name']));
    $staff_id    = mysqli_real_escape_string($conn, trim($_POST['staff_id']));
    $email       = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone       = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $department  = mysqli_real_escape_string($conn, trim($_POST['department']));
    $password    = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role        = strtolower(trim($_POST['role']));

    $sql = "INSERT INTO users (name, staff_id, email, phone, department, password, role)
            VALUES ('$name', '$staff_id', '$email', '$phone', '$department', '$password', '$role')";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_staff.php?success=added");
        exit;
    } else {
        $message = "<div class='w3-panel w3-red w3-round-large w3-padding-16 w3-margin-bottom-24'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff | Management System</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #667eea;
            --background: #f0f4f8;
        }
        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .top-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 18px 24px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-bar h1 {
            color: white;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
        }
        .btn-back, .btn-logout {
            background: white;
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: bold;
            text-decoration: none;
            color: #333;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-back:hover { background: #e2e6ea; }
        .btn-logout:hover { background: #dc3545; color: white; }
        .form-container {
            max-width: 750px;
            margin: 32px auto;
            background: white;
            padding: 36px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        .form-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 24px;
        }
        .divider { height: 2px; background: #eee; margin-bottom: 28px; }
        label {
            font-weight: bold;
            color: #444;
            display: block;
            margin-bottom: 8px;
            font-size: 17px;
        }
        input, select {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 24px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }
        .btn-save {
            width: 100%;
            padding: 16px;
            background: #28a745;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 19px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-save:hover { background: #218838; }
    </style>
</head>
<body>

<div class="top-bar">
    <h1><i class="fa fa-user-plus"></i> Add New Staff</h1>
    <div>
        <a href="view_staff.php" class="btn-back"><i class="fa fa-arrow-left"></i> Back</a>
        <a href="sign_out.php" class="btn-logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="form-container">
    <h2>Staff Personal Information</h2>
    <div class="divider"></div>

    <?php echo $message; ?>

    <form method="post">
        <label>Full Name *</label>
        <input type="text" name="name" placeholder="e.g. Ahmad Bin Abu" required>

        <label>Staff ID *</label>
        <input type="text" name="staff_id" placeholder="e.g. STF001" required>

        <label>Email Address *</label>
        <input type="email" name="email" placeholder="example@mail.com" required>

        <label>Phone Number</label>
        <input type="text" name="phone" placeholder="012-3456789">

        <label>Department / Division *</label>
        <input type="text" name="department" placeholder="e.g. Finance / Administration" required>

        <label>Password *</label>
        <input type="password" name="password" placeholder="Enter password" required>

        <label>System Role *</label>
        <select name="role" required>
            <option value="staff">Regular Staff</option>
            <option value="admin">System Administrator</option>
        </select>

        <button type="submit" class="btn-save">
            <i class="fa fa-save"></i> Save Information
        </button>
    </form>
</div>

</body>
</html>