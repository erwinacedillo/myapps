<?php
include('config.php');

// Start the session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement to check credentials
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ? AND usertype = 'admin'");
    if ($stmt) {
        $stmt->bind_param("s", $email);

        // Execute statement
        $stmt->execute();

        // Bind result variables
        $stmt->bind_result($id, $email, $stored_password);

        // Check if a result is returned
        if ($stmt->fetch()) {
            // Verify password
            if ($password === $stored_password) {
                // Set session variables
                $_SESSION['admin_id'] = $id;
                $_SESSION['email'] = $email;

                // Redirect to admin dashboard
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "No admin user found with that email.";
        }

        // Close statement
        $stmt->close();
    } else {
        $message = "Error preparing SQL statement: " . $conn->error;
    }

    // Close database connection
    $conn->close();
}

// Output JavaScript alert and redirect
if (!empty($message)) {
    echo "<script type='text/javascript'>
            alert('$message');
            window.location.href = 'index.php';
          </script>";
}
?>
