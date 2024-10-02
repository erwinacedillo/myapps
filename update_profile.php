<?php
include('config.php');
include("session.php");

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind
    $stmt = $conn->prepare("UPDATE users SET firstName=?, middleName=?, lastName=?, email=?, password=? WHERE id=?");
    $stmt->bind_param("sssssi", $firstName, $middleName, $lastName, $email, $password, $userId);

    // Set parameters from POST data
    $firstName = $_POST['firstName'];
    $middleName = isset($_POST['middleName']) ? $_POST['middleName'] : null;
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userId = $_SESSION['userId']; // Assuming userId is stored in session

    // Execute the statement
    if ($stmt->execute()) {
        $message = "Profile updated successfully";
        // Update session variables
        $_SESSION['firstName'] = $firstName;
        $_SESSION['middleName'] = $middleName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['email'] = $email;
    } else {
        $message = "Error updating profile: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
} else {
    $message = "Form submission method not valid.";
}

// Close connection
$conn->close();

// Output JavaScript for alert and redirection
echo "<script type='text/javascript'>
        alert('$message');
        window.location.href = 'home.php';
      </script>";
?>
