<?php

use app\utility\Auth;
use config\Config;

$personel = Auth::getAuthPersonel();
$personel_id = $personel->getId();

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
    <script src="/assets/js/jquery.validate.js"></script>
    <script src="/assets/js/additional-methods.js"></script>
    <script src="/assets/js/popup.js"></script>
    <script src="/assets/js/fullcalendar.js"></script>
    <script src="/assets/js/fullcalendar_locales.js"></script>
    <script src="/assets/js/jquery.inputmask.js"></script>
    <script>
        var tckn_options = {
            mask: '99999999999',
            placeholder: '_',
            postValidation: function() {
                return true;
            },
            onUnMask: function(maskedValue, unmaskedValue, opts) {
                var temp = maskedValue.replace(/\s/, '');
                var regexp = new RegExp((opts.placeholder || '_'), 'g');
                return temp.replace(regexp, '');
            },
            clearMaskOnLostFocus: false
        }
        var phone_options = {
            mask: '0 (999) 999 99 99',
            placeholder: '_',
            postValidation: function() {
                return true;
            },
            onUnMask: function(maskedValue, unmaskedValue, opts) {
                temp = maskedValue.replace(/\(/g, '');
                temp = temp.replace(/\s/g, '');
                return temp.replace(/\)/g, '');
            },
            clearMaskOnLostFocus: false
        }
        var time_options = {
            alias: 'datetime',
            inputFormat: 'HH:MM',
            placeholder: '_',
            postValidation: function() {
                return true;
            },
            onUnMask: function(maskedValue, unmaskedValue, opts) {
                var regexp = new RegExp((opts.placeholder || '_'), 'g');
                return maskedValue.replace(regexp, '0');
            },
            clearMaskOnLostFocus: false
        }
        $('#start_hour').inputmask(time_options);
        $('#end_hour').inputmask(time_options);
        $('#telefon').inputmask(phone_options);
        $('#tckn').inputmask(tckn_options);
    </script>
    <script>
        $('#modal .toggle').click(function(e) {
            e.preventDefault();
            $('#existing_record').toggleClass('d-none');
            $('#new_record').toggleClass('d-none');
        });
        $('#modal').on('hidden.bs.modal', function(e) {
            $('#modal_form').trigger('reset');
            $('#modal_form input').removeClass('error-class');
            $('#error').html('');
            $('#current_date').html('');
        });
        $('#search_results').on('click', function(e) {
            document.getElementById('search').setAttribute('data-selected_hasta_id', e.target.getAttribute('data-hasta_id'));
            document.getElementById('search').value = e.target.innerHTML;
            document.getElementById('search_results').classList.add('d-none');
        });
        $('#search').on('input', async function(e) {
            if (!e.target.value.match(/^[a-zA-Z\s\.\'\-ığüşöçİĞÜŞÖÇ]+$/)) {
                e.target.value = '';
            }
            var search_this = e.target.value;
            search_this = search_this.trim();
            search_this = search_this.replace(/\s+/g, ' ');
            var ul = document.querySelector('#search_results ul');
            document.getElementById('search').removeAttribute('data-selected_hasta_id');
            ul.innerHTML = '';
            if (search_this.length >= 3) {
                try {
                    var response = await fetch('/api/hasta/ara', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            deger: search_this
                        })
                    });
                    response = await response.json();
                } catch (error) {
                    show_popup('Sunucu Hatası', 'Bilinmeyen bir ağ hatası oluştu. Lütfen destek ekibimizle iletişim kurun.', 500);
                    return;
                }
                if (response.status === 'success') {
                    var hastalar = response['data']['sonuclar'];
                    console.log('arama basarili');
                    hastalar.forEach(function(hasta) {
                        var li = document.createElement('li');
                        li.className = 'search_result';
                        li.innerHTML = 'İsim: ' + hasta.isim + ', Soyisim: ' + hasta.soyisim + ', TCKN: ' + hasta.tckn;
                        li.setAttribute('data-hasta_id', '' + hasta.id);
                        ul.appendChild(li);
                        document.getElementById('search_results').classList.remove('d-none');
                    });
                } else if (response.status === 'failure') {
                    var errors = response['data'];
                    errors.forEach(function(error) {
                        show_popup(error['title'], error['message'], error['code']);
                    });
                    return;
                } else {
                    show_popup('Sunucu Hatası', 'Response status\'u düzgün bir şekilde okunamadı. Lütfen destek ekibimizle iletişim kurun.', 500);
                    return;
                }
            }
        });
        $('#submit').on('click', async function(e) {
            e.preventDefault();

            if ($('#modal_form').valid() !== true) {
                console.log('debug log: islem basarisiz');
                return false;
            }

            var start_date = calc_start_date();
            var end_date = calc_end_date();
            var new_record = $('#new_record').hasClass('d-none') === false;
            if (new_record) {
                var hasta = {
                    isim: $('#isim').val(),
                    soyisim: $('#soyisim').val(),
                    telefon: $('#telefon').inputmask('unmaskedvalue'),
                    tckn: $('#tckn').inputmask('unmaskedvalue')
                }

                try {
                    var hasta_response = await fetch('/api/hasta/kayit', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(hasta)
                    });
                    hasta_response = await hasta_response.json();
                } catch (error) {
                    show_popup('Sunucu Hatası', 'Bilinmeyen bir ağ hatası oluştu. Lütfen destek ekibimizle iletişim kurun.', 500);
                    return;
                }
                if (hasta_response.status === 'failure') {
                    var errors = hasta_response['data'];
                    errors.forEach(function(error) {
                        show_popup(error['title'], error['message'], error['code']);
                    });
                    return;
                } else if (hasta_response.status !== 'success') {
                    show_popup('Sunucu Hatası', 'Response status\'u düzgün bir şekilde okunamadı. Lütfen destek ekibimizle iletişim kurun.', 500);
                    return;
                }

                hasta = hasta_response['data']['kaydedilen_hasta'];

                var event = {
                    title: hasta.isim + ' ' + hasta.soyisim,
                    start: start_date,
                    end: end_date
                }

                var randevu = {
                    personel_id: <?php echo $personel_id ?>,
                    hasta_id: hasta['id'],
                    baslangic: js_timestamp_to_unix_timestamp(start_date.getTime()),
                    bitis: js_timestamp_to_unix_timestamp(end_date.getTime()),
                    notlar: '',
                    hatirlat: false
                }

                console.log(randevu);


                try {
                    var randevu_response = await fetch('/api/randevu/olustur', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(randevu)
                    });
                    randevu_response = await randevu_response.json();
                } catch (error) {
                    show_popup('Sunucu Hatası', 'Bilinmeyen bir ağ hatası oluştu. Lütfen destek ekibimizle iletişim kurun.', 500);
                    return;
                }
                console.log(randevu_response);

                if (randevu_response.status === 'success') {
                    show_popup(randevu_response['data']['title'], randevu_response['data']['message'], 200);
                } else if (randevu_response.status === 'failure') {
                    await fetch('/api/hasta/sil/' + hasta['id']);
                    var errors = randevu_response['data'];
                    errors.forEach(function(error) {
                        show_popup(error['title'], error['message'], error['code']);
                    });
                    return;
                } else {
                    show_popup('Sunucu Hatası', 'Response status\'u düzgün bir şekilde okunamadı. Lütfen destek ekibimizle iletişim kurun.', 500);
                    return;
                }


            } else {
                // find hasta from db


            }
        });
    </script>
    <script>
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'tr',
            timeZone: 'local',
            views: {},
            initialView: 'timeGridWeek',
            headerToolbar: {
                start: 'prev,next,today',
                center: 'title',
                end: 'dayGridMonth,timeGridWeek'
            },
            themeSystem: 'bootstrap',
            bootstrapFontAwesome: {
                prev: 'fa-angle-left',
                next: 'fa-angle-right'
            },
            height: "70vh",
            titleFormat: {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            },
            buttonText: {
                today: 'Bugün',
                month: 'Aylık',
                week: 'Haftalık',
                day: 'gün'
            },
            slotEventOverlap: false,
            allDaySlot: false,
            scrollTime: "08:00:00",
            firstDay: 1,
            dayHeaderFormat: {
                weekday: 'short',
                month: 'numeric',
                day: 'numeric',
                omitCommas: true
            },
            slotDuration: '00:15:00',
            slotLabelInterval: '01:00',
            slotLabelFormat: {
                hour: '2-digit',
                minute: '2-digit',
                omitZeroMinute: false,
                meridiem: false,
                hour12: false
            },
            selectable: true,
            selectMirror: true,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                omitZeroMinute: false,
                meridiem: false,
                hour12: false
            },
            events: [{
                title: 'Örnek Hasta',
                start: '2021-01-05T04:00:00',
                end: '2021-01-05T05:00:00',
                color: 'crimson',

            }, ],
            select: function(info) {
                var day_names = ['pazar', 'pazartesi', 'salı', 'çarşamba', 'perşembe', 'cuma', 'cumartesi'];
                var html = '<strong>Seçilen Tarih: </strong> ' + info.start.toLocaleString().substring(0, 10) + ' ' + uc_first(day_names[info.start.getDay()]);
                $('#current_date').html(html);
                var start_date = info.start;
                var end_date = info.end;
                end_date.setDate(start_date.getDate());
                $('#modal').attr('data-start', start_date.getTime());
                $('#modal').attr('data-end', end_date.getTime());
                var start_hour = (info.start.getHours() < 10) ? ('0' + info.start.getHours()) : info.start.getHours();
                var start_min = (info.start.getMinutes() < 10) ? ('0' + info.start.getMinutes()) : info.start.getMinutes();
                var end_hour = (info.end.getHours() < 10) ? ('0' + info.end.getHours()) : info.end.getHours();
                var end_min = (info.end.getMinutes() < 10) ? ('0' + info.end.getMinutes()) : info.end.getMinutes();
                $('#start_hour').val(start_hour + ':' + start_min);
                $('#end_hour').val(end_hour + ':' + end_min);
                $('#modal').modal('show');
            }
        });
        calendar.render();
        calendar.updateSize();
    </script>
    <script>
        function is_valid_tckn(string) {
            return Inputmask.isValid(string, tckn_options);
        }

        function is_valid_phone(string) {
            return Inputmask.isValid(string, phone_options);
        }

        function uc_first(string = '') {
            return string.charAt(0).toLocaleUpperCase() + string.slice(1);
        }

        function is_valid_hour(string) {
            try {
                return Inputmask.isValid(string, time_options);
            } catch (error) {
                return false;
            }
        }

        function is_valid_range(start_date, end_date) {
            return start_date.getTime() < end_date.getTime();
        }

        function is_not_past(start_date) {
            return start_date.getTime() >= Date.now();
        }

        function is_not_overlap(start_date, end_date) {
            var new_start = start_date.getTime();
            var new_end = end_date.getTime();
            var all_events = calendar.getEvents();

            for (var i = 0; i < all_events.length; ++i) {
                var existing_start = all_events[i].start.getTime();
                var existing_end = all_events[i].end.getTime();
                if (new_start < existing_end && new_end > existing_start) {
                    return false;
                }
            }
            return true;
        }

        function calc_start_date() {
            var start_date = new Date(Number($('#modal').attr('data-start')));
            var input_start_hour = $('#start_hour').inputmask('unmaskedvalue').split(':');
            start_date.setHours(Number(input_start_hour[0]));
            start_date.setMinutes(Number(input_start_hour[1]));
            return start_date;
        }

        function calc_end_date() {
            var end_date = new Date(Number($('#modal').attr('data-end')));
            var input_end_hour = $('#end_hour').inputmask('unmaskedvalue').split(':');
            end_date.setHours(Number(input_end_hour[0]));
            end_date.setMinutes(Number(input_end_hour[1]));
            return end_date;
        }

        function js_timestamp_to_unix_timestamp(js_timestamp) {
            return (js_timestamp / 1000).toFixed(0);
        }
    </script>
    <script>
        $.validator.addMethod('is_valid_hour', function(value, element, param) {
            return is_valid_hour(value);
        });
        $.validator.addMethod('is_valid_range', function() {
            return is_valid_range(calc_start_date(), calc_end_date());
        }, 'Başlangıç saati bitiş saatinden önce olmalıdır.');
        $.validator.addMethod('is_not_past', function() {
            return is_not_past(calc_start_date());
        }, 'Geçmiş tarihe randevu veremezsiniz.');
        $.validator.addMethod('is_not_overlap', function() {
            return is_not_overlap(calc_start_date(), calc_end_date());
        }, 'Girmiş olduğunuz aralıkta başka bir randevu var.');
        $.validator.addMethod('is_valid_name_set', function(value) {
            return value.match(/^[a-zA-Z\s\.\'\-ığüşöçİĞÜŞÖÇ]*$/);
        });
        $.validator.addMethod('is_valid_tckn', function(value) {
            return is_valid_tckn(value);
        });
        $.validator.addMethod('is_valid_phone', function(value) {
            return is_valid_phone(value);
        });

        var validator = $('#modal_form').validate({
            errorPlacement: function(error, element) {
                error.appendTo($('#error'));
            },
            errorClass: 'error-class',
            wrapper: 'div',
            highlight: function(element, errorClass) {
                $(element).addClass(errorClass);
            },
            unhighlight: function(element, errorClass) {
                $(element).removeClass(errorClass);
            },
            resetForm: function() {
                return 10;
            },
            groups: {
                hours: 'start_hour end_hour',
            },
            rules: {
                start_hour: {
                    is_valid_hour: true,
                    is_valid_range: true,
                    is_not_overlap: true,
                    is_not_past: true,
                },
                end_hour: {
                    is_valid_hour: true,
                    is_valid_range: true,
                    is_not_overlap: true,
                    is_not_past: true,
                },
                soyisim: {
                    required: true,
                    is_valid_name_set: true,
                },
                isim: {
                    required: true,
                    is_valid_name_set: true,
                },
                tckn: {
                    required: true,
                    is_valid_tckn: true
                },
                telefon: {
                    is_valid_phone: true
                }

            },
            messages: {
                start_hour: {
                    is_valid_hour: 'Lütfen başlangıç saatini girin.'
                },
                end_hour: {
                    is_valid_hour: 'Lütfen bitiş saatini girin.'
                },
                isim: {
                    required: 'İsim alanı boş bırakılamaz.',
                    is_valid_name_set: 'İsim alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire karakterleri içerebilir.'
                },
                soyisim: {
                    required: 'Soyisim alanı boş bırakılamaz.',
                    is_valid_name_set: 'Soyisim alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire karakterleri içerebilir.'
                },
                tckn: {
                    required: 'TCKN alanı boş bırakılamaz.',
                    is_valid_tckn: 'Lütfen geçerli bir TCKN girin.'
                },
                telefon: {
                    is_valid_phone: 'Lütfen geçerli bir telefon girin.'
                }

            },
        });
    </script>

</body>

</html>