$(document).ready(function() {
    $.validator.addMethod(
        'firstNameRegexp',
        function(value, element, param) {
            if (value.match(first_name_regexp) == null) {
                return false;
            }
            return true;
        },
        first_name_regexp_msg
    );
    $.validator.addMethod(
        'lastNameRegexp',
        function(value, element, param) {
            if (value.match(last_name_regexp) == null) {
                return false;
            }
            return true;
        },
        last_name_regexp_msg
    );
    $.validator.addMethod(
        'validPasswordSet',
        function(value, element, param) {
            if (value.match(password_regexp) == null) {
                return false;
            }
            return true;
        },
        password_regexp_msg
    );
    $.validator.addMethod(
        'atLeastOneUppercase',
        function(value, element, param) {
            if (value.match(password_regexp_uppercase) == null) {
                return false;
            }
            return true;
        },
        password_regexp_uppercase_msg
    );
    $.validator.addMethod(
        'atLeastOneDigit',
        function(value, element, param) {
            if (value.match(password_regexp_digit) == null) {
                return false;
            }
            return true;
        },
        password_regexp_digit_msg
    );
    $.validator.addMethod(
        'tckn',
        function(value, element, param) {
            if (value.match(tckn_regexp) == null) {
                return false;
            }
            return true;
        },
        tckn_regexp_msg
    );
    $('#signup_form').validate({
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
                error.addClass('validation_error');
                error.insertAfter(element.parent());
            } else {
                error.addClass('validation_error');
                error.insertAfter(element);
            }
        },
        wrapper: 'span',
        rules: {
            first_name: {
                required: true,
                firstNameRegexp: true
            },
            last_name: {
                required: true,
                lastNameRegexp: true
            },
            tckn: {
                tckn: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: password_min_len,
                maxlength: password_max_len,
                validPasswordSet: true,
                atLeastOneUppercase: true,
                atLeastOneDigit: true
            }
        },
        messages: {
            first_name: {
                required: first_name_cannot_be_empty
            },
            last_name: {
                required: last_name_cannot_be_empty
            },
            email: {
                required: email_cannot_be_empty,
                email: invalid_email
            },
            password: {
                required: password_cannot_be_empty,
                minlength: password_min_len_msg,
                maxlength: password_max_len_msg
            }
        }
    });
});