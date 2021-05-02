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
    if ($('#search_bar').attr('data-selected_patient_id')) {
        $('#search_bar').removeAttr('data-selected_patient_id');
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
    if ($('#search_bar').attr('data-selected_patient_id')) {
        $('#search_bar').removeAttr('data-selected_patient_id');
    }
    setTimeout(() => {
        clear_popups();
    }, 2000);
});

$('#search_results').on('click', function (e) {
    if ($(e.target).attr('data-patient_id')) {
        $('#search').attr('data-selected_patient_id', $(e.target).attr('data-patient_id'));
        $('#search').val($(e.target).html());
        $('#search_results').addClass('d-none');
    }
});

var onDebouncedCompositeSearchTermChange = async function(term) {
    try {
        var response = await fetch('/api/patient/search', {
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
        show_popup(unknown_error.title, unknown_error.message, unknown_error.code);
        return;
    }
    if (response.status === 'success') {
        var patients = response['data']['results'];
        if (patients.length == 0) {
            var li = document.createElement('li');
            li.className = 'search_result';
            $('#search_results ul').html('');
            $(li).html('Sonuç bulunamadı');
            $('#search_results ul').append(li);
        } else {
            patients.forEach(function (patient) {
                var li = document.createElement('li');
                li.className = 'search_result';
                $(li).html('İsim: ' + patient.first_name + ', Soyisim: ' + patient.last_name + ', TCKN: ' + patient.tckn);
                $(li).attr('data-patient_id', '' + patient.id);
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
        show_popup(unknown_error.title, unknown_error.message, unknown_error.code);
        return;
    }
};

debouncedCompositeSearchTerm.addListener(onDebouncedCompositeSearchTermChange);

$('#search').on('input', async function (e) {

    $('#search').removeAttr('data-selected_patient_id');
    $('#search_results ul').html('');

    var pattern = newRegexp(composite_search_regexp.value);
    if (!pattern.test($(e.target).val())) {
        $(e.target).val('');
    }

    var search_value = $(e.target).val().trim().replace(/\s+/g, ' ');
    if (search_value.length >= composite_search_min_length.value) {
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
        var patient = {
            first_name: $('#first_name').val(),
            last_name: $('#last_name').val(),
            phone: $('#phone').inputmask('unmaskedvalue'),
            tckn: $('#tckn').inputmask('unmaskedvalue')
        }

        try {
            var patient_response = await fetch('/api/patient/register', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(patient)
            });
            patient_response = await patient_response.json();
        } catch (error) {
            show_popup(unknown_error.title, unknown_error.message, unknown_error.code);
            return;
        }
        if (patient_response.status === 'failure') {
            var errors = patient_response['data'];
            errors.forEach(function (error) {
                show_popup(error['title'], error['message'], error['code']);
            });
            return;
        } else if (patient_response.status !== 'success') {
            show_popup(unknown_error.title, unknown_error.message, unknown_error.code);
            return;
        }

        patient = patient_response['data']['saved_patient'];

        var event = {
            title: patient.first_name + ' ' + patient.last_name,
            start: start_date,
            end: end_date
        }

        var appointment = {
            user_id: user_id,
            patient_id: patient['id'],
            start: js_timestamp_to_unix_timestamp(start_date.getTime()),
            end: js_timestamp_to_unix_timestamp(end_date.getTime()),
            notes: $('#notes').val(),
            notify: false,
            appointment_type_id: $('#appointment_type_id').val()
        }

        if ($('#notify').val() == 1) {
            appointment['notify'] = true;
        } else {
            appointment['notify'] = false;
        }

        try {
            var appointment_response = await fetch('/api/appointment/create', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(appointment)
            });
            appointment_response = await appointment_response.json();
        } catch (error) {
            show_popup(unknown_error.title, unknown_error.message, unknown_error.code);
            await fetch('/api/patient/delete/' + patient['id']);
            return;
        }

        if (appointment_response.status === 'success') {
            show_popup(appointment_response['data']['title'], appointment_response['data']['message'], 200);
            $('#modal').modal('hide');
        } else if (appointment_response.status === 'failure') {
            await fetch('/api/patient/delete/' + patient['id']);
            var errors = appointment_response['data'];
            errors.forEach(function (error) {
                show_popup(error['title'], error['message'], error['code']);
            });
            return;
        } else {
            await fetch('/api/patient/delete/' + patient['id']);
            show_popup(unknown_error.title, unknown_error.message, unknown_error.code);
            return;
        }


    } else {
        if (!$('#search').attr('data-selected_patient_id')) {
            show_popup(please_select_patient.title, please_select_patient.message, please_select_patient.code);
            return;
        }
        if (!(invalid_patient_id_regexp).test('' + $('#search').attr('data-selected_patient_id'))) {
            show_popup(invalid_patient_id_response.title, invalid_patient_id_response.message, invalid_patient_id_response.code);
            return;
        }
        var appointment = {
            user_id: user_id,
            patient_id: $('#search').attr('data-selected_patient_id'),
            start: js_timestamp_to_unix_timestamp(start_date.getTime()),
            end: js_timestamp_to_unix_timestamp(end_date.getTime()),
            notes: $('#notes').val(),
            notify: false,
            appointment_type_id: $('#appointment_type_id').val()
        }

        if ($('#notify').val() == 1) {
            appointment['notify'] = true;
        } else {
            appointment['notify'] = false;
        }

        try {
            var appointment_response = await fetch('/api/appointment/create', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(appointment)
            });
            appointment_response = await appointment_response.json();
        } catch (error) {
            show_popup(unknown_error.title, unknown_error.message, unknown_error.code);
            return;
        }

        if (appointment_response.status === 'success') {
            show_popup(appointment_response['data']['title'], appointment_response['data']['message'], 200);
            $('#modal').modal('hide');
        } else if (appointment_response.status === 'failure') {
            var errors = appointment_response['data'];
            errors.forEach(function (error) {
                show_popup(error['title'], error['message'], error['code']);
            });
            return;
        } else {
            show_popup(unknown_error.title, unknown_error.message, unknown_error.code);
            return;
        }

        $('search').removeAttr('data-selected_patient_id');
        $('#modal').modal('hide');
    }
});

async function init () {
    var list = await fetch('/api/appointment/get-all-by-range', {
        method: "POST",
        body: JSON.stringify({
            start: js_timestamp_to_unix_timestamp(calendar.view.activeStart.getTime()),
            end: js_timestamp_to_unix_timestamp(calendar.view.activeEnd.getTime()),
        })
    });

    list = await list.json();
    var status = list.status || false;
    if (status === 'success') {
        calendar.render();
        calendar.updateSize();
        calendar.removeAllEvents();
        list.data.appointment_list.forEach(function (appointment) {
            calendar.addEvent(appointment);
            console.log(appointment);
        });

    }
}
init();