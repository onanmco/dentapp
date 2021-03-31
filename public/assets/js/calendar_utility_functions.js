function is_valid_name_charset(string) {
    return newRegexp(name_regexp.value).test(string);
}

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