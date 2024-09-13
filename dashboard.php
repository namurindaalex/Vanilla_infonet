<?php
// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=VANILLA_INFONET', 'root', '');

// Fetch all posts
$stmt = $pdo->query("SELECT * FROM Posts ORDER BY id DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vanilla Infonet web-portal</title>
    <link rel="stylesheet" href="start.css">

    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        function updateCount(postId, action) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_post.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            
            xhr.onload = function() {
                if (this.status === 200) {
                    if (action === 'like') {
                        document.getElementById('like-count-' + postId).textContent = this.responseText;
                    } else if (action === 'share') {
                        document.getElementById('share-count-' + postId).textContent = this.responseText;
                    }
                } else {
                    console.error('Error:', this.status, this.statusText);
                }
            };

            xhr.onerror = function() {
                console.error('Request failed');
            };

            xhr.send('post_id=' + postId + '&action=' + action);
        }
    </script>

    <style>
        .cal {
            width: 100%;
            height: 300px;
            margin: 10px auto; /* Center the calendar */
            border: 1px solid #324181; /* Frame border */
            padding: 5px; /* Space between border and calendar */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(90, 202, 159, 0.473); /* Optional: shadow effect */
        }
    
        .fc-daygrid-day {
            width: 10px; /* Adjust the width of each day cell */
            height: 10px; /* Adjust the height of each day cell */
        }
        .fc-header-toolbar.fc-toolbar{
            font-size: 8px;
            font-weight: bold;
            color: blue;
        }
    
        #calendar {
            width: 100%;
            height: 100%;
            font-size: 10px;
        }
      </style>

<style>
        .more-info {
            display: none;
            margin-top: 10px;
        }
    </style>

<?php
session_start();
$phoneNumber = $_SESSION['phone']; // Get phone number from session

class UserProfile {
    private $db_host = 'localhost';
    private $db_user = 'root';
    private $db_password = '';
    private $db_name = 'VANILLA_INFONET';
    private $conn;

    public function __construct() {
        // Establish a database connection
        $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getUserProfile($phoneNumber) {
        // SQL query to get user name and profile photo
        $stmt = $this->conn->prepare("SELECT NAME, profile_photo, ROLE FROM Details WHERE PHONE_NUMBER = ?");
        $stmt->bind_param("s", $phoneNumber);
        $stmt->execute();
        $stmt->bind_result($name, $profilePhoto, $role);
        $stmt->fetch();
        $stmt->close();
        
        return [
            'name' => $name ? $name : 'User', // Default name if none
            'profile_photo' => $profilePhoto ? $profilePhoto : 'uploads/onli.png', // Default photo if none
            'role' => $role ? $role : 'Kasese' // Default name if none
        ];
    }

    public function __destruct() {
        // Close the database connection
        $this->conn->close();
    }
}

// Create an instance of UserProfile and get the user profile data
$profile = new UserProfile();
$userProfile = $profile->getUserProfile($phoneNumber);

// Pass the user profile data to JavaScript
$userName = htmlspecialchars($userProfile['name']);
$profilePhoto = htmlspecialchars($userProfile['profile_photo']);
$Role = htmlspecialchars($userProfile['role']);
?>

<?php
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
$query = "SELECT NAME, ROLE, PHONE_NUMBER, DISTRICT, Email, Price FROM details WHERE PHONE_NUMBER = ?";
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

<style>
    .profile-section {
        display: none; /* Hidden by default */
        padding: 10px 15px;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 0 20px;
    }
    .profile-button {
        margin: 10px 10px;
        margin-left: 25px;
        background-color: #f4c836;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 20px;
        border-radius: 10px;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .profile-section img {
        max-width: 150px;
        border-radius: 50%;
    }
</style>


</head>
<body>
        <nav class="navbar">
            <div class="navbar-container">
                <div class="navbar-logo">
                    <img src="IMAGES/logo1.png" alt="Logo">
                </div>
                <div class="navbar-separator"></div>
                <div class="navbar-profile">
                    <img src="<?php echo htmlspecialchars($profilePhoto); ?>" alt="Profile Photo" class="profile-photo">
                    <div class="profile-info">
                        <p id="greeting" class="greeting"></p>
                        <p class="email">Welcome to Vanilla Infonet web-portal, your gateway to successful vanilla Production!</p>
                    </div>
                </div>
                <div class="navbar-separator1"></div>
                <div class="navbar-profile-btn">
                <button id="myProfileBtn" class="profile-button">My Profile</button>
                </div>
                <div class="navbar-signout">
                    <a href="index.php"><button class="signout-button">SignOut</button></a>
                </div>
            </div>
        </nav>

      <!-- Hidden profile section -->
<div id="profileSection" class="profile-section">
    <h2>My Profile</h2>
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
    
    <?php if ($user['ROLE'] === 'Trader'): ?>
        <p><strong>Price:</strong> 
            <?php if (!empty($user['Price'])): ?>
                <?php echo htmlspecialchars($user['Price']); ?>
            <?php else: ?>
                <form method="POST">
                    <input type="text" name="price" placeholder="Add Price" required>
                    <button type="submit">Add</button>
                </form>
            <?php endif; ?>
        </p>
    <?php endif; ?>

    <div class="change-profile-pic">
        <form id="uploadForm" method="POST" enctype="multipart/form-data">
            <button type="button" id="changePicBtn">Upload Profile Picture</button>
            <input type="file" name="profile_photo" id="profilePhoto" accept="image/*" style="display: none;">
            <p id="message"></p>
        </form>
    </div>
</div>


<script>
    document.getElementById('changePicBtn').addEventListener('click', function() {
        document.getElementById('profilePhoto').click();
    });

    document.getElementById('profilePhoto').addEventListener('change', function() {
        var form = document.getElementById('uploadForm');
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'upload_profile_picture.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                alert(response); // Show the server's response in an alert box
                setTimeout(function() {
                    location.reload(); // Refresh the page after 2 seconds
                }, 500);
            } else {
                alert('Failed to upload profile picture.');
            }
        };
        xhr.send(formData);
    });
</script>



    <div class="container">
        <div class="left-sidebar">
            <div class="imp-links">
                <a href="#"><img src="IMAGES/news1.png">News & Updates</a>
                <a href="pricegraph.html"><img src="IMAGES/buy_sell.png">Buy/Sell</a>
                <a href="resources.html"><img src="IMAGES/resources.png">Resources</a>
                <a href="pricegraph.html"><img src="IMAGES/price.png">Market Prices</a>
                <a href="readfaq.php"><img src="IMAGES/FAQ.png">FAQs</a>
            </div>       
            <p>Mark your calender</p>
            <div class="cal">
            <div id='calendar'></div>
            </div>
        </div>

        <div class="main-content">
            <div class="slider">
                <p class="slider_heading">VANILLA CROP <br /> PRODUCTION</p>
                <p class="slider_par1">in</p>
                <p class="slider_par2">Western Uganda</p>
            </div>

            <div class="write-post-container">
                <div class="user-profile">
                    <img src="<?php echo htmlspecialchars($profilePhoto); ?>">
                    <div>
                        <p><?php echo htmlspecialchars($userName); ?></p>
                        <small>public <i class="fas fa-caret-down"></i></small>
                    </div>
                </div>
                
                <div class="post-input-container">
                    <textarea rows="3" placeholder="What's on your mind?"></textarea>
                    <div class="add-post-link">
                        <a href="#"><img src="IMAGES/icon_live_video.gif">Live Video</a>
                        <a href="#"><img src="IMAGES/icon_photo.png">Photo/Video</a>
                        <a href="#"><img src="IMAGES/icon_activity.png">Feeling/Activity</a>
                    </div>
                </div>
                
            </div>

            <div class="post-conatiner">
                <div class="post-row">
                    <div class="user-profile">
                        <img src="<?php echo htmlspecialchars($profilePhoto); ?>">
                        <div>
                            <p><?php echo htmlspecialchars($userName); ?></p>
                            <span>June 24 2021, 13:40pm</span>
                        </div>
                    </div>
                    <a href="#"><i class="fas fa-ellipsis-v"></i></a>
                </div>
                <p: class="post-text"><p class="post-text">Stay dedicated to cultivating vanilla and 
                explore market trends to boost your success. Share your experiences with fellow farmers and 
                seek advice from experts to continually improve. <br /><br />Don't forget to check out and utilize 
                the relevant posts shared by the admin. They offer valuable insights and tips to 
                help you thrive in vanilla farming!</p><a href="#">#vanilla_infonet</a>  <a href="#">#YouTube</a></p><br .>
                <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
            <div class="post-container">
                <?php if (strpos($post['IMAGE'], '.mp4') !== false): ?>
                    <video width="100%" height="auto" controls>
                        <source src="<?php echo htmlspecialchars($post['IMAGE']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    <img src="<?php echo htmlspecialchars($post['IMAGE']); ?>" alt="Post Image" style="width:100%; margin-top: 20px;">
                <?php endif; ?>
                <p><?php echo htmlspecialchars($post['Comment']); ?></p>
                <div class="post-row">
                    <div class="activity-icons">
                        <div>
                            <button onclick="updateCount(<?php echo $post['id']; ?>, 'like')" style=" margin: 5px 5px;">
                                <img src="IMAGES/icon_like.png" alt="Like Icon" > 
                                <span id="like-count-<?php echo $post['id']; ?>"><?php echo $post['Liking']; ?></span>
                            </button>
                        </div>
                        <div>
                            <button onclick="updateCount(<?php echo $post['id']; ?>, 'share')">
                                <img src="IMAGES/icon_forward.png" alt="Share Icon"> 
                                <span id="share-count-<?php echo $post['id']; ?>"><?php echo $post['Share']; ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>

            </div> 
            

        </div>

        <div class="right-sidebar">

            <div class="sidebar-title">
                <h4>Upcoming Events</h4>
            </div>
            <div id="events">
                <?php
                // Database connection
                $mysqli = new mysqli("localhost", "root", "", "VANILLA_INFONET");

                // Check connection
                if ($mysqli->connect_error) {
                    die("Connection failed: " . $mysqli->connect_error);
                }

                // Fetch events
                $result = $mysqli->query("SELECT * FROM Events ORDER BY day, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')");

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='event'>";
                        echo "<div class='left-event'>";
                        echo "<h3>" . $row["day"] . "</h3>";
                        echo "<span>" . $row["month"] . "</span>";
                        echo "</div>";
                        echo "<div class='right-event'>";
                        echo "<h4>" . $row["Description"] . "</h4>";
                        echo "<p><i class='fas fa-map-marker-alt'></i> " . $row["Venue"] . "</p>";
                        echo "<a href='#' class='more-info-button' onclick='toggleMoreInfo(event)'>More Info</a>";
                        echo "<div class='more-info'>" . $row["More_info"] . "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No events found.</p>";
                }

                // Close the connection
                $mysqli->close();
                ?>
            </div>
            <br >
            <br >
            
            <div class="sidebar-title">
                <h4>Discussion Forum</h4>
                <a href="#" id="hide-chat-toggle">Hide Chat</a>
            </div>
            <?php
                class OnlineList {
                    private $db_host = 'localhost';
                    private $db_user = 'root';
                    private $db_password = '';
                    private $db_name = 'VANILLA_INFONET';
                    private $conn;

                    public function __construct() {
                        // Establish a database connection
                        $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);

                        // Check connection
                        if ($this->conn->connect_error) {
                            die("Connection failed: " . $this->conn->connect_error);
                        }
                    }

                    public function getPeopleList() {
                        // SQL query to get the list of people excluding admins
                        $sql = "SELECT * FROM Details WHERE ROLE != 'admin'"; // Exclude admin role
                        $result = $this->conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                $profilePhoto = $row["profile_photo"] ? $row["profile_photo"] : 'uploads/onli.png'; // Default photo if none
                                echo '<div class="online-list">';
                                echo '<div class="online"><img src="' . htmlspecialchars($profilePhoto) . '" alt="Profile Picture"></div>'; // Use actual profile photo
                                echo '<p>' . htmlspecialchars($row["NAME"]) . '</p>'; // Adjust field names as needed
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No users online</p>';
                        }
                    }

                    public function __destruct() {
                        // Close the database connection
                        $this->conn->close();
                    }
                }

                // Create an instance of OnlineList and display the list of people
                $list = new OnlineList();
                $list->getPeopleList();
            ?>

   
        </div>
    </div>

    <div class="footer">
        <p>Copyright 2024 - vanilla_infonet @ group2</p>
    </div>
<script src="profile_buttom.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: [
          {
            title: 'Event 1',
            start: '2023-07-01'
          },
          {
            title: 'Event 2',
            start: '2023-07-02',
            end: '2023-07-03'
          }
          // Add more events here
        ],
        dayRender: function(arg) {
          var today = new Date().toISOString().slice(0, 10);
          if (arg.dateStr === today) {
            arg.el.classList.add('today');
          }
        },
        dateClick: function(info) {
          var clickedDate = info.dateStr;
          var today = new Date().toISOString().slice(0, 10);
          
          if (clickedDate !== today) {
            calendar.setOption('title', clickedDate);
          }
        }
      });

      calendar.render();
    });
  </script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        var hideChatToggle = document.getElementById('hide-chat-toggle');
        var onlineLists = document.querySelectorAll('.online-list');

        hideChatToggle.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior

            onlineLists.forEach(function(list) {
                if (list.style.display === 'none') {
                    list.style.display = 'flex'; // Show the online list using flex
                    hideChatToggle.textContent = 'Hide Chat'; // Change text back to 'Hide Chat'
                } else {
                    list.style.display = 'none'; // Hide the online list
                    hideChatToggle.textContent = 'Show Chat'; // Change text to 'Show Chat'
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Get the user name from PHP
        const userName = "<?php echo $userName; ?>";
        const now = new Date();
        const hours = now.getHours();
        let greeting;

        if (hours < 12) {
            greeting = `Good morning, ${userName}!`;
        } else if (hours < 18) {
            greeting = `Good afternoon, ${userName}!`;
        } else {
            greeting = `Good evening, ${userName}!`;
        }

        // Set the greeting message
        document.getElementById('greeting').textContent = greeting;
    });
    </script>

<script>
        function toggleMoreInfo(event) {
            var moreInfoDiv = event.target.nextElementSibling;
            if (moreInfoDiv.style.display === "block") {
                moreInfoDiv.style.display = "none";
                event.target.textContent = "More Info";
            } else {
                moreInfoDiv.style.display = "block";
                event.target.textContent = "Less Info";
            }
        }
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var profileButton = document.getElementById('myProfileBtn');
        var profileSection = document.getElementById('profileSection');
        var changePicBtn = document.getElementById('changePicBtn');

        profileButton.addEventListener('click', function() {
            if (profileSection.style.display === 'none' || profileSection.style.display === '') {
                profileSection.style.display = 'block'; // Show the profile section
                profileButton.textContent = 'Hide Profile'; // Change button text
            } else {
                profileSection.style.display = 'none'; // Hide the profile section
                profileButton.textContent = 'My Profile'; // Change button text
            }
        });
    });
</script>


</body>
</html>