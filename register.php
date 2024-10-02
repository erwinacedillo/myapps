<?php
include('config.php');

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (firstName, middleName, lastName, email, password, usertype) VALUES (?, ?, ?, ?, ?, 'user')");
    $stmt->bind_param("sssss", $firstName, $middleName, $lastName, $email, $password);

    // Set parameters from POST data
    $firstName = $_POST['firstName'];
    $middleName = isset($_POST['middleName']) ? $_POST['middleName'] : null;
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Execute the statement
    if ($stmt->execute()) {
        $message = "New record created successfully";
    } else {
        $message = "Error: " . $stmt->error;
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
        window.location.href = 'index.php';
      </script>";
?>
