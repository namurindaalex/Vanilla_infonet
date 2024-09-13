<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vanilla Infonet web-portal FAQs Page</title>
    <link rel="stylesheet" href="new.css">

    <style>
       .container {
            display: flex;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
            color: #007BFF;
        }
        table {
            width: 90%;
            border-collapse: collapse;
            align-content: center;
            margin-bottom: 20px;
            margin-left: 50px;
        }
        table, th, td {
            border: 2px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: #fff;
        }
        td img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        
       .role-title {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            font-size: 20px;
            margin-top: 30px;
            color: #007BFF;
            border-radius: 5px;
        }
    </style>
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
                    <br>List of Registered Users!
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
                <a href="adminpage.html" id="homeLink"><img src="IMAGES/products.jpg">Home</a>
                <a href="EVENTS.html"><img src="IMAGES/pricing.jpg">Update Events</a>
                <a href="#"><img src="IMAGES/resources.png">Update FAQs</a>
                <a href="updatepost.php"><img src="IMAGES/resources.png">Update Story</a>
                <a href="https://africastalking.ug/marketplace"><img src="IMAGES/resources.png">Broadcast SMS</a>
                <a href="https://africastalking.ug/marketplace"><img src="IMAGES/resources.png">Call</a>
                <a href="https://africastalking.ug/blog"><img src="IMAGES/price.png">Add Farmer</a>
                <a href="#"><img src="IMAGES/language.jpg">View List</a>
                <a href="https://help.africastalking.com/en/"><img src="IMAGES/FAQ.png">FAQ</a>
            </div>
        </div>

        <div class="main-content">
            <h1>LIST OF REGISTERED USERS</h1><br><br>

            <div class="role-title">Farmers</div>
            <table>
            <thead>
                <tr>
                    <th>Profile Photo</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                </tr>
            </thead>

            <tbody>
                <!-- Farmers table content -->
                <?php
                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'VANILLA_INFONET');

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch farmers
                $sql = "SELECT NAME, PHONE_NUMBER, profile_photo FROM Details WHERE ROLE = 'farmer'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $profilePhoto = $row["profile_photo"] ? $row["profile_photo"] : 'uploads/onli.png';
                        echo '<tr>';
                        echo '<td><img src="' . htmlspecialchars($profilePhoto) . '" alt="Profile Picture"></td>';
                        echo '<td>' . htmlspecialchars($row["NAME"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["PHONE_NUMBER"]) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">No farmers found</td></tr>';
                }
                ?>
            
            </tbody>
            </table>

            <div class="role-title">Traders</div>
            <table>
            <thead>
                <tr>
                    <th>Profile Photo</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                </tr>
            </thead>

            <tbody>
                <!-- Traders table content -->
                <?php
                // Fetch traders
                $sql = "SELECT NAME, PHONE_NUMBER, profile_photo FROM Details WHERE ROLE = 'trader'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $profilePhoto = $row["profile_photo"] ? $row["profile_photo"] : 'uploads/onli.png';
                        echo '<tr>';
                        echo '<td><img src="' . htmlspecialchars($profilePhoto) . '" alt="Profile Picture"></td>';
                        echo '<td>' . htmlspecialchars($row["NAME"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["PHONE_NUMBER"]) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">No traders found</td></tr>';
                }

                // Close the connection
                $conn->close();
                ?>
            
            </tbody>
            </table>
        </div>
    </div>
</body>
</html>
