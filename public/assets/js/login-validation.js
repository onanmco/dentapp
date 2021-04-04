$(document).ready(function() {
    $('#login_form').validate({
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
                error.addClass('validation_error');
                error.appendTo(element.parent().parent());
            } else {
                error.addClass('validation_error');
                error.appendTo(element.parent());
            }
        },
        wrapper: 'div',
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            }
        },
        messages: {
            email: {
                required: email_cannot_be_empty,
                email: invalid_email
            },
            password: {
                required: password_cannot_be_empty
            }
        }
    });
});