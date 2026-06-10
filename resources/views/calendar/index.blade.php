@extends('layouts.admin2')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.css">

<style>
/* =========================
   FULLCALENDAR SIM MARKETING
========================= */

#calendar{
    background:#fff;
    border-radius:20px;
    padding:20px;
}

/* HEADER */
.fc-toolbar{
    margin-bottom:20px !important;
}

.fc-toolbar-title{
    font-size:28px !important;
    font-weight:700 !important;
    color:#1572E8;
}

/* BUTTON */
.fc-button{
    background:#1572E8 !important;
    border:none !important;
    box-shadow:none !important;
    border-radius:8px !important;
    padding:8px 15px !important;
}

.fc-button:hover{
    background:#1262c6 !important;
}

.fc-button-active{
    background:#0d53aa !important;
}

/* GRID */
.fc-scrollgrid{
    border-radius:15px;
    overflow:hidden;
    border:1px solid #e5e7eb !important;
}

.fc-col-header-cell{
    background:#1572E8;
}

.fc-col-header-cell-cushion{
    color:#fff !important;
    font-weight:600;
    text-decoration:none !important;
    padding:10px 0;
}

/* TANGGAL */
.fc-daygrid-day-number{
    color:#1572E8 !important;
    font-weight:600;
    text-decoration:none !important;
}

.fc-daygrid-day{
    background:#fff;
    transition:0.3s;
}

.fc-daygrid-day:hover{
    background:#f5f9ff;
}

/* HARI INI */
.fc-day-today{
    background:#EAF4FF !important;
}

/* EVENT */
.fc-event{
    border:none !important;
    border-radius:10px !important;
    padding:4px 8px !important;
    font-size:13px;
    font-weight:600;
    color:#fff !important;
    box-shadow:0 2px 6px rgba(0,0,0,.15);
}

/* JUDUL EVENT */
.fc-event-title{
    color:#fff !important;
    font-weight:600;
}

/* DOT EVENT */
.fc-daygrid-event-dot{
    border-color:#fff !important;
}

/* WEEKEND */
.fc-day-sat{
    background:#fafcff;
}

.fc-day-sun{
    background:#fff5f5;
}

/* SCROLL */
.fc-scroller{
    overflow:auto !important;
}

/* SHADOW */
.fc-theme-standard td,
.fc-theme-standard th{
    border-color:#e5e7eb !important;
}

.fc-event{
    background:#1572E8 !important;
    color:#fff !important;
    border:none !important;
    border-radius:8px !important;
    padding:4px 8px !important;
    min-height:24px;
}

.fc-event-main{
    color:#fff !important;
}

.fc-event-title{
    color:#fff !important;
    font-weight:600 !important;
    display:block !important;
}

.fc-daygrid-event{
    white-space:normal !important;
}

.fc-h-event{
    background:#1572E8 !important;
    border:0 !important;
}

/* RESPONSIVE */
@media(max-width:768px){

    .fc-toolbar{
        flex-direction:column;
        gap:10px;
    }

    .fc-toolbar-title{
        font-size:20px !important;
    }

}
</style>

<div class="container-fluid">

    <div class="card shadow-lg calendar-card">

        <div class="calendar-header">
            <h3>📅 Marketing Calendar</h3>
            <p>Kelola jadwal follow up, meeting, presentasi dan target marketing</p>
        </div>

        <div class="card-body">
            <div id="calendar"></div>
        </div>

    </div>

</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {

        locale: 'id',

        initialView: 'dayGridMonth',

        height: 800,

        selectable: true,

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            week: 'Minggu',
            day: 'Hari'
        },

        events: '/calendar/events',

        select: function(info){

            let title = prompt('Masukkan Judul Jadwal');

            if(!title) return;

            fetch('/calendar/store', {

                method:'POST',

                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },

                body: JSON.stringify({
                    title:title,
                    type:'followup',
                    start_date:info.startStr,
                    end_date:info.endStr
                })

            })
            .then(response => response.json())
            .then(data => {
                calendar.refetchEvents();
            });

        },

        eventClick:function(info){

            if(confirm('Hapus jadwal ini?')){

                fetch('/calendar/delete/' + info.event.id, {

                    method:'DELETE',

                    headers:{
                        'X-CSRF-TOKEN':'{{ csrf_token() }}'
                    }

                })
                .then(response => response.json())
                .then(data => {
                    calendar.refetchEvents();
                });

            }

        }

    });

    calendar.render();

});
</script>

@endsection
