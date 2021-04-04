var tckn_options = {
    mask: '99999999999',
    placeholder: '_',
    postValidation: function () {
        return true;
    },
    onUnMask: function (maskedValue, unmaskedValue, opts) {
        var regexp = new RegExp((opts['placeholder'] || '_'), 'g');
        return maskedValue.replace(/\s/, '').replace(regexp, '');
    },
    clearMaskOnLostFocus: false
}
var phone_options = {
    mask: '0 (999) 999 99 99',
    placeholder: '_',
    postValidation: function () {
        return true;
    },
    onUnMask: function (maskedValue, unmaskedValue, opts) {
        return maskedValue.replace(/\(/g, '').replace(/\s/g, '').replace(/\)/g, '');
    },
    clearMaskOnLostFocus: false
}
var time_options = {
    alias: 'datetime',
    inputFormat: 'HH:MM',
    placeholder: '_',
    postValidation: function () {
        return true;
    },
    onUnMask: function (maskedValue, unmaskedValue, opts) {
        var regexp = new RegExp((opts.placeholder || '_'), 'g');
        return maskedValue.replace(regexp, '0');
    },
    clearMaskOnLostFocus: false
}
$('#start_hour').inputmask(time_options);
$('#end_hour').inputmask(time_options);
$('#phone').inputmask(phone_options);
$('#tckn').inputmask(tckn_options);