<?php

use app\constant\Messages;
use config\Config;

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
    <title>İletişim</title>
</head>

<body>
    <div class="row d-flex justify-content-center mt-5 w-100">
        <div class="card col-10 col-sm-8 col-md-7 col-lg-5 p-0">
            <div class="card-header p-2 pl-3 pr-3 bg-white">
                <div class="row align-items-center justify-content-start">
                    <div class="col-6">
                        <h3><?php echo Config::CLIENT_APP_NAME; ?></h3>
                    </div>
                    <div class="col-6 text-right">
                        <i id="help_drawer_icon" class="fas fa-question-circle text-info"></i>
                    </div>
                </div>
                <p class="m-0 small text-muted">İletişim formu</p>
            </div>
            <div class="card-body p-0 pl-3 pr-3">
                <form id="contact_form">
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-center justify-content-between">
                        <label for="full_name" class="m-0 text-muted font-weight-bold">İsim:</label>
                        <div class="w-80">
                            <input type="text" name="full_name" id="full_name" class="form-control form-control-sm mt-1">
                        </div>
                    </div>
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-center justify-content-between">
                        <label for="email" class="m-0 text-muted font-weight-bold">E-mail:</label>
                        <div class="w-80">
                            <input type="email" name="email" id="email" class="form-control form-control-sm mt-1">
                        </div>
                    </div>
                    <div class="form-group row m-0 mt-2 flex-nowrap align-items-start justify-content-between">
                        <label for="message" class="m-0 text-muted font-weight-bold">Mesaj:</label>
                        <div class="w-80">
                            <textarea name="message" id="message" rows="5" class="form-control form-control-sm mt-1"></textarea>
                        </div>
                    </div>
                    <div class="row m-0 mt-2">
                        <button id="send_button" class="btn btn-info btn-block btn-sm mb-2">Gönder</button>
                    </div>
                </form>
            </div>
            <div class="card-footer p-2 pl-3 bg-white">
                <p class="m-0 small text-muted">Tüm Hakları Saklıdır.</p>
                <p class="m-0 small text-muted"><?php echo Config::CLIENT_APP_NAME; ?> &copy <?php echo date('Y'); ?> | Destek için <a href="mailto:<?php echo Config::CLIENT_EMAIL ?>"><?php echo Config::CLIENT_EMAIL ?></a> | <?php echo Config::CLIENT_PHONE ?></p>
            </div>
        </div>
    </div>
    <script src="/assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/popup.js"></script>
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
    <script>
        var form = document.getElementById('contact_form');
        var send_button = document.getElementById('send_button');
        var full_name = document.getElementById('full_name');
        var email = document.getElementById('email');
        var message = document.getElementById('message');

        send_button.addEventListener('click', async function(e) {
            e.preventDefault();

            var response = await fetch('/api/contact/gonder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'full_name': full_name.value,
                    'email': email.value,
                    'message': message.value
                })
            });

            response = await response.json();
            console.log(response);

            status = response.status | false;
            switch (response.status) {
                case 'success':
                    show_popup(response.data.title, response.data.message, response.data.code);
                    break;
                case 'failure':
                    response.data.forEach(function(error) {
                        show_popup(error.title, error.message, error.code);
                    });
                    break;
                default:
                    error = <?php echo json_encode(Messages::UNKNOWN_ERROR) ?>;
                    show_popup(error.title, error.message, error.code);
                    break;
            }
        });
    </script>
</body>

</html>