<?php

use app\model\Personel;
use config\Config;

$personel = (isset($personel)) ? $personel : new Personel();
$remember = (isset($remember) && $remember) ? 'checked' : '';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/all.css">
    <link rel="stylesheet" href="/assets/css/popup.css">
    <link rel="stylesheet" href="/assets/css/signup.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <title>Personel Giriş</title>
</head>

<body>
    <div class="row d-flex justify-content-center mt-5 w-100">
        <div class="card col-10 col-sm-8 col-md-7 col-lg-5 p-0">
            <div class="card-header p-2 pl-3 pr-3 bg-white">
                <div class="row align-items-center justify-content-start">
                    <div class="col-6">
                        <h3>DentApp</h3>
                    </div>
                    <div class="col-6 text-right">
                        <i id="help_drawer_icon" class="fas fa-question-circle text-info"></i>
                    </div>
                </div>
                <p class="m-0 small text-muted">İşlemlerinize devam etmek için lütfen giriş yapın.</p>
            </div>
            <div class="card-body p-0 pl-3 pr-3">
                <form action="/personel/auth" method="POST" id="login_form">
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-center justify-content-between">
                        <label for="email" class="m-0 text-muted font-weight-bold">E-mail:</label>
                        <div class="w-80">
                            <input type="email" name="email" id="email" class="form-control form-control-sm mt-1" value="<?php echo htmlspecialchars($personel->getEmail()) ?>">
                        </div>
                    </div>
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-center justify-content-between">
                        <label for="sifre" class="m-0 text-muted font-weight-bold">Şifre: </label>
                        <div class="w-80 input-group input-group-sm password_input_group">
                            <input type="password" name="sifre" id="sifre" class="form-control form-control-sm">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-secondary">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="remember_me">
                        <label class="form-check-label text-muted" for="remember_me">Beni Hatırla</label>
                    </div> -->
                    <div class="row m-0 mt-2">
                        <button class="btn btn-info btn-block btn-sm mb-2">Giriş Yap</button>
                    </div>
                </form>
            </div>
            <div class="card-footer p-2 pl-3 bg-white">
                <p class="m-0 small text-muted">Tüm Hakları Saklıdır.</p>
                <p class="m-0 small text-muted">DentApp &copy <?php echo date('Y'); ?> | Destek için <a href="mailto:<?php echo Config::CLIENT_EMAIL ?>"><?php echo Config::CLIENT_EMAIL ?></a> | <?php echo Config::CLIENT_PHONE ?></p>
            </div>
        </div>
    </div>
    <script src="/assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/jquery.validate.js"></script>
    <script src="/assets/js/additional-methods.js"></script>
    <script src="/assets/js/popup.js"></script>
    <script src="/assets/js/password-show-hide.js"></script>
    <script>
        password_input_groups = document.getElementsByClassName('password_input_group');
        Array.prototype.forEach.call(password_input_groups, function(element) {
            provide_show_hide_password(element);
        });
    </script>
    <!-- <script src="/assets/js/login-validation.js"></script> -->
    <script>
        <?php app\utility\Popup::printAll() ?>
    </script>
    <script>
        var help_drawer_icon = document.getElementById('help_drawer_icon');
        if (help_drawer_icon) {
            help_drawer_icon.addEventListener('click', function() {
                console.log('clicked');
            });
        }
    </script>
</body>

</html>