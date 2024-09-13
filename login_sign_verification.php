<?php
// Start a session
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

// Function to validate user credentials and fetch user data
function authenticateUser($phone, $password, $conn) {
    $sql = "SELECT * FROM Details WHERE PHONE_NUMBER='$phone' AND PASSWORD='$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result); // Return user data including name and role
    } else {
        return false; // Authentication failed
    }
}

// Login logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    
    $user = authenticateUser($phone, $password, $conn);
    
    if ($user) {
        $_SESSION['phone'] = $phone;
        $_SESSION['name'] = $user['NAME']; // Store user's name in session
        $_SESSION['role'] = $user['ROLE']; // Store user's role in session
        
        // Redirect user based on role
        if ($user['ROLE'] == 'admin') {
            header("Location: adminpage.html");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        echo "Invalid user credentials";
    }
}

// Registration logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Handle file upload
    $uploadDir = 'uploads/';
    $defaultPhotoPath = 'uploads/onli.png'; // Path to the default image

    // Check if the uploads directory exists
    if (!is_dir($uploadDir)) {
        // Create the uploads directory if it doesn't exist
        if (!mkdir($uploadDir, 0755, true)) {
            die("Failed to create directory: $uploadDir");
        }
    }

    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $fileName = $_FILES['profile_photo']['name'];
        $fileTmpName = $_FILES['profile_photo']['tmp_name'];
        $fileDestination = $uploadDir . $fileName;

        // Move uploaded file to the server
        if (move_uploaded_file($fileTmpName, $fileDestination)) {
            $profilePhotoPath = $fileDestination;
        } else {
            echo "Failed to move uploaded file.";
            $profilePhotoPath = $defaultPhotoPath; // Use default image on failure
        }
    } else {
        $profilePhotoPath = $defaultPhotoPath; // Use default image if no file was uploaded
    }

    // Check if the phone number is already taken
    $checkSql = "SELECT * FROM Details WHERE PHONE_NUMBER='$phone'";
    $checkResult = mysqli_query($conn, $checkSql);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $registration_error = "Number already registered! Try another number.";
        echo $registration_error;
    } else {
        // Insert the new user into the database
        $insertSql = "INSERT INTO Details (NAME, ROLE, PHONE_NUMBER, PASSWORD, profile_photo) VALUES ('$name', '$role', '$phone', '$password', '$profilePhotoPath')";
        if (mysqli_query($conn, $insertSql)) {
            $_SESSION['phone'] = $phone;
            $_SESSION['name'] = $name; // Store new user's name in session
            $_SESSION['role'] = $role; // Store new user's role in session
            
            // Redirect user based on role
            if ($role == 'admin') {
                header("Location: index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $registration_error = "Registration failed";
            echo $registration_error;
        }
    }
}
?>
