<?php
include("session.php");



// Determine user type
$userType = $_SESSION['userType'] ?? ''; // Assuming 'userType' is set in session
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <!-- Navbar -->
    <?php 
    if ($userType == 'user') {
        include("includes/nav_user.php");
    } elseif ($userType == 'seller') {
        include("includes/nav_seller.php");
    }
    ?>

    <!-- Hero Section -->
    <?php include("includes/body.php"); ?>

    <!-- Footer -->
    <?php include("includes/footer.php"); ?>
    <?php include("includes/modal.php");?>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
