$.validator.addMethod('is_valid_hour', function (value, element, param) {
    return is_valid_hour(value);
});
$.validator.addMethod('is_valid_range', function () {
    return is_valid_range(calc_start_date(), calc_end_date());
}, 'Başlangıç saati bitiş saatinden önce olmalıdır.');
$.validator.addMethod('is_not_past', function () {
    return is_not_past(calc_start_date());
}, 'Geçmiş tarihe randevu veremezsiniz.');
$.validator.addMethod('is_not_overlap', function () {
    return is_not_overlap(calc_start_date(), calc_end_date());
}, 'Girmiş olduğunuz aralıkta başka bir randevu var.');
$.validator.addMethod('is_valid_name_charset', function (value) {
    return is_valid_name_charset(value);
});
$.validator.addMethod('is_valid_tckn', function (value) {
    return is_valid_tckn(value);
});
$.validator.addMethod('is_valid_phone', function (value) {
    return is_valid_phone(value);
});

var validator = $('#modal_form').validate({
    errorPlacement: function (error, element) {
        error.appendTo($('#error'));
    },
    errorClass: 'error-class',
    wrapper: 'div',
    highlight: function (element, errorClass) {
        $(element).addClass(errorClass);
    },
    unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    resetForm: function () {
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
        last_name: {
            required: true,
            is_valid_name_charset: true,
        },
        first_name: {
            required: true,
            is_valid_name_charset: true,
        },
        tckn: {
            required: true,
            is_valid_tckn: true
        },
        phone: {
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
        first_name: {
            required: 'İsim alanı boş bırakılamaz.',
            is_valid_name_charset: 'İsim alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire karakterleri içerebilir.'
        },
        last_name: {
            required: 'Soyisim alanı boş bırakılamaz.',
            is_valid_name_charset: 'Soyisim alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire karakterleri içerebilir.'
        },
        tckn: {
            required: 'TCKN alanı boş bırakılamaz.',
            is_valid_tckn: 'Lütfen geçerli bir TCKN girin.'
        },
        phone: {
            is_valid_phone: 'Lütfen geçerli bir telefon girin.'
        }
    },
});