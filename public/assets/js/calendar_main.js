var debouncedCompositeSearchTerm = {
    value: '',
    listeners: [],
    getValue() { return this.value; },
    setValue(val) {
        this.value = val;
        this.triggerListeners();
    },
    addListener(fn) {
        this.listeners.push(fn);
    },
    removeListener(fn_to_remove) {
        this.listeners = this.listeners.filter(function (fn) {
            if (fn != fn_to_remove) {
                return fn;
            }
        });
    },
    triggerListeners() {
        var term = this.value;
        this.listeners.forEach(function (listener) {
            listener.call(null, term);
        });
    },
};

var compositeSearchTerm = {
    value: '',
    timeoutID: null,
    getValue() { return this.value; },
    setValue(val) {
        clearTimeout(this.timeoutID);
        this.timeoutID = setTimeout(function() {
            debouncedCompositeSearchTerm.setValue(val);
        }, 500);
    },
};

$('#modal .toggle').click(function (e) {
    e.preventDefault();
    $('#modal_form input').removeClass('error-class');
    $('#error').html('');
    $('#existing_record').toggleClass('d-none');
    $('#new_record').toggleClass('d-none');
    $('#search_results ul').html('');
    $('#search_bar').val('');
    if ($('#search_bar').attr('data-selected_hasta_id')) {
        $('#search_bar').removeAttr('data-selected_hasta_id');
    }
});

$('#modal').on('hidden.bs.modal', function (e) {
    $('#modal_form').trigger('reset');
    $('#modal_form input').removeClass('error-class');
    $('#error').html('');
    $('#current_date').html('');
    $('#existing_record').addClass('d-none');
    $('#new_record').removeClass('d-none');
    $('#search_results ul').html('');
    $('#search_bar').val('');
    if ($('#search_bar').attr('data-selected_hasta_id')) {
        $('#search_bar').removeAttr('data-selected_hasta_id');
    }
    setTimeout(() => {
        clear_popups();
    }, 2000);
});

$('#search_results').on('click', function (e) {
    if ($(e.target).attr('data-hasta_id')) {
        $('#search').attr('data-selected_hasta_id', $(e.target).attr('data-hasta_id'));
        $('#search').val($(e.target).html());
        $('#search_results').addClass('d-none');
    }
});

var onDebouncedCompositeSearchTermChange = async function(term) {
    try {
        var response = await fetch('/api/hasta/ara', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                value: term
            })
        });
        response = await response.json();
    } catch (error) {
        show_popup('Sunucu Hatası', 'Bilinmeyen bir ağ hatası oluştu. Lütfen destek ekibimizle iletişim kurun.', 500);
        return;
    }
    if (response.status === 'success') {
        var hastalar = response['data']['sonuclar'];
        if (hastalar.length == 0) {
            var li = document.createElement('li');
            li.className = 'search_result';
            $('#search_results ul').html('');
            $(li).html('Sonuç bulunamadı');
            $('#search_results ul').append(li);
        } else {
            hastalar.forEach(function (hasta) {
                var li = document.createElement('li');
                li.className = 'search_result';
                $(li).html('İsim: ' + hasta.isim + ', Soyisim: ' + hasta.soyisim + ', TCKN: ' + hasta.tckn);
                $(li).attr('data-hasta_id', '' + hasta.id);
                $('#search_results ul').append(li);
            });
        }
        $('#search_results').removeClass('d-none');
    } else if (response.status === 'failure') {
        var errors = response['data'];
        errors.forEach(function (error) {
            show_popup(error['title'], error['message'], error['code']);
        });
        return;
    } else {
        show_popup('Sunucu Hatası', 'Response status\'u düzgün bir şekilde okunamadı. Lütfen destek ekibimizle iletişim kurun.', 500);
        return;
    }
};

debouncedCompositeSearchTerm.addListener(ondebouncedCompositeSearchTermChange);

$('#search').on('input', async function (e) {

    $('#search').removeAttr('data-selected_hasta_id');
    $('#search_results ul').html('');

    var pattern = newRegexp(composite_search_charset);
    if (!pattern.test($(e.target).val())) {
        $(e.target).val('');
    }

    var search_value = $(e.target).val().trim().replace(/\s+/g, ' ');
    if (search_value.length >= composite_search_min_length) {
        compositeSearchTerm.setValue(search_value);
    }
});

$('#submit').on('click', async function (e) {
    e.preventDefault();

    if ($('#modal_form').valid() !== true) {
        return false;
    }

    var start_date = calc_start_date();
    var end_date = calc_end_date();

    if ($('#new_record').hasClass('d-none') === false) {
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
            errors.forEach(function (error) {
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
            personel_id: personel_id,
            hasta_id: hasta['id'],
            baslangic: js_timestamp_to_unix_timestamp(start_date.getTime()),
            bitis: js_timestamp_to_unix_timestamp(end_date.getTime()),
            notlar: $('#notlar').val(),
            hatirlat: false,
            randevu_turu_id: $('#randevu_turu_id').val()
        }

        if ($('#hatirlat').val() == 1) {
            randevu['hatirlat'] = true;
        } else {
            randevu['hatirlat'] = false;
        }

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
            await fetch('/api/hasta/sil/' + hasta['id']);
            return;
        }

        if (randevu_response.status === 'success') {
            show_popup(randevu_response['data']['title'], randevu_response['data']['message'], 200);
            $('#modal').modal('hide');
        } else if (randevu_response.status === 'failure') {
            await fetch('/api/hasta/sil/' + hasta['id']);
            var errors = randevu_response['data'];
            errors.forEach(function (error) {
                show_popup(error['title'], error['message'], error['code']);
            });
            return;
        } else {
            await fetch('/api/hasta/sil/' + hasta['id']);
            show_popup('Sunucu Hatası', 'Response status\'u düzgün bir şekilde okunamadı. Lütfen destek ekibimizle iletişim kurun.', 500);
            return;
        }


    } else {
        if (!$('#search').attr('data-selected_hasta_id')) {
            show_popup('Kayıt Başarısız', 'Lütfen geçerli bir hasta seçimi yapın.', 400);
            return;
        }
        if (!(/^\d+$/).test('' + $('#search').attr('data-selected_hasta_id'))) {
            show_popup('Kayıt Başarısız', 'Hasta ID sayısal bir değer olmalıdır.', 400);
            return;
        }
        var randevu = {
            personel_id: personel_id,
            hasta_id: $('#search').attr('data-selected_hasta_id'),
            baslangic: js_timestamp_to_unix_timestamp(start_date.getTime()),
            bitis: js_timestamp_to_unix_timestamp(end_date.getTime()),
            notlar: $('#notlar').val(),
            hatirlat: false,
            randevu_turu_id: $('#randevu_turu_id').val()
        }

        if ($('#hatirlat').val() == 1) {
            randevu['hatirlat'] = true;
        } else {
            randevu['hatirlat'] = false;
        }

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

        if (randevu_response.status === 'success') {
            show_popup(randevu_response['data']['title'], randevu_response['data']['message'], 200);
            $('#modal').modal('hide');
        } else if (randevu_response.status === 'failure') {
            var errors = randevu_response['data'];
            errors.forEach(function (error) {
                show_popup(error['title'], error['message'], error['code']);
            });
            return;
        } else {
            show_popup('Sunucu Hatası', 'Response status\'u düzgün bir şekilde okunamadı. Lütfen destek ekibimizle iletişim kurun.', 500);
            return;
        }

        $('search').removeAttr('data-selected_hasta_id');
        $('#modal').modal('hide');
    }
});