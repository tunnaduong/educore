<x-layouts.dash-student active="schedules">
    @include('components.language')
    <div class="row">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-calendar3 me-2"></i>Lịch học của bạn
                    </h4>
                    <p class="text-muted mb-0">Xem thời khóa biểu các buổi học, kiểm tra, bài tập</p>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div><span
                        style="display:inline-block;width:18px;height:18px;background:#0d6efd;border-radius:4px;margin-right:6px;"></span>
                    <b>Lịch học</b>
                </div>
                <div><span
                        style="display:inline-block;width:18px;height:18px;background:#fd7e14;border-radius:4px;margin-right:6px;"></span>
                    <b>Bài tập</b>
                </div>
                <div><span
                        style="display:inline-block;width:18px;height:18px;background:#20c997;border-radius:4px;margin-right:6px;"></span>
                    <b>Kiểm tra</b>
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
                    initialView: 'listWeek',
                    locale: 'vi',
                    buttonText: {
                        today: 'Hôm nay',
                        month: 'Tháng',
                        week: 'Tuần',
                        day: 'Ngày',
                        list: 'Danh sách'
                    },
                    titleFormat: {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    },
                    allDayText: 'Cả ngày',
                    noEventsText: 'Không có sự kiện để hiển thị',
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
                            html = '<p class="text-center">Không có sự kiện nào trong ngày này.</p>';
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
                        <h5 class="modal-title" id="modalScheduleLabel">Lịch chi tiết trong ngày</h5>
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
