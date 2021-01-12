$(document).ready(function() {
    $('#login_form').validate({
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
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: "E-mail address cannot be empty.",
                email: "E-mail address is not valid."
            }
        }
    });
});