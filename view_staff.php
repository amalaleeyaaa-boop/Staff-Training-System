<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit;
}

if (!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header("Location: mainMenu.php");
    exit;
}

if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE user_id = $del_id");
    header("Location: view_staff.php?success=deleted");
    exit;
}

$sql = "SELECT * FROM users ORDER BY user_id ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff List</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --primary: #667eea; --background: #f0f4f8; }
        body { background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%); margin: 0; padding: 0; font-family: Arial, sans-serif; }
        .top-bar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 18px 24px; box-shadow: 0 3px 10px rgba(0,0,0,0.15); display: flex; justify-content: space-between; align-items: center; }
        .top-bar h1 { color: white; font-size: 28px; display: flex; align-items: center; gap: 12px; margin:0; }
        .btn-back, .btn-logout { background: white; border-radius: 25px; padding: 8px 20px; font-weight: bold; text-decoration: none; color: #333; display: inline-flex; align-items: center; gap:6px; }
        .btn-back:hover { background: #e2e6ea; }
        .btn-logout:hover { background: #dc3545; color: white; }
        .content { max-width: 1200px; margin: 32px auto; padding: 0 20px; }
        .btn-add { background: #28a745; color: white; border-radius: 8px; padding: 10px 18px; font-size: 17px; font-weight: bold; text-decoration: none; display: inline-flex; align-items: center; gap:6px; margin-bottom:20px; }
        .btn-add:hover { background: #218838; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .w3-table { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; }
        .w3-table th { background: #2d3748; color: white; padding: 14px; text-align: left; }
        .w3-table td { padding: 14px; border-bottom: 1px solid #eee; }
        .role-badge { padding: 4px 10px; border-radius: 15px; color: white; font-size: 14px; font-weight: bold; }
        .role-admin { background: #007bff; }
        .role-staff { background: #28a745; }
        .btn-edit { background: #ffc107; color: #212529; border-radius: 6px; padding: 5px 8px; text-decoration: none; margin-right: 5px; }
        .btn-delete { background: #dc3545; color: white; border-radius: 6px; padding: 5px 8px; text-decoration: none; }
    </style>
</head>
<body>

<div class="top-bar">
    <h1><i class="fa fa-users"></i> Staff List</h1>
    <div>
        <a href="index.php" class="btn-back"><i class="fa fa-arrow-left"></i> Back</a>
        <a href="sign_out.php" class="btn-logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="content">
    <?php if (isset($_GET['success'])): ?>
        <?php if ($_GET['success'] == 'added'): ?>
            <div class="alert alert-success">✅ Staff member added successfully!</div>
        <?php elseif ($_GET['success'] == 'updated'): ?>
            <div class="alert alert-success">✅ Information updated successfully!</div>
        <?php elseif ($_GET['success'] == 'deleted'): ?>
            <div class="alert alert-success">✅ Staff member deleted successfully!</div>
        <?php endif; ?>
    <?php endif; ?>

    <a href="addStaff.php" class="btn-add">
        <i class="fa fa-plus"></i> Add New Staff
    </a>

    <table class="w3-table w3-striped w3-hoverable">
        <thead>
            <tr>
                <th>No.</th>
                <th>Full Name</th>
                <th>Staff ID</th>
                <th>Email</th>
                <th>Department</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $badge_class = strtolower($row['role']) == 'admin' ? 'role-admin' : 'role-staff';
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['staff_id']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['department']); ?></td>
                <td><span class="role-badge <?php echo $badge_class; ?>"><?php echo ucfirst($row['role']); ?></span></td>
                <td>
                    <a href="editStaff.php?id=<?php echo $row['user_id']; ?>" class="btn-edit" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="?delete=<?php echo $row['user_id']; ?>" class="btn-delete" onclick="return confirm('Delete this record?')" title="Delete"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>