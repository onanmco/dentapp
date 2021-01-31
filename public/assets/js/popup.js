let popups = document.createElement('div');
popups.setAttribute('id', 'popups');
document.body.insertBefore(popups, document.body.firstChild);

function show_popup(title, message, code)
{
    let id = Date.now();
    type = (code >= 200 && code < 300) ? 'success' : 'danger';
    let html =
        `
        <div id="${id}" class="visible popup_wrapper col-lg-6 col-md-8 col-10">
            <div class="row h-100">
                <div class="popup_icon bg-${type}">
                    <img class="w-75" src="../assets/svgs/${type}.svg" alt="">
                </div>
                <div class="popup_body bg-light">
                    <div class="popup_title">${title}</div>
                    <div class="popup_message text-muted">${message}</div>
                </div>
                <div class="button_wrapper bg-light">
                    <img class="popup_closer" data-id="${id}" src="../assets/svgs/close_dimgray.svg" alt="">
                </div>
                <div class="right_placeholder bg-${type}"></div>
            </div>
        </div>
        `;
    popups.innerHTML += html;
}

function remove_popup(id)
{
    if (id == undefined || id == '' || id == null) {
        popups.innerHTML = '';
        return;
    }
    let element = document.getElementById(id);
    if (element == undefined || element == '' || element == null) {
        popups.innerHTML = '';
        return;
    }
    element.classList.remove('visible');
    element.classList.add('invisible');
    setTimeout(() => {
        popups.removeChild(document.getElementById(id));
    }, 300);
}

popups.addEventListener('click', function (e) {
    if (e.target.className == 'popup_closer') {
        let id = e.target.getAttribute('data-id');
        remove_popup(id);
    }
});