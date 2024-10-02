<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Include database configuration
include('config.php');

// Handle approval and disapproval actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    if (isset($_POST['approve'])) {
        $userStatus = 'approved';
        $userType = 'seller';
        $applicationStatus = 'approved';
    } elseif (isset($_POST['disapprove'])) {
        $userStatus = 'disapproved';
        $userType = null; // Keep usertype unchanged on disapproval
        $applicationStatus = 'disapproved';
    }

    if (isset($userStatus) && isset($applicationStatus)) {
        if ($userType) {
            $stmt = $conn->prepare("UPDATE users SET usertype = ? WHERE id = ?");
            $stmt->bind_param("si", $userType, $userId);
            $stmt->execute();
        }

        $stmt = $conn->prepare("UPDATE seller_applications SET status = ? WHERE user_id = ?");
        $stmt->bind_param("si", $applicationStatus, $userId);
        if ($stmt->execute()) {
            $message = "User and application status updated successfully.";
        } else {
            $message = "Error updating application status: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch only pending applications
$sql = "SELECT 
            users.id, 
            users.firstName, 
            users.lastName, 
            users.email, 
            users.usertype, 
            seller_applications.business_name,
            seller_applications.business_email,
            seller_applications.business_address,
            seller_applications.business_phone,
            seller_applications.business_description,
            seller_applications.application_date,
            seller_applications.status AS application_status
        FROM 
            users
        LEFT JOIN 
            seller_applications 
        ON 
            users.id = seller_applications.user_id
        WHERE
            seller_applications.status = 'pending'
        GROUP BY
            users.id"; // Ensuring distinct users

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
        }
        #sidebar {
            width: 250px;
            background: #343a40;
            color: #fff;
            height: 100vh;
            padding: 15px;
            position: fixed;
        }
        #sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 0;
        }
        #sidebar a:hover {
            background: #495057;
        }
        #content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <h2>Admin Dashboard</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="logout.php">Logout</a>
    </div>
    <div id="content">
        <h1>Manage Users</h1>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Business Name</th>
                    <th>Business Email</th>
                    <th>Business Address</th>
                    <th>Business Phone</th>
                    <th>Business Description</th>
                    <th>Application Date</th>
                    <th>Application Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['firstName']); ?></td>
                        <td><?php echo htmlspecialchars($row['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($row['business_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['business_email']); ?></td>
                        <td><?php echo htmlspecialchars($row['business_address']); ?></td>
                        <td><?php echo htmlspecialchars($row['business_phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['business_description']); ?></td>
                        <td><?php echo htmlspecialchars($row['application_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['application_status']); ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="approve" class="btn btn-success btn-sm">Approve</button>
                                <button type="submit" name="disapprove" class="btn btn-danger btn-sm">Disapprove</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$conn->close();
?>
