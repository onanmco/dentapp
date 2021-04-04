<?php

use app\constant\Constants;
use app\utility\Auth;

$auth_user = Auth::getAuthUser();
$language = Auth::getUserLanguage();
?>
<!doctype html>
<html lang="<?php echo $language; ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/all.css">
    <link rel="stylesheet" href="/assets/css/popup.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <title><?php echo Constants::HOME_PAGE_TITLE(); ?></title>
</head>

<body>
    <div class="row d-flex justify-content-center w-100">
        <div class="col-10 col-md-8 col-lg-6 text-center">
            <?php 
            if (Auth::isLoggedIn()) {
                $welcome_msg = Constants::HOME_PAGE_WELCOME();
                $full_name = htmlspecialchars($auth_user->getFirstName()) . ' ' . htmlspecialchars($auth_user->getLastName());
                echo "<h3> $welcome_msg $full_name</h3>";
                echo Constants::HOME_PAGE_LOGOUT_TEXT();
            } else {
                echo Constants::HOME_PAGE_VISITOR_TEXT();
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