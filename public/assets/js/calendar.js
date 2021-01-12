async function main(){
    var calendar = document.getElementById('calendar');
    var calendar_nav = document.getElementById('calendar_nav');
    var calendar_height_percent = calendar.getAttribute('data-calendar_height') || 60;
    var week_offset = 0;
    build_calendar(calendar, calendar_nav, calendar_height_percent, week_offset);

    window.addEventListener('resize', function (e) {
        set_calendar_height(calendar, calendar_height_percent);
    });

    calendar.addEventListener('click', function (e) {
        e.preventDefault();
        if (e.target.classList.contains('calendar_clickable')) {
            console.log(e.target.getAttribute('data-hour_offset'));
            console.log(e.target.getAttribute('data-timestamp'));
        }
    });

    calendar_nav.addEventListener('click', function (e) {
        e.preventDefault();
        if (e.target.id === 'calendar_prev' || e.target.parentElement.id === 'calendar_prev') {
            week_offset--;
            build_calendar(calendar, calendar_nav, calendar_height_percent, week_offset);
        }
        if (e.target.id === 'calendar_next' || e.target.parentElement.id === 'calendar_next') {
            week_offset++;
            build_calendar(calendar, calendar_nav, calendar_height_percent, week_offset);
        }
        if (e.target.id === 'calendar_today' || e.target.parentElement.id === 'calendar_today') {
            week_offset = 0;
            build_calendar(calendar, calendar_nav, calendar_height_percent, week_offset);
        }
    });
}

main();


// func. definitions
function get_cell_height(table_height) {
    return Math.floor(window.innerHeight * table_height / 1000 * 10 / 11);
}

function build_calendar(calendar, calendar_nav, calendar_height_percent, week_offset = 0)
{
    var week_days = get_week_days(week_offset);
    init_calendar(calendar, week_days);
    set_calendar_height(calendar, calendar_height_percent);
    jump_to_8am(calendar, calendar_height_percent);
    init_calendar_nav(calendar_nav, week_days);
}

function init_calendar_nav(calendar_nav, week_days) {
    calendar_nav.className = 'row mb-2';
    calendar_nav.innerHTML = 
    `
    <div id="calendar_buttons" class="col-3 text-left">
        <button id="calendar_prev" class="btn btn-light border rounded-circle text-center">
            <i class="fas fa-angle-left"></i>
        </button>
        <button id="calendar_next" class="btn btn-light border rounded-circle text-center">
            <i class="fas fa-angle-right"></i>
        </button>
    </div>
    <div id="calendar_heading" class="col-6">
        <h6 class="m-0">Tarih</h6>
    </div>
    <div id="calendar_today" class="col-3">
        <button class="btn btn-sm btn-light border font-weight-bold text-muted">
            Bugün
        </button>
    </div>
    `;
    calendar_nav.querySelector('#calendar_heading h6').innerHTML = week_days[1]['local_date'] + ' - ' + week_days[7]['local_date'];
    calendar_nav.querySelector('#calendar_today button').style.marginRight = (calendar.querySelector('tbody').offsetWidth - calendar.querySelector('tbody').clientWidth) + 'px';
}

function set_calendar_height(calendar, calendar_height_percent)
{
    var cell_height = get_cell_height(calendar_height_percent);
    var thead = calendar.querySelector('thead');
    var tbody = calendar.querySelector('tbody');   
    tbody.style.height = 10 * cell_height + 'px';
    var th = calendar.querySelectorAll('th');
    Array.prototype.forEach.call(th, function (element) {
        element.style.height = cell_height + 'px';
    });
    var td = calendar.querySelectorAll('td');
    Array.prototype.forEach.call(td, function (element) {
        element.style.height = cell_height + 'px';
    });
    thead.style.width = tbody.clientWidth + 'px';
}

function jump_to_8am(calendar, calendar_height_percent)
{
    var cell_height = get_cell_height(calendar_height_percent);
    var tbody = calendar.querySelector('tbody');
    tbody.scrollTo(0, (cell_height || 0) * 8);
}

function init_thead(calendar, week_days, thead)
{
    var tr = document.createElement('tr');
    for (var i = 0; i < 8; ++i) {
        var th = document.createElement('th');
        if (i > 0) {
            th.innerHTML = 
            `
            <div>${week_days[i]['short_name']}</div>
            <div>${week_days[i]['local_date'].substring(0,5)}</div>
            `;
        }        
        tr.appendChild(th);
    }
    thead.appendChild(tr);
}

function init_tbody(calendar, week_days, tbody)
{
    for (var i = 0; i < 24; ++i) {
        var tr = document.createElement('tr');
        tr.setAttribute('data-hour_offset', i);
        for (var j = 0; j < 8; ++j) {
            var td = document.createElement('td');
            if (j == 0) {
                var hour = (i < 10) ? ('0' + i) : i;
                td.innerHTML = hour + ':00'; 
            } else {
                td.className = 'calendar_clickable';
                td.setAttribute('data-timestamp', week_days[j]['timestamp']);
                td.setAttribute('data-hour_offset', i);
            }
            tr.appendChild(td);
        }
        tbody.appendChild(tr);
    }
}

function init_calendar(calendar, week_days)
{
    var thead = document.createElement('thead');
    thead.className = 'table-bordered';
    var tbody = document.createElement('tbody');
    init_thead(calendar, week_days, thead);
    init_tbody(calendar, week_days, tbody);
    calendar.innerHTML = '';
    calendar.className = 'table-responsive';
    calendar.appendChild(thead);
    calendar.appendChild(tbody);
}

function get_week_days(offset = 0)
{
    var ONEMIN = 1000 * 60;
    var ONEHOUR = ONEMIN * 60;
    var ONEDAY = ONEHOUR * 24;
    var ONEWEEK = ONEDAY * 7;
    var date = new Date();
    date = new Date(date.getFullYear(), date.getMonth(), date.getDate() + (offset * 7));
    var timestamp = date.getTime();
    var monday = false;
    if (date.getDay() == 0) {
        monday = timestamp - 6 * ONEDAY;
    } else {
        week_day_offset = date.getDay();
        monday = timestamp - ((week_day_offset - 1) * ONEDAY);
    }
    var tuesday = monday + ONEDAY;
    var wednesday = tuesday + ONEDAY;
    var thursday = wednesday + ONEDAY;
    var friday = thursday + ONEDAY;
    var saturday = friday + ONEDAY;
    var sunday = saturday + ONEDAY;
    return {
        '1': {
            'name': 'Pazartesi',
            'short_name': 'Pzt.',
            'timestamp': monday,
            'local_date': new Date(monday).toLocaleString("tr-TR").substring(0,10),
        },
        '2': {
            'name': 'Salı',
            'short_name': 'Sal.',
            'timestamp': tuesday,
            'local_date': new Date(tuesday).toLocaleString("tr-TR").substring(0,10),
        },
        '3': {
            'name': 'Çarşamba',
            'short_name': 'Çar.',
            'timestamp': wednesday,
            'local_date': new Date(wednesday).toLocaleString("tr-TR").substring(0,10),
        },
        '4': {
            'name': 'Perşembe',
            'short_name': 'Per.',
            'timestamp': thursday,
            'local_date': new Date(thursday).toLocaleString("tr-TR").substring(0,10),
        },
        '5': {
            'name': 'Cuma',
            'short_name': 'Cu.',
            'timestamp': friday,
            'local_date': new Date(friday).toLocaleString("tr-TR").substring(0,10),
        },
        '6': {
            'name': 'Cumartesi',
            'short_name': 'Cts.',
            'timestamp': saturday,
            'local_date': new Date(saturday).toLocaleString("tr-TR").substring(0,10),
        },
        '7': {
            'name': 'Pazar',
            'short_name': 'Pz.',
            'timestamp': sunday,
            'local_date': new Date(sunday).toLocaleString("tr-TR").substring(0,10),
        },
    }
}

function get_unix_timestamp(timestamp)
{
    return parseInt((timestamp / 1000).toFixed(0));
}

function get_iso_date_time(timestamp) {
    var date = new Date(timestamp);
    return date.toISOString();
}

function get_iso_date(timestamp) {
    return get_iso_date_time(timestamp).substring(0,10);
}

function date_from_timestamp(timestamp) {
    return new Date(timestamp);
}