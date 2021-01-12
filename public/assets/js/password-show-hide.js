function provide_show_hide_password(password_input_group) {
    var button = password_input_group.querySelector('button');
    var input = password_input_group.querySelector('input');
    if (button) {
        var icon = button.querySelector('i');
    }
    if (button && input && icon) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            if (icon.classList.contains('fa-eye-slash')) {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else if (icon.classList.contains('fa-eye')) {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    }
}