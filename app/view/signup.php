<?php

use app\model\Personel;
use config\Config;

$personel = (isset($personel)) ? $personel : new Personel();
$errors = (isset($errors)) ? $errors : [];
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
    <title>Title</title>
</head>

<body>
    <div id="help_drawer" class="d-none row justify-content-center m-0 mt-5 w-100">
        <div class="card col-10 col-sm-8 col-md-7 col-lg-5 p-0">
            <div class="card-header p-2 pl-3 pr-3 bg-white">
                <div class="row align-items-center">
                    <div class="col-6 text-left">
                        <h3>Yardım</h3>
                    </div>
                    <div class="col-6 text-right">
                        <i id="help_drawer_close_icon" class="fas fa-times-circle text-danger"></i>
                    </div>
                </div>
                <p class="m-0 small text-muted">Personel Giriş Ekranı Yardım Penceresi</p>
            </div>
            <div class="card-body p-0 pl-3 pr-3 small text-muted pt-2">
                <p>Personel girişleri <strong>e-mail adresi</strong> üzerinden yapılır. Bu yüzden lütfen geçerli bir e-mail adresi girdiğinizden emin olun.</p>
                <p>E-mail adresinde <strong>Türkçe karakterler kullanmayın.</strong></p>
                <p>Hesap güvenliği açısından, <strong>şifre en az 8, en fazla 20 karakterden oluşmalıdır.</strong> Ayrıca şifre <strong>en az 1 adet büyük harf ve en az bir adet rakam içermelidir.</strong></p>
                <p>Şifrede <strong>Türkçe karakterler kullanmayın.</strong></p>
                <p>Yabancı personel ihtimaline karşılık, TCKN alanı boş bırakılabilir. Ancak eğer giriş yapılırsa sadece 11 haneli geçerli bir TCKN girdiğinizden emin olun.</p>
                <p>Maaş alanı boş bırakılabilir. Ancak eğer giriş yapılırsa <strong>ondalıklı değerler için virgül yerine nokta kullanın.</strong></p>
                <p>Meslek alanı kaydetmiş olduğunuz personelin yetkilerini otomatik olarak belirleyecektir. Bu yüzden <strong>kaydedeceğiniz personelin doğru meslek grubunu seçtiğinizden emin olun.</strong></p>
            </div>
        </div>
    </div>
    <div class="row d-flex justify-content-center m-0 mt-5 w-100">
        <div class="card col-10 col-sm-8 col-md-7 col-lg-5 p-0">
            <div class="card-header p-2 pl-3 pr-3 bg-white">
                <div class="row align-items-center">
                    <div class="col-6 text-left">
                        <h3>DentApp</h3>
                    </div>
                    <div class="col-6 text-right">
                        <i id="help_drawer_icon" class="fas fa-question-circle text-info"></i>
                    </div>
                </div>
                <p class="m-0 small text-muted">Personel Kayıt Ekranı</p>
            </div>
            <div class="card-body p-0 pl-3 pr-3">
                <?php 
                if (!empty($errors)) {
                    echo '<h3 class="text-danger">Hatalar: </h3>';
                    echo '<ul>';
                    foreach ($errors as $error) {
                        echo '<li class="text-muted small">' . htmlspecialchars($error) . '</li>';
                    }
                    echo '</ul>';
                }
                ?>
                <form action="/personel/olustur" method="POST">
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-center justify-content-between">
                        <label for="isim" class="m-0 text-muted font-weight-bold">İsim:</label>
                        <div class="w-80">
                            <input type="text" name="isim" id="isim" class="form-control form-control-sm mt-1" value="<?php echo htmlspecialchars($personel->getIsim()) ?>">
                        </div>
                    </div>
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-center justify-content-between">
                        <label for="soyisim" class="m-0 text-muted font-weight-bold">Soyisim:</label>
                        <div class="w-80">
                            <input type="text" name="soyisim" id="soyisim" class="form-control form-control-sm mt-1" value="<?php echo htmlspecialchars($personel->getSoyisim()) ?>">
                        </div>
                    </div>
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
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-center justify-content-between">
                        <label for="tckn" class="m-0 text-muted font-weight-bold">TCKN:</label>
                        <div class="w-80">
                            <input type="text" name="tckn" id="tckn" class="form-control form-control-sm mt-1" value="<?php echo htmlspecialchars($personel->getTckn()) ?>">
                        </div>
                    </div>
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-center justify-content-between">
                        <label for="maas" class="m-0 text-muted font-weight-bold">Maaş:</label>
                        <div class="w-80">
                            <input type="text" name="maas" id="maas" class="form-control form-control-sm mt-1" value="<?php echo htmlspecialchars($personel->getMaas()) ?>">
                        </div>
                    </div>
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-center justify-content-between">
                        <label for="meslek" class="m-0 text-muted font-weight-bold">Meslek:</label>
                        <div class="w-80">
                            <select type="text" name="meslek" id="meslek" class="form-control form-control-sm mt-1">
                                <?php
                                $meslek = $personel->getMeslek();
                                if ($meslek === 'sekreter' || $meslek === 'hekim' || $meslek === 'patron') {
                                    echo '<option value="' . $meslek . '" selected>' . ucfirst($meslek) . '</option>';
                                }
                                ?>
                                <option value="sekreter">Sekreter</option>
                                <option value="hekim">Hekim</option>
                                <option value="patron">Patron</option>
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mt-2">
                        <button type="submit" class="btn btn-info btn-block btn-sm mb-2">Personel Kaydet</button>
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
    <script>
        var help_drawer = document.getElementById('help_drawer');
        var help_drawer_icon = document.getElementById('help_drawer_icon');
        var help_drawer_close_icon = document.getElementById('help_drawer_close_icon');
        help_drawer_icon.addEventListener('click', function (e) {
            e.preventDefault();
            help_drawer.classList.remove('d-none');
            help_drawer.classList.add('d-flex');
        });
        help_drawer_close_icon.addEventListener('click', function (e) {
            e.preventDefault();
            help_drawer.classList.remove('d-flex');
            help_drawer.classList.add('d-none');
        });
    </script>
    <!-- <script src="/assets/js/signup-validation.js"></script> -->
    <script>
        <?php app\utility\Popup::printAll() ?>
    </script>
</body>

</html>