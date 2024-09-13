<?php
// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=VANILLA_INFONET', 'root', '');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = isset($_POST['comment']) ? $_POST['comment'] : null;
    $image = null;

    // Handle file upload if an image/video is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check file size
        if ($_FILES["image"]["size"] > 256 * 1024 * 1024) {
            echo "Sorry, your file is too large.";
            exit;
        }

        // Allow certain file formats
        $allowedTypes = array("jpg", "jpeg", "png", "gif", "mp4", "avi", "mov");
        if (!in_array($fileType, $allowedTypes)) {
            echo "Sorry, only JPG, JPEG, PNG, GIF, MP4, AVI, and MOV files are allowed.";
            exit;
        }

        // Attempt to upload the file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $targetFile;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    // Ensure at least one type of media is provided
    if (empty($image)) {
        echo "Please provide an image or video.";
        exit;
    }

    // Insert into the database
    $insertQuery = "INSERT INTO Posts (image, comment, liking, share) VALUES (?, ?, 0, 0)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute([$image, $comment]);

    header('Location: updatepost.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vanilla Infonet web-portal posts Page</title>
    <link rel="stylesheet" href="new.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-logo">
                <img src="IMAGES/logo1.png" alt="Logo" width="200" height="120">
            </div>
            <div class="navbar-separator"></div>
            <div class="navbar-profile">                
                <p> Welcome to Vanilla Infonet web-portal, your gateway to successful vanilla Production!<br>
                    <br>Add post !
                </p>
            </div>
            <div class="navbar-signout">
                <a href="index.php"><button class="startbutton">Sign Out</button></a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="left-sidebar">
            <div class="imp-links">
            <a href="index.html" id="homeLink"><img src="IMAGES/products.jpg">Home</a>
                <a href="EVENTS.html"><img src="IMAGES/pricing.jpg">Update Events</a>
                <a href="FAQS.html"><img src="IMAGES/resources.png">Update FAQs</a>
                <a href="#"><img src="IMAGES/resources.png">Update Story</a>
                <a href="#"><img src="IMAGES/sms.png">Broadcast SMS</a>
                <a href="#"><img src="IMAGES/resources.png">Call</a>
                <a href="#"><img src="IMAGES/price.png">Add Farmer</a>
                <a href="userlist.php"><img src="IMAGES/language.jpg">View List</a>
                <a href="#"><img src="IMAGES/FAQ.png">FAQ</a>
            </div>
        </div>

        <div class="main-content">
            <div class="slid">
                <p class="slider_heading">Add New Post</p>
                <p class="slider_par2">
                <form action="updatepost.php" method="post" enctype="multipart/form-data">
                    <label for="image">Upload Image/Video:</label>
                    <input type="file" id="image" name="image" required>
                    <br><br>
                    <label for="comment">Description:</label>
                    <textarea id="comment" name="comment" required></textarea>
                
                    <br><br>
                    <div class="navbar-signoutt">
                        <a><button type="submit" id="myProfileBtn" class="startbuilding">Add POST</button></a>
                    </div>
                </form></p>
                    
            </div>        
        </div>
    </div>
</body>
</html>
