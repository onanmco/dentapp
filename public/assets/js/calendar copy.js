async function calendar_build()
{
    var calendar_title = document.getElementById('calendar_title');
    var calendar_table = document.getElementById('calendar_table');
    var calendar_nav = document.getElementById('calendar_nav');
    setCalendarTitleWidth();
    window.addEventListener('resize', function () {
        setCalendarTitleWidth();
    });
    var week_offset = 0;
    var week_days = get_week_days(week_offset);
    calendar_nav.querySelector('h6').innerHTML = 
    `
    ${week_days[1]['local_date']} - ${week_days[7]['local_date']}
    `;
    for (var i = 0; i < 8; ++i) {
        var cell = document.createElement('div');
        cell.className = 'calendar_item';
        if (i > 0) {
            cell.innerHTML = 
            `
            <span>${week_days[i]['short_name']}</span>
            <span>${week_days[i]['local_date'].substring(0,5)}</span>
            `;
        }
        calendar_title.querySelector('.row').appendChild(cell);
    }
    for (var i = 0; i < 24; ++i) {
        var row = document.createElement('div');
        row.className = 'row';
        row.setAttribute('data-hour_offset', i);
        var hour = (i >= 10) ? i : ('0' + i);
        var mins = ':00';
        var time = hour + mins;
        for (var j = 0; j < 8; ++j) {
            var cell = document.createElement('div');
            if (j == 0) {
                cell.innerHTML = time;
                cell.className = 'calendar_item';
            } else {
                cell.className = 'calendar_item calendar_cell';
                cell.setAttribute('data-timestamp', week_days[j]['timestamp']);
            }
            row.appendChild(cell);
        }
        calendar_table.appendChild(row);
    }

    var row8am = calendar_table.querySelector('.row:nth-of-type(9)');
    calendar_table.scroll(0, row8am.offsetTop);

}

calendar_build();

calendar_table.addEventListener('click', function (e) {
    e.preventDefault();
    if (e.target.classList.contains('calendar_cell')) {
        var randevu = document.createElement('div');
        randevu.className = 'randevu';
        randevu.classList.add('bg-danger');        
        e.target.appendChild(randevu);
    }
    console.log(e.target);
});

function setCalendarTitleWidth()
{
    var calendar_title = document.getElementById('calendar_title');
    var calendar_table = document.getElementById('calendar_table');
    var diff = calendar_table.offsetWidth - calendar_table.clientWidth;
    calendar_title.style.width = calendar_table.clientWidth + 'px';
    calendar_title.style.marginRight = diff + 'px';
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