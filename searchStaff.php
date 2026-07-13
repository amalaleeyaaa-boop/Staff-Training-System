<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit;
}

$is_admin = (isset($_SESSION['role']) && strtolower(trim($_SESSION['role'])) === 'admin');

$search_results = array();
$keyword = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['keyword']) && trim($_GET['keyword']) !== "") {
    $keyword = trim($_GET['keyword']);
    $keyword_esc = mysqli_real_escape_string($conn, $keyword);

    if ($is_admin) {
        $sql = "SELECT user_id, name, staff_id, email 
                FROM users 
                WHERE staff_id LIKE '%$keyword_esc%' 
                   OR name LIKE '%$keyword_esc%' 
                   OR email LIKE '%$keyword_esc%'
                ORDER BY user_id ASC";
    } else {
        $sql = "SELECT user_id, name, staff_id, email 
                FROM users 
                WHERE user_id = '".$_SESSION['user_id']."'
                  AND (staff_id LIKE '%$keyword_esc%' 
                   OR name LIKE '%$keyword_esc%' 
                   OR email LIKE '%$keyword_esc%')
                ORDER BY user_id ASC";
    }

    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $search_results[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Staff</title>
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
        .search-box {
            max-width: 800px; margin: 32px auto; background: white;
            padding: 24px; border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        .search-form {
            display: flex; gap: 10px;
        }
        .search-form input {
            flex: 1; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px;
        }
        .search-form button {
            padding: 12px 24px; background: #667eea; border: none; border-radius: 8px;
            color: white; font-weight: bold; cursor: pointer;
        }
        .search-form button:hover { background: #5567d0; }
        .result-container {
            max-width: 1000px; margin: 24px auto; background: white;
            border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: bold; color: #333; }
        tr:hover { background: #f8f9ff; }
        .no-result { padding: 30px; text-align: center; color: #777; font-size: 17px; }
    </style>
</head>
<body>

<div class="top-bar">
    <h1><i class="fa fa-search"></i> Search Staff</h1>
    <div>
        <a href="index.php" class="btn-back"><i class="fa fa-arrow-left"></i> Back</a>
        <a href="sign_out.php" class="btn-logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="search-box">
    <form class="search-form" method="get" action="searchStaff.php">
        <input type="text" name="keyword" placeholder="Enter Staff ID, Name or Email..." value="<?php echo htmlspecialchars($keyword); ?>">
        <button type="submit"><i class="fa fa-search"></i> Search</button>
    </form>
</div>

<div class="result-container">
    <?php
    if (!empty($keyword)) {
        if (count($search_results) > 0) {
    ?>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Staff ID</th>
                    <th>Email Address</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($search_results as $staff) {
                ?>
                <tr>
                    <td><?php echo $staff['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($staff['name']); ?></td>
                    <td><?php echo $staff['staff_id']; ?></td>
                    <td><?php echo htmlspecialchars($staff['email']); ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    <?php
        } else {
    ?>
        <div class="no-result">
            <i class="fa fa-info-circle fa-2x" style="color:#ccc;"></i><br><br>
            <?php
                if ($is_admin) {
                    echo "No records found matching: <strong>".htmlspecialchars($keyword)."</strong>";
                } else {
                    echo "You can only search and view your own information.";
                }
            ?>
        </div>
    <?php
        }
    } else {
    ?>
        <div class="no-result">
            Enter a keyword above to start searching.
        </div>
    <?php
    }
    ?>
</div>

</body>
</html>