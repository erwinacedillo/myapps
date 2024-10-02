<?php
session_start(); // Start session to access session variables

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

// Fetch user details from session variables safely
$firstName = $_SESSION['firstName'] ?? '';
$middleName = $_SESSION['middleName'] ?? '';
$lastName = $_SESSION['lastName'] ?? '';
$email = $_SESSION['email'] ?? '';
$photo = $_SESSION['photo'] ?? '';

?>