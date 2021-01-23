<?php

use app\utility\Auth;
use config\Config;

$auth_personel = Auth::getAuthPersonel();
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
    <div class="row m-0">
        <div class="col-md-4 col-lg-3 bg-light border-right p-0" style="height: 100vh;">
            <div class="row border-bottom pt-2 pb-2">
                <h3 class="col-12"><?php echo Config::CLIENT_APP_NAME; ?></h3>
                <p class="small text-muted m-0 col-12">Yönetim Paneli</p>
            </div>
            <div class="row">
                <ul class="list-unstyled border-top col-12 p-0 mt-5">
                    <a href="">
                        <li class="pt-2 pb-2 pl-3 border-bottom">
                            <i class="fas fa-user-friends"></i>
                            <span><strong>Randevular</strong></span>
                        </li>
                    </a>
                    <a href="">
                        <li class="pt-2 pb-2 pl-3 border-bottom">
                            <i class="fas fa-user-plus"></i>
                            <span><strong>Hasta Kaydı</strong></span>
                        </li>
                    </a>
                    <a href="">
                        <li class="pt-2 pb-2 pl-3 border-bottom">
                            <i class="fas fa-star"></i>
                            <span><strong>Menu</strong></span>
                        </li>
                    </a>
                    <a href="">
                        <li class="pt-2 pb-2 pl-3 border-bottom">
                            <i class="fas fa-star"></i>
                            <span><strong>Menu</strong></span>
                        </li>
                    </a>
                    <a href="">
                        <li class="pt-2 pb-2 pl-3 border-bottom">
                            <i class="fas fa-star"></i>
                            <span><strong>Menu</strong></span>
                        </li>
                    </a>
                </ul>
            </div>
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