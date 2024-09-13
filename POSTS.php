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

    header('Location: POSTS.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Post</title>
</head>
<body>
    <h1>Add New Post</h1>
    <form action="POSTS.php" method="post" enctype="multipart/form-data">
        <label for="image">Upload Image/Video:</label>
        <input type="file" id="image" name="image" required>
        <br>
        <label for="comment">Description:</label>
        <textarea id="comment" name="comment" required></textarea>
        <br>
        <button type="submit">Add Post</button>
    </form>
</body>
</html>
