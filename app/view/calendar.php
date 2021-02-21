<?php

use app\constant\Environment;
use app\utility\Auth;
use config\Config;

$personel = Auth::getAuthPersonel();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/all.css">
    <link rel="stylesheet" href="/assets/css/popup.css">
    <link rel="stylesheet" href="/assets/css/fullcalendar.css">
    <link rel="stylesheet" href="/assets/css/calendar.css">

    <title>Randevu</title>
</head>

<body>

    <div class="modal" id="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex flex-column align-items-start">
                    <div class="d-flex justify-content-between w-100">
                        <h4 class="modal-title" id="modal_title"><?php echo Config::CLIENT_APP_NAME; ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <p class="m-0 small text-muted">Randevu Ekleme Ekranı</p>
                </div>
                <div class="modal-body">
                    <!-- <p id="error" class="small text-danger font-weight-bold"></p> -->
                    <form action="" id="modal_form">
                        <p id="error" class="small text-danger font-weight-bold m-0"></p>
                        <p id="current_date" class="small text-success m-0"></p>
                        <div class="form-row">
                            <div class="col-12 col-lg-6">
                                <label for="start_hour" class="small text-muted">Başlangıç Saati</label>
                                <input type="text" name="start_hour" id="start_hour" class="form-control form-control-sm">
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="end_hour" class="small text-muted">Bitiş Saati</label>
                                <input type="text" name="end_hour" id="end_hour" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div id="existing_record" class="form-row d-none">
                            <div class="col-6">
                                <label for="search" class="small text-muted">Hasta Ara</label>
                            </div>
                            <div class="col-6 text-right" id="new_or_existing">
                                <a href="#" class="toggle small">Yeni Hasta</a>
                                <span class=""></span>
                            </div>
                            <div id="search_wrapper" class="col-12">
                                <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="İsim, soyisim ya da TCKN ile arayın.">
                                <div id="search_results">
                                    <ul class="list-unstyled">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="new_record" class="form-row">
                            <div class="col-12 col-lg-6">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="isim" class="small text-muted">Hasta Adı:</label>
                                    </div>
                                    <div class="col-6 text-right d-lg-none">
                                        <a href="" class="toggle small">Mevcut Hasta</a>
                                    </div>
                                </div>
                                <input type="text" name="isim" id="isim" class="form-control form-control-sm">
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="soyisim" class="small text-muted">Hasta Soyadı:</label>
                                    </div>
                                    <div class="col-6 text-right d-none d-lg-block">
                                        <a href="" class="toggle small">Mevcut Hasta</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <input type="text" name="soyisim" id="soyisim" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="telefon" class="small text-muted">Tel:</label>
                                <input type="text" name="telefon" id="telefon" class="form-control form-control-sm">
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="tckn" class="small text-muted">TCKN:</label>
                                <input type="text" name="tckn" id="tckn" class="form-control form-control-sm">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="close" type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Kapat</button>
                    <button id="submit" form="modal_form" type="submit" class="btn btn-sm btn-info">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-8">
            <div id="calendar"></div>
        </div>
    </div>

    <script src="/assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script>
        var name_charset = <?php echo json_encode(Environment::NAME_CHARSET, JSON_UNESCAPED_UNICODE); ?>;
        var personel_id = <?php echo $personel->getId(); ?>;
        var composite_search_charset = <?php echo json_encode(Environment::COMPOSITE_SEARCH_CHARSET); ?>;
        var composite_search_min_length = <?php echo Environment::COMPOSITE_SEARCH_MIN_LENGTH; ?>;
    </script>
    <script src="/assets/js/jquery.validate.js"></script>
    <script src="/assets/js/additional-methods.js"></script>
    <script src="/assets/js/popup.js"></script>
    <script src="/assets/js/fullcalendar.js"></script>
    <script src="/assets/js/fullcalendar_locales.js"></script>
    <script src="/assets/js/jquery.inputmask.js"></script>
    <script src="/assets/js/utility-functions.js"></script>
    <script src="/assets/js/calendar_inputmask_definitions.js"></script>
    <script src="/assets/js/calendar_fullcalendar_definitions.js"></script>
    <script src="/assets/js/calendar_main.js"></script>
    <script src="/assets/js/calendar_utility_functions.js"></script>
    <script src="/assets/js/calendar_jquery_validator_definitions.js"></script>

</body>

</html>