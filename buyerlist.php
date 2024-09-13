<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'VANILLA_INFONET';

$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch traders from the Details table
$query = "SELECT profile_photo, name, district, email, phone_number, price FROM Details WHERE role = 'Trader'";
$result = mysqli_query($conn, $query);

$default_profile_photo = 'uploads/onli.png'; // Path to the default user icon
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vanilla Buyers</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #a5e4ac;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .buyer-list {
            width: 100%;
            max-width: 80%;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .buyer-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eaeaea;
            transition: background-color 0.3s ease;
            position: relative;
        }
        .buyer-item:hover {
            background-color: #f0f0f0;
        }
        .buyer-item:last-child {
            border-bottom: none;
        }
        .buyer-item img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            margin-right: 20px;
            object-fit: cover;
        }
        .buyer-details {
            flex-grow: 1;
        }
        .buyer-details h4 {
            margin: 5px 0;
            font-size: 1.2em;
            color: #2980b9;
        }
        .buyer-details p {
            margin: 5px 0;
            color: #666;
        }
        .buyer-details p strong {
            color: #333;
        }
        .chat-button {
            position: absolute;
            bottom: 15px;
            right: 15px;
            padding: 8px 16px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
        }
        .chat-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <h2>Connect to your favourite buyer</h2>

    <div class="buyer-list">
        <?php while ($buyer = mysqli_fetch_assoc($result)): ?>
            <?php
            // Determine the path to the profile photo
            $profilePhotoPath = 'uploads/' . (!empty($buyer['profile_photo']) ? $buyer['profile_photo'] : $default_profile_photo);
            ?>
            <div class="buyer-item">
                <img src="<?php echo htmlspecialchars($profilePhotoPath); ?>" alt="Profile Photo">
                <div class="buyer-details">
                    <h4><?php echo htmlspecialchars($buyer['name']); ?></h4>
                    <p><strong>District:</strong> <?php echo htmlspecialchars($buyer['district']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($buyer['email']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($buyer['phone_number']); ?></p>
                    <p><strong>Price per kg:</strong> <?php echo htmlspecialchars($buyer['price']); ?></p>
                </div>
                <button class="chat-button">Chat</button>
            </div>
        <?php endwhile; ?>
    </div>

    <?php
    // Close the database connection
    mysqli_close($conn);
    ?>

</body>
</html>
