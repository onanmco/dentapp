<?php

use app\constant\Constants;
use app\constant\Constraints;
use app\constant\Fields;
use app\constant\Messages;
use app\model\Group;
use app\model\User;
use app\utility\Auth;
use app\utility\UtilityFunctions;
use config\Config;

$user = (isset($user)) ? $user : new User();
$errors = (isset($errors)) ? $errors : [];
?>
<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/all.css">
    <link rel="stylesheet" href="/assets/css/popup.css">
    <link rel="stylesheet" href="/assets/css/signup.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <title>Personel Kayıt</title>
</head>

<body>
    <div id="help_drawer" class="d-none row justify-content-center m-0 w-100">
        <div class="card col-10 col-sm-8 col-md-7 col-lg-5 p-0 mt-5">
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
    <div class="row d-flex justify-content-center m-0 w-100">
        <div class="card col-10 col-sm-8 col-md-7 mt-5 col-lg-5 p-0">
            <div class="card-header p-2 pl-3 pr-3 bg-white">
                <div class="row align-items-center">
                    <div class="col-6 text-left">
                        <h3><?php echo Config::CLIENT_APP_NAME; ?></h3>
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
                <form action="/user/register" method="POST" id="signup_form">
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-baseline justify-content-between">
                        <label for="first_name" class="m-0 text-muted font-weight-bold">
                            <?php echo UtilityFunctions::turkish_uc_first(Fields::FIRST_NAME()) . ':'; ?>
                        </label>
                        <div class="w-80">
                            <div class="input-group input-group-sm">
                                <input type="text" name="first_name" id="first_name" class="form-control form-control-sm mt-1" value="<?php echo htmlspecialchars($user->getFirstName()) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-baseline justify-content-between">
                        <label for="last_name" class="m-0 text-muted font-weight-bold">
                            <?php echo UtilityFunctions::turkish_uc_first(Fields::LAST_NAME()) . ':'; ?>
                        </label>
                        <div class="w-80">
                            <div class="input-group input-group-sm">
                                <input type="text" name="last_name" id="last_name" class="form-control form-control-sm mt-1" value="<?php echo htmlspecialchars($user->getLastName()) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-baseline justify-content-between">
                        <label for="email" class="m-0 text-muted font-weight-bold">
                            <?php echo UtilityFunctions::turkish_uc_first(Fields::EMAIL()) . ':'; ?>
                        </label>
                        <div class="w-80">
                            <div class="input-group input-group-sm">
                                <input type="email" name="email" id="email" class="form-control form-control-sm mt-1" value="<?php echo htmlspecialchars($user->getEmail()) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-baseline justify-content-between">
                        <label for="password" class="m-0 text-muted font-weight-bold">
                            <?php echo UtilityFunctions::turkish_uc_first(Fields::PASSWORD()) . ':'; ?>
                        </label>
                        <div class="w-80">
                            <div class="input-group input-group-sm password_input_group">
                                <input type="password" name="password" id="password" class="form-control form-control-sm">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-baseline justify-content-between">
                        <label for="tckn" class="m-0 text-muted font-weight-bold">
                            <?php echo UtilityFunctions::turkish_uc_first(Fields::TCKN()) . ':'; ?>
                        </label>
                        <div class="w-80">
                            <div class="input-group input-group-sm">
                                <input type="text" name="tckn" id="tckn" class="form-control form-control-sm mt-1" value="<?php echo htmlspecialchars($user->getTckn()) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-baseline justify-content-between">
                        <label for="group_id" class="m-0 text-muted font-weight-bold">
                            <?php echo UtilityFunctions::turkish_uc_first(Fields::USER_GROUP()) . ':'; ?>
                        </label>
                        <div class="w-80">
                            <div class="input-group input-group-sm">
                                <select type="text" name="group_id" id="group_id" class="form-control form-control-sm mt-1">
                                    <?php
                                    $group_id = $user->getGroupId();
                                    $all_roles = Group::getAll();

                                    foreach ($all_roles as $row) {
                                        if ($group_id === $row->getId()) {
                                            echo '<option value="' . $row->getId() . '" selected>' . ucfirst($row->getGroup()) . '</option>';
                                        }
                                    }

                                    foreach ($all_roles as $row) {
                                        echo '<option value="' . $row->getId() . '">' . ucfirst($row->getGroup()) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 mt-2">
                        <button type="submit" class="btn btn-info btn-block btn-sm mb-2">Personel Kaydet</button>
                    </div>
                </form>
            </div>
            <div class="card-footer p-2 pl-3 bg-white">
                <p class="m-0 small text-muted">
                    <?php echo Constants::ALL_RIGHTS_RESERVED(); ?>
                </p>
                <p class="m-0 small text-muted">
                    <?php echo Config::CLIENT_APP_NAME; ?> &copy 
                    <?php echo date('Y'); ?> | destek için <a href="mailto:
                    <?php echo Config::CLIENT_EMAIL ?>">
                    <?php echo Config::CLIENT_EMAIL ?></a> | 
                    <?php echo Config::CLIENT_PHONE ?></p>
            </div>
        </div>
    </div>
    <script src="/assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/utility-functions.js"></script>
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
        help_drawer_icon.addEventListener('click', function(e) {
            e.preventDefault();
            help_drawer.classList.remove('d-none');
            help_drawer.classList.add('d-flex');
            var offset = help_drawer.offsetTop;
            scrollTo(0, offset);
        });
        help_drawer_close_icon.addEventListener('click', function(e) {
            e.preventDefault();
            help_drawer.classList.remove('d-flex');
            help_drawer.classList.add('d-none');
        });
    </script>
    <script>
        var first_name_regexp = newRegexp(<?php echo json_encode(Constraints::NAME_REGEXP(Fields::FIRST_NAME())['value'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
        var first_name_regexp_msg = <?php echo json_encode(Constraints::NAME_REGEXP(Fields::FIRST_NAME())['message'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        var first_name_cannot_be_empty = <?php echo json_encode(Messages::CANNOT_BE_EMPTY(Fields::FIRST_NAME())); ?>;

        var last_name_regexp = newRegexp(<?php echo json_encode(Constraints::NAME_REGEXP(Fields::LAST_NAME())['value'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
        var last_name_regexp_msg = <?php echo json_encode(Constraints::NAME_REGEXP(Fields::LAST_NAME())['message'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
        var last_name_cannot_be_empty = <?php echo json_encode(Messages::CANNOT_BE_EMPTY(Fields::LAST_NAME())); ?>;

        var tckn_regexp = newRegexp(<?php echo json_encode(Constraints::TCKN_USER_SIGNUP_REGEXP(Fields::TCKN())['value'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
        var tckn_regexp_msg = <?php echo json_encode(Constraints::TCKN_USER_SIGNUP_REGEXP(Fields::TCKN())['message'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        var password_cannot_be_empty = <?php echo json_encode(Messages::CANNOT_BE_EMPTY(Fields::PASSWORD()), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        var password_regexp = newRegexp(<?php echo json_encode(Constraints::PASSWORD_REGEXP(Fields::PASSWORD())['value'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
        var password_regexp_msg = <?php echo json_encode(Constraints::PASSWORD_REGEXP(Fields::PASSWORD())['message'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        var password_min_len = <?php echo json_encode(Constraints::PASSWORD_MIN_LENGTH(Fields::PASSWORD())['value'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        var password_min_len_msg = <?php echo json_encode(Constraints::PASSWORD_MIN_LENGTH(Fields::PASSWORD())['message'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        var password_max_len = <?php echo json_encode(Constraints::PASSWORD_MAX_LEN(Fields::PASSWORD())['value'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        var password_max_len_msg = <?php echo json_encode(Constraints::PASSWORD_MAX_LEN(Fields::PASSWORD())['message'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        var password_regexp_uppercase = newRegexp(<?php echo json_encode(Constraints::PASSWORD_REGEXP_UPPERCASE(Fields::PASSWORD())['value'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
        var password_regexp_uppercase_msg = <?php echo json_encode(Constraints::PASSWORD_REGEXP_UPPERCASE(Fields::PASSWORD())['message'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        var password_regexp_digit = newRegexp(<?php echo json_encode(Constraints::PASSWORD_REGEXP_DIGIT(Fields::PASSWORD())['value'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
        var password_regexp_digit_msg = <?php echo json_encode(Constraints::PASSWORD_REGEXP_DIGIT(Fields::PASSWORD())['message'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        var email_cannot_be_empty = <?php echo json_encode(Messages::CANNOT_BE_EMPTY(Fields::EMAIL()), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        var invalid_email = <?php echo json_encode(Messages::INVALID_EMAIL(Fields::EMAIL()), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
    </script>
    <script src="/assets/js/signup-validation.js"></script>
    <script>
        <?php app\utility\Popup::printAll() ?>
    </script>
</body>

</html>