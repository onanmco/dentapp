var calendarEl = document.getElementById('calendar');
var calendar = new FullCalendar.Calendar(calendarEl, {
    locale: 'tr',
    timeZone: 'local',
    views: { // view-specific options applied here
        dayGridMonth: {
            dayHeaderFormat: {
                weekday: 'short',
                // month: '2-digit',
                // day: '2-digit',
                omitCommas: true
            },
          }
    },
    initialView: 'timeGridWeek',
    headerToolbar: {
        start: 'prev,next,today',
        center: 'title',
        end: 'dayGridMonth,timeGridWeek'
    },
    themeSystem: 'bootstrap',
    bootstrapFontAwesome: {
        prev: 'fa-angle-left',
        next: 'fa-angle-right'
    },
    height: "70vh",
    titleFormat: {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    },
    buttonText: {
        today: 'Bugün',
        month: 'Aylık',
        week: 'Haftalık',
        day: 'gün'
    },
    slotEventOverlap: false,
    allDaySlot: false,
    scrollTime: "08:00:00",
    firstDay: 1,
    dayHeaderFormat: {
        weekday: 'short',
        month: '2-digit',
        day: '2-digit',
        omitCommas: true
    },
    slotDuration: '00:15:00',
    slotLabelInterval: '01:00',
    slotLabelFormat: {
        hour: '2-digit',
        minute: '2-digit',
        omitZeroMinute: false,
        meridiem: false,
        hour12: false
    },
    selectable: true,
    selectMirror: true,
    eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        omitZeroMinute: false,
        meridiem: false,
        hour12: false
    },
    events: [],
    select: function (info) {
        var day_names = ['pazar', 'pazartesi', 'salı', 'çarşamba', 'perşembe', 'cuma', 'cumartesi'];
        var html = '<strong>Seçilen Tarih: </strong> ' + info.start.toLocaleString().substring(0, 10) + ' ' + uc_first(day_names[info.start.getDay()]);
        $('#current_date').html(html);
        var start_date = info['start'];
        var end_date = info['end'];
        end_date.setDate(start_date.getDate());
        $('#modal').attr('data-start', start_date.getTime());
        $('#modal').attr('data-end', end_date.getTime());
        var start_hour = (info.start.getHours() < 10) ? ('0' + info.start.getHours()) : info.start.getHours();
        var start_min = (info.start.getMinutes() < 10) ? ('0' + info.start.getMinutes()) : info.start.getMinutes();
        var end_hour = (info.end.getHours() < 10) ? ('0' + info.end.getHours()) : info.end.getHours();
        var end_min = (info.end.getMinutes() < 10) ? ('0' + info.end.getMinutes()) : info.end.getMinutes();
        $('#start_hour').val(start_hour + ':' + start_min);
        $('#end_hour').val(end_hour + ':' + end_min);
        $('#modal').modal('show');
    },
    datesSet: function (info) {
        var fetch_appointments = async function () {
            var list = await fetch('/api/appointment/get-all-by-range', {
                method: "POST",
                body: JSON.stringify({
                    start: js_timestamp_to_unix_timestamp(calendar.view.activeStart.getTime()),
                    end: js_timestamp_to_unix_timestamp(calendar.view.activeEnd.getTime()),
                })
            });
    
            console.log(list);
        
            list = await list.json();
            var status = list.status || false;
            if (status === 'success') {
                calendar.render();
                calendar.updateSize();
                calendar.removeAllEvents();
                list.data.appointment_list.forEach(function (appointment) {
                    calendar.addEvent(appointment);
                });    
            }
        }
        fetch_appointments();
    }
});