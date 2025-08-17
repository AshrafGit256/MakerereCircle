@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Events</h1>
    <div id="calendar"></div>
</div>

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($events - > map(function($event) {
                return [
                    'title' => $event - > title,
                    'start' => $event - > start_time,
                    'end' => $event - > end_time,
                    'allDay' => false,
                ];
            })),
            editable: true,
            selectable: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
        });
        calendar.render();
    });
</script>
@endsection
@endsection