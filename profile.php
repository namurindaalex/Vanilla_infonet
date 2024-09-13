<?php
session_start();

// Database connection configuration
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'VANILLA_INFONET'; 

// Create a database connection using MySQLi
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION['phone'])) {
    header('Location: login.php');
    exit;
}

// Fetch the user's details based on the phone_number
$phone_number = $_SESSION['phone'];
$query = "SELECT NAME, ROLE, PHONE_NUMBER, DISTRICT, Email FROM details WHERE PHONE_NUMBER = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $phone_number);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// If the form is submitted to update DISTRICT, Email, or Price
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['district']) && !empty($_POST['district'])) {
        $district = $_POST['district'];
        $updateQuery = "UPDATE details SET DISTRICT = ? WHERE PHONE_NUMBER = ?";
        $updateStmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, 'ss', $district, $phone_number);
        mysqli_stmt_execute($updateStmt);
        $user['DISTRICT'] = $district;
    }

    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = $_POST['email'];
        $updateQuery = "UPDATE details SET Email = ? WHERE PHONE_NUMBER = ?";
        $updateStmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, 'ss', $email, $phone_number);
        mysqli_stmt_execute($updateStmt);
        $user['Email'] = $email;
    }

    if (isset($_POST['price']) && !empty($_POST['price']) && $user['ROLE'] === 'Trader') {
        $price = $_POST['price'];
        $updateQuery = "UPDATE details SET Price = ? WHERE PHONE_NUMBER = ?";
        $updateStmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, 'ss', $price, $phone_number);
        mysqli_stmt_execute($updateStmt);
        $user['Price'] = $price;
    }
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <!-- Add your CSS styling here -->
</head>
<body>
    <h1>My Profile</h1>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['NAME']); ?></p>
    <p><strong>Role:</strong> <?php echo htmlspecialchars($user['ROLE']); ?></p>
    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['PHONE_NUMBER']); ?></p>

    <p><strong>District:</strong> 
        <?php if (!empty($user['DISTRICT'])): ?>
            <?php echo htmlspecialchars($user['DISTRICT']); ?>
        <?php else: ?>
            <form method="POST">
                <input type="text" name="district" placeholder="Add District" required>
                <button type="submit">Add</button>
            </form>
        <?php endif; ?>
    </p>

    <p><strong>Email:</strong> 
        <?php if (!empty($user['Email'])): ?>
            <?php echo htmlspecialchars($user['Email']); ?>
        <?php else: ?>
            <form method="POST">
                <input type="email" name="email" placeholder="Add Email" required>
                <button type="submit">Add</button>
            </form>
        <?php endif; ?>
    </p>
    <p><strong>Price:</strong> 
    <?php if ($user['ROLE'] === 'Trader'): ?>
        <?php if (!empty($user['Price'])): ?>
            <?php echo htmlspecialchars($user['Price']); ?>
        <?php else: ?>
            <form method="POST">
                <input type="text" name="price" placeholder="Add Price" required>
                <button type="submit">Add</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <em>Not Applicable</em>
    <?php endif; ?>
</p>

    <!-- Add any additional profile details or navigation links here -->
</body>
</html>
