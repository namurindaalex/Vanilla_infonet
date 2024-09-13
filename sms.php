
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vanilla Infonet web-portal Broadcast sms Page</title>
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
            <p class="email"> <p> Welcome to Vanilla Infonet web-portal, your gateway to successful vanilla Production!<br>
                        <br>This is the Admin Page
                    </p>
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
                <a href="FAQS.html"><img src="IMAGES/resources.png">Update FAQs</a>
                <a href="updatepost.php"><img src="IMAGES/resources.png">Update Story</a>
                <a href="sms.php"><img src="IMAGES/sms.png">Broadcast SMS</a>
                <a href="#"><img src="IMAGES/resources.png">Call</a>
                <a href="#"><img src="IMAGES/price.png">Add Farmer</a>
                <a href="userlist.php"><img src="IMAGES/language.jpg">View List</a>
                <a href="#"><img src="IMAGES/FAQ.png">FAQ</a>
            </div>
        </div>

        <div class="main-content">
            <div class="slid">
                <p class="slider_heading">Send Broadcast Message</p>
                <p class="slider_par2">
                <form action="ssms.php" method="post">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" required></textarea>
                
                    <br><br>
                    <div class="navbar-signoutt">
                        <a><button type="submit" id="myProfileBtn" class="startbuilding">Send Message</button></a>
                    </div>
                </form></p>
                    
            </div>        
        </div>
    </div>
</body>
</html>
