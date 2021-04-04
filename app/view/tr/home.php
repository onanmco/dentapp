<?php

use app\utility\Auth;
use config\Config;

$auth_user = Auth::getAuthUser();
?>
<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/all.css">
    <link rel="stylesheet" href="/assets/css/popup.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <title>Anasayfa</title>
</head>

<body>
    <div class="row d-flex justify-content-center w-100">
        <div class="col-10 col-md-8 col-lg-6 text-center">
            <?php 
            if (Auth::isLoggedIn()) {
                $full_name = htmlspecialchars($auth_user->getFirstName()) . ' ' . htmlspecialchars($auth_user->getLastName());
                $welcome_msg = 'Hoş Geldiniz, ' . $full_name;
                echo "<h3>$welcome_msg</h3>";
                echo '<p>Çıkış yapmak için <a href="user/logout">buraya</a> tıklayın.</p>';
            } else {
                echo '<h3>Hoşgeldiniz !</h3>';
                echo '<p class="mb-0">İşlemlerinize devam edebilmek için lütfen giriş yapın.</p>';
                echo '<p class="mb-0">Giriş ekranına gitmek için <a href="/user/login">buraya</a> tıklayın.</p>';
                echo '<p class="mb-0">Hesabınızla ilgili sorunlar için <a href="mailto:' . Config::CLIENT_EMAIL . '">' . Config::CLIENT_EMAIL . '</a> adresinden bizlere ulaşabilirsiniz.</p>';
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