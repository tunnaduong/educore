<x-layouts.dash-admin active="schedules">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-calendar3 mr-2"></i>{{ __('views.teaching_schedule_overview') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('views.view_all_schedules_assignments_quizzes') }}</p>
                </div>
                <a href="{{ route('schedules.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left mr-2"></i>{{ __('views.back_to_list') }}
                </a>
            </div>
        </div>

        <!-- Legend -->
        <div class="mb-3">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div><span
                        style="display:inline-block;width:18px;height:18px;background:#0d6efd;border-radius:4px;margin-right:6px;"></span>
                    <b>{{ __('views.schedule') }}</b>
                </div>
                <div><span
                        style="display:inline-block;width:18px;height:18px;background:#fd7e14;border-radius:4px;margin-right:6px;"></span>
                    <b>{{ __('views.assignment') }}</b>
                </div>
                <div><span
                        style="display:inline-block;width:18px;height:18px;background:#20c997;border-radius:4px;margin-right:6px;"></span>
                    <b>{{ __('views.quiz') }}</b>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
    <style>
        .fc th,
        .fc a,
        .fc-daygrid-day-number,
        .fc-col-header-cell-cushion {
            text-decoration: none !important;
        }
    </style>
    <!-- Modal CSS -->
    <style>
        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1050 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales-all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var events = @json($events);
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'listWeek',
                locale: 'vi',
                buttonText: {
                    today: '{{ __('views.today') }}',
                    month: '{{ __('views.month') }}',
                    week: '{{ __('views.week') }}',
                    day: '{{ __('views.day') }}',
                    list: '{{ __('views.list') }}'
                },
                titleFormat: {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                },
                allDayText: '{{ __('views.all_day') }}',
                noEventsText: '{{ __('views.no_events_to_display') }}',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: events,
                height: 650,
                dateClick: function(info) {
                    // Lọc các event của ngày được click
                    var clickedDate = info.dateStr;
                    var dayEvents = events.filter(function(ev) {
                        return ev.start.startsWith(clickedDate);
                    });
                    var html = '';
                    if (dayEvents.length === 0) {
                        html = '<p class="text-center">{{ __('views.no_events_on_this_day') }}</p>';
                    } else {
                        html = '<ul class="list-group">';
                        dayEvents.forEach(function(ev) {
                            var typeIcon = '';
                            var typeClass = '';

                            if (ev.extendedProps && ev.extendedProps.type === 'schedule') {
                                typeIcon = '<i class="bi bi-calendar3 mr-2"></i>';
                                typeClass = 'text-primary';
                            } else if (ev.extendedProps && ev.extendedProps.type === 'assignment') {
                                typeIcon = '<i class="bi bi-journal-text mr-2"></i>';
                                typeClass = 'text-warning';
                            } else if (ev.extendedProps && ev.extendedProps.type === 'quiz') {
                                typeIcon = '<i class="bi bi-patch-question mr-2"></i>';
                                typeClass = 'text-success';
                            }

                            html += '<li class="list-group-item">' +
                                '<div class="d-flex align-items-start">' +
                                '<span style="display:inline-block;width:12px;height:12px;background:' +
                                ev.backgroundColor +
                                ';border-radius:50%;margin-right:8px;margin-top:4px;"></span>' +
                                '<div class="flex-grow-1">' +
                                '<div class="' + typeClass + '">' + typeIcon + '<b>' + ev
                                .title + '</b></div>';

                            if (ev.extendedProps && ev.extendedProps.classroom) {
                                html += '<small class="text-muted">{{ __('views.class_label') }}: ' + ev.extendedProps
                                    .classroom + '</small><br>';
                            }

                            if (ev.start && ev.end) {
                                var start = ev.start.substring(11, 16);
                                var end = ev.end.substring(11, 16);
                                html += '<small class="text-muted">{{ __('views.time_label') }}: ' + start +
                                    ' - ' + end + '</small>';
                            } else if (ev.start) {
                                var start = ev.start.substring(11, 16);
                                html += '<small class="text-muted">{{ __('views.time_label') }}: ' + start + '</small>';
                            }

                            if (ev.extendedProps && ev.extendedProps.location) {
                                html += '<br><small class="text-muted">{{ __('views.location_label') }}: ' + ev
                                    .extendedProps.location + '</small>';
                            }

                            html += '</div></div></li>';
                        });
                        html += '</ul>';
                    }
                    document.getElementById('modalScheduleBody').innerHTML = html;
                    var modal = new bootstrap.Modal(document.getElementById('modalSchedule'));
                    modal.show();
                },
                eventClick: function(info) {
                    var event = info.event;
                    var eventId = event.id.split('_')[1];
                    var eventType = event.extendedProps.type;

                    // Gọi Livewire component để hiển thị chi tiết
                    @this.call('showEventDetail', eventId, eventType);
                }
            });
            calendar.render();
        });

        // Lắng nghe event từ Livewire để hiển thị modal chi tiết
        Livewire.on('showEventModal', (data) => {
            var html = '<div class="event-detail">';
            html += '<h6 class="mb-3">' + data.title + '</h6>';

            if (data.classroom) {
                html += '<p><strong>{{ __('views.class_label') }}:</strong> ' + data.classroom + '</p>';
            }

            if (data.description) {
                html += '<p><strong>{{ __('views.description_label') }}:</strong> ' + data.description + '</p>';
            }

            if (data.location) {
                html += '<p><strong>{{ __('views.location_label') }}:</strong> ' + data.location + '</p>';
            }

            if (data.teachers) {
                html += '<p><strong>{{ __('views.teachers_label') }}:</strong> ' + data.teachers + '</p>';
            }

            if (data.studentCount) {
                html += '<p><strong>{{ __('views.student_count_label') }}:</strong> ' + data.studentCount + '</p>';
            }

            if (data.points) {
                html += '<p><strong>{{ __('views.points_label') }}:</strong> ' + data.points + '</p>';
            }

            if (data.duration) {
                html += '<p><strong>{{ __('views.duration_label') }}:</strong> ' + data.duration + ' {{ __('views.minutes') }}</p>';
            }

            html += '</div>';

            document.getElementById('modalEventBody').innerHTML = html;
            var modal = new bootstrap.Modal(document.getElementById('modalEvent'));
            modal.show();
        });
    </script>

    <!-- Modal HTML cho chi tiết ngày -->
    <div class="modal fade" id="modalSchedule" tabindex="-1" aria-labelledby="modalScheduleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalScheduleLabel">{{ __('views.teaching_schedule_today') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalScheduleBody">
                    <!-- Nội dung sẽ được render bằng JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML cho chi tiết sự kiện -->
    <div class="modal fade" id="modalEvent" tabindex="-1" aria-labelledby="modalEventLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEventLabel">{{ __('views.event_details') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalEventBody">
                    <!-- Nội dung sẽ được render bằng JS -->
                </div>
            </div>
        </div>
    </div>
@endpush
