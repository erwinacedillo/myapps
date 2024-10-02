<?php
include("session.php");
include("config.php"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $businessName = $_POST['businessName'];
    $businessEmail = $_POST['businessEmail'];
    $businessAddress = $_POST['businessAddress'];
    $businessPhone = $_POST['businessPhone'];
    $businessDescription = $_POST['businessDescription'];
    $userId = $_SESSION['userId']; 

    // Insert seller application into the database using MySQLi
    $stmt = $conn->prepare("INSERT INTO seller_applications (user_id, business_name, business_email, business_address, business_phone, business_description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $userId, $businessName, $businessEmail, $businessAddress, $businessPhone, $businessDescription);

    if ($stmt->execute()) {
        header("Location: home.php?application=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
