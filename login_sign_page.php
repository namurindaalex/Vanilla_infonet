<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login|sign_up</title>
    <link rel="stylesheet" href="login_page_style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'create.php'; ?>
    <div class="wrapper">
        <span class="bg-animate"></span>
        <span class="bg-animate2"></span>

        <!--sign up option-->

        <div class="form-box login">
            <h2 class="animation" style="--i:0;">Sign Up</h2>
            <form action="login_sign_verification.php" method="post" class="sig" enctype="multipart/form-data">
                <div class="input-box animation" style="--i:1;">
                    <input type="text" name="name" required>
                    <label for="name">Name:</label>
                    <i class="bx bxs-user"></i>
                </div>

                <div class="input-box animation" style="--i:2;">
                    <input type="text" name="role" required>
                    <label for="role">Role:</label>
                    <i class="bx bxs-user-detail"></i>
                </div>


                <div class="input-box animation" style="--i:3;">
                    <input type="text" name="phone" required>
                    <label for="phone">Phone Number:</label>
                    <i class="bx bxs-phone"></i>
                </div>

                <div class="input-box animation" style="--i:4;">
                    <input type="password" name="password" required>
                    <label for="password">Password:</label>
                    <i class="bx bxs-lock-alt"></i>
                </div>

                <!--
                <div class="input-box animation" style="--i:5;">
                    <input type="file" name="profile_photo" accept="image/*">
                    <i class="bx bxs-image"></i>
                </div>
                -->

                <button type="submit" name="signup" value="Sign up" class="btn animation" style="--i:6;">Sign up</button>
                <div class="logreg-link animation" style="--i:7;">
                <p>Already have an account? <a href="#" class="register-link">Login</a></p>
            </div>
            </form>
        </div>

        <div class="info-text login">
            <h2 class="animation" style="--i:0">Welcome Back!<br /></h2>
            <p class="animation" style="--i:1">Vanilla Crop Production in Western Uganda</p>
        </div>

        <!--login option-->

        <div class="form-box register">
        <h2 class="animation" style="--i:8;">Login</h2>
        <form action="login_sign_verification.php" method="post">
            <div class="colum">
                <!-- Phone Number -->
                <div class="input-box animation" style="--i:9;">
                    <input type="tel" name="phone" required>
                    <label for="phone">Phone Number:</label>
                    <i class="bx bxs-phone"></i>
                </div>

                <!-- Password -->
                <div class="input-box animation" style="--i:10;">
                    <input type="password" name="password" required>
                    <label for="password">Password:</label>
                    <i class="bx bxs-lock-alt"></i>
                </div>
            </div>

            <button type="submit" name="login" value="Login" class="btn animation" style="--i:11;">Login</button>
            <div class="logreg-link animation" style="--i:12;">
                <a href="#" class="Forgot">Forgot Password</a><br /><br >
                <p>Don't have an account? <a href="#" class="login-link">Sign up</a></p>
            </div>
        </form>
    </div>


        <div class="info-text register">
            <h2 class="animation" style="--i:13;">Welcome Back!<br /></h2>
            <p class="animation" style="--i:14;">Vanilla Crop Production in Western Uganda</p>
        </div>
    </div>
    

    <script src="signup_button.js"></script>

    <?php
    if (isset($login_error)) {
        echo '<p style="color: red;">Login Error: $login_error</p>';
    }
    if (isset($registration_error)) {
        echo '<p style="color: red;">Registration Error: $registration_error</p>';
    }
    ?>

</body>
</html>