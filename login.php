<?php
session_start(); // Start session for managing user login state

include('config.php'); // Include database configuration

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from form
    $loginEmail = $_POST['loginEmail'];
    $loginPassword = $_POST['loginPassword'];

    // Prepare SQL statement to retrieve user data
    $stmt = $conn->prepare("SELECT id, firstName, middleName, lastName, email, password, usertype, photo FROM users WHERE email = ?");
    $stmt->bind_param("s", $loginEmail);

    // Execute the statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($userId, $firstName, $middleName, $lastName, $email, $storedPassword, $userType, $photo);

    // Fetch and verify user
    if ($stmt->fetch()) {
        // Verify password (plain text comparison)
        if ($loginPassword === $storedPassword) { 
            // Password is correct, set session variables
            $_SESSION['userId'] = $userId;
            $_SESSION['firstName'] = $firstName;
			$_SESSION['middleName'] = $middleName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $storedPassword;
			$_SESSION['userType'] = $userType;
			$_SESSION['photo'] = $photo;

            // Redirect to dashboard or home page
            header("Location: home.php");
            exit();
        } else {
            // Password is incorrect
            $_SESSION['loginError'] = "Invalid password";
            echo "<script type='text/javascript'>
                alert('Invalid password');
                window.location.href = 'index.php';
              </script>";
            exit();
        }
    } else {
        // User not found
        $_SESSION['loginError'] = "User not found";
        echo "<script type='text/javascript'>
                alert('User not found');
                window.location.href = 'index.php';
              </script>";
        exit();
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
