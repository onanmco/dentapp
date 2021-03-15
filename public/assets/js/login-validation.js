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
            sifre: {
                required: true,
            }
        },
        messages: {
            email: {
                required: "E-mail alanı boş bırakılamaz.",
                email: "Lütfen geçerli bir e-mail girin."
            },
            sifre: {
                required: "Şifre alanı boş bırakılamaz."
            }
        }
    });
});