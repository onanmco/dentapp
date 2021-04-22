<?php

use app\utility\Auth;
use config\Config;

$auth_user = Auth::getAuthUser();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/all.css">
    <link rel="stylesheet" href="/assets/css/popup.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <title>Home</title>
</head>

<body>
    <div class="row d-flex justify-content-center w-100">
        <div class="col-10 col-md-8 col-lg-6 text-center">
            <?php 
            if (Auth::isLoggedIn()) {
                $full_name = htmlspecialchars($auth_user->getFirstName()) . ' ' . htmlspecialchars($auth_user->getLastName());
                $welcome_msg = 'Welcome, ' . $full_name;
                echo "<h3>$welcome_msg</h3>";
                echo '<p>Click <a href="user/logout">here</a> to logout.</p>';
            } else {
                echo '<h3>Welcome !</h3>';
                echo '<p class="mb-0">Please login to continue.</p>';
                echo '<p class="mb-0">Click <a href="/user/login">here</a> to go to login page.</p>';
                echo '<p class="mb-0">Contact us from <a href="mailto:' . Config::CLIENT_EMAIL . '">' . Config::CLIENT_EMAIL . '</a> for account issues.</p>';
            }
            ?>
        </div>
    </div>
    <script src="/assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/jquery.validate.js"></script>
    <script src="/assets/js/additional-methods.js"></script>
    <script src="/assets/js/popup.js"></script>
    <script>
        <?php app\utility\Popup::printAll() ?>
    </script>
</body>

</html>