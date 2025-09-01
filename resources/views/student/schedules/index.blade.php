<x-layouts.dash-student active="schedules">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-calendar3 mr-2"></i>{{ __('views.student_pages.schedules.index.title') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('views.student_pages.schedules.index.subtitle') }}</p>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div><span
                        style="display:inline-block;width:18px;height:18px;background:#0d6efd;border-radius:4px;margin-right:6px;"></span>
                    <b>{{ __('views.student_pages.schedules.index.schedule_legend') }}</b>
                </div>
                <div><span
                        style="display:inline-block;width:18px;height:18px;background:#fd7e14;border-radius:4px;margin-right:6px;"></span>
                    <b>{{ __('views.student_pages.schedules.index.assignments_legend') }}</b>
                </div>
                <div><span
                        style="display:inline-block;width:18px;height:18px;background:#20c997;border-radius:4px;margin-right:6px;"></span>
                    <b>{{ __('views.student_pages.schedules.index.quizzes_legend') }}</b>
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
                var events = @json($events ?? []);
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: '{{ app()->getLocale() }}',
                    firstDay: 1, // Bắt đầu tuần từ Thứ 2
                    fixedWeekCount: false, // Không cố định số tuần
                    showNonCurrentDates: false, // Không hiển thị ngày không thuộc tháng hiện tại
                    // Không dùng validRange để vẫn có thể điều hướng giữa các tháng
                    eventDidMount: function(info) {
                        // Lọc events chỉ hiển thị trong tháng hiện tại
                        var eventDate = new Date(info.event.start);
                        var currentDate = new Date();
                        if (eventDate.getMonth() !== currentDate.getMonth() || eventDate.getFullYear() !== currentDate.getFullYear()) {
                            info.el.style.display = 'none';
                        }
                    },
                    buttonText: {
                        today: '{{ __('views.student_pages.schedules.index.calendar_today') }}',
                        month: '{{ __('views.student_pages.schedules.index.calendar_month') }}',
                        week: '{{ __('views.student_pages.schedules.index.calendar_week') }}',
                        day: '{{ __('views.student_pages.schedules.index.calendar_day') }}',
                        list: '{{ __('views.student_pages.schedules.index.calendar_list') }}'
                    },
                    titleFormat: {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    },
                    allDayText: '{{ __('views.student_pages.schedules.index.calendar_all_day') }}',
                    noEventsText: '{{ __('views.student_pages.schedules.index.calendar_no_events') }}',
                    headerToolbar: {
                        left: 'prev,next',
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
                            html = '<p class="text-center">{{ __('views.student_pages.schedules.index.no_events_today') }}</p>';
                        } else {
                            html = '<ul class="list-group">';
                            dayEvents.forEach(function(ev) {
                                html += '<li class="list-group-item">' +
                                    '<span style="display:inline-block;width:12px;height:12px;background:' +
                                    ev.backgroundColor +
                                    ';border-radius:50%;margin-right:8px;"></span>' +
                                    '<b>' + ev.title + '</b>';
                                if (ev.start && ev.end) {
                                    var start = ev.start.substring(11, 16);
                                    var end = ev.end.substring(11, 16);
                                    html += ' <span class="text-muted">(' + start + ' - ' + end +
                                        ')</span>';
                                }
                                html += '</li>';
                            });
                            html += '</ul>';
                        }
                        document.getElementById('modalScheduleBody').innerHTML = html;
                        var modal = new bootstrap.Modal(document.getElementById('modalSchedule'));
                        modal.show();
                    }
                });
                calendar.render();
            });
        </script>
        <!-- Modal HTML -->
        <div class="modal fade" id="modalSchedule" tabindex="-1" aria-labelledby="modalScheduleLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalScheduleLabel">{{ __('views.student_pages.schedules.index.modal_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalScheduleBody">
                        <!-- Nội dung sẽ được render bằng JS -->
                    </div>
                </div>
            </div>
        </div>
    @endpush
</x-layouts.dash-student>
