$(document).ready(function() {
    $.validator.addMethod(
        'validNameSet',
        function(value, element, param) {
            if (value.match(/^[a-zA-Z\s\.\'-]+$/g) == null) {
                return false;
            }
            return true;
        },
        'İsim alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire içerebilir.'
    );
    $.validator.addMethod(
        'validPasswordSet',
        function(value, element, param) {
            if (value.match(/^[\@\!\^\+\%\/\(\)\=\?\_\*\-\<\>\#\$\½\{\[\]\}\\\|\w]+$/g) == null) {
                return false;
            }
            return true;
        },
        'Şifre alanı yalnızca harf, sayı, özel karakterler, tire ve nokta içerebilir.'
    );
    $.validator.addMethod(
        'atLeastOneUppercase',
        function(value, element, param) {
            if (value.match(/[A-Z]+/g) == null) {
                return false;
            }
            return true;
        },
        'Şifre alanı en az bir adet büyük harf içermelidir.'
    );
    $.validator.addMethod(
        'atLeastOneDigit',
        function(value, element, param) {
            if (value.match(/\d+/g) == null) {
                return false;
            }
            return true;
        },
        'Şifre en az bir adet rakam içermelidir.'
    );
    $.validator.addMethod(
        'tckn',
        function(value, element, param) {
            if (value.match(/(^$)|(^\d{11}$)/g) == null) {
                return false;
            }
            return true;
        },
        'TCKN alanı yalnızca rakam içerebilir.'
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
            isim: {
                required: true,
                validNameSet: true
            },
            soyisim: {
                required: true,
                validNameSet: true
            },
            tckn: {
                tckn: true
            },
            email: {
                required: true,
                email: true
            },
            sifre: {
                required: true,
                minlength: 8,
                maxlength: 20,
                validPasswordSet: true,
                atLeastOneUppercase: true,
                atLeastOneDigit: true
            }
        },
        messages: {
            isim: {
                required: "İsim alanı boş bırakılamaz."
            },
            soyisim: {
                required: "Soyisim alanı boş bırakılamaz."
            },
            email: {
                required: "E-mail alanı boş bırakılamaz.",
                email: "Lütfen geçerli bir e-mail adresi girin."
            },
            sifre: {
                required: "Şifre alanı boş bırakılamaz.",
                minlength: "Şifre alanı en az 8 karakter içermelidir.",
                maxlength: "Şifre alanı en fazla 20 karakter içerebilir."
            }
        }
    });
});