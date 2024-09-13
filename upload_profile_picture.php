<?php
session_start();

// Database connection configuration
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'VANILLA_INFONET';

// Create a database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $userPhoneNumber = $_SESSION['phone']; // Assumes the phone number is stored in the session

        // Validate file type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2 MB
        $fileType = $_FILES['profile_photo']['type'];
        $fileSize = $_FILES['profile_photo']['size'];

        if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
            $fileName = $_FILES['profile_photo']['name'];
            $fileTmpName = $_FILES['profile_photo']['tmp_name'];
            $uploadDir = 'uploads/';
            $filePath = $uploadDir . basename($fileName);

            if (move_uploaded_file($fileTmpName, $filePath)) {
                // Update the profile picture in the database
                $stmt = mysqli_prepare($conn, "UPDATE Details SET profile_photo = ? WHERE PHONE_NUMBER = ?");
                mysqli_stmt_bind_param($stmt, 'ss', $filePath, $userPhoneNumber);
                $success = mysqli_stmt_execute($stmt);

                if ($success) {
                    echo 'Profile picture updated successfully!';
                } else {
                    echo 'Failed to update profile picture in the database.';
                }

                mysqli_stmt_close($stmt);
            } else {
                echo 'Failed to move uploaded file.';
            }
        } else {
            echo 'Invalid file type or size.';
        }
    } else {
        echo 'No file uploaded or upload error.';
    }
} else {
    echo 'Invalid request method.';
}

mysqli_close($conn);
?>
