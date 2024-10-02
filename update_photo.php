<?php
session_start();
include('config.php');
include("session.php");


// Check if form was submitted and file was uploaded
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['photo'])) {
    $userId = $_SESSION['userId'];
    $photo = $_FILES['photo'];

    // File upload handling
    $targetDir = "uploads/"; // Directory where uploaded files will be stored
    $targetFile = $targetDir . basename($photo['name']); // Path of the uploaded file on the server
    $uploadOk = 1; // Flag to check if file upload is successful
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); // Get the file extension

    // Check if image file is a actual image or fake image
    $check = getimagesize($photo['tmp_name']);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $message = "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($photo['size'] > 5 * 1024 * 1024) {
        $message = "Sorry, your file is too large. Maximum size allowed is 5 MB.";
        $uploadOk = 0;
    }

    // Allow only certain file formats (you can adjust this as needed)
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    if (!in_array($imageFileType, $allowedExtensions)) {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($photo['tmp_name'], $targetFile)) {
            // Update the photo path in the database
            $updateQuery = "UPDATE users SET photo = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $targetFile, $userId);

            if ($stmt->execute()) {
                $message = "Photo uploaded and updated successfully.";
                // Update session variable with new photo path
                $_SESSION['photo'] = $targetFile;
            } else {
                $message = "Error updating photo: " . $conn->error;
            }

            $stmt->close();
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }

    // Close database connection
    $conn->close();

    // Output JavaScript for alert and redirection
    echo "<script type='text/javascript'>
            alert('$message');
            window.location.href = 'home.php';
          </script>";
} else {
    // Redirect to update profile page if no file was uploaded or incorrect request method
    header("Location: home.php");
    exit();
}
?>
