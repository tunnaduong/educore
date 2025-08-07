<x-layouts.dash-teacher active="schedules">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-calendar3 mr-2"></i>Lịch giảng dạy
                    </h4>
                    <p class="text-muted mb-0">Xem thời khóa biểu các buổi giảng dạy, bài tập, kiểm tra</p>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="mb-3">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div><span
                        style="display:inline-block;width:18px;height:18px;background:#0d6efd;border-radius:4px;margin-right:6px;"></span>
                    <b>Lịch giảng dạy</b>
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
</x-layouts.dash-teacher>

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
                            var typeIcon = '';
                            var typeClass = '';

                            if (ev.extendedProps && ev.extendedProps.type === 'schedule') {
                                typeIcon = '<i class="bi bi-calendar3 mr-2"></i>';
                                typeClass = 'text-primary';
                            } else if (ev.extendedProps && ev.extendedProps.type === 'lesson') {
                                typeIcon = '<i class="bi bi-book mr-2"></i>';
                                typeClass = 'text-primary';
                            } else if (ev.extendedProps && ev.extendedProps.type ===
                                'assignment') {
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
                                html += '<small class="text-muted">Lớp: ' + ev.extendedProps
                                    .classroom + '</small><br>';
                            }

                            if (ev.start && ev.end && !ev.allDay) {
                                var start = ev.start.substring(11, 16);
                                var end = ev.end.substring(11, 16);
                                html += '<small class="text-muted">Thời gian: ' + start +
                                    ' - ' + end + '</small>';
                            } else if (ev.allDay) {
                                html += '<small class="text-muted">Cả ngày</small>';
                            }

                            if (ev.extendedProps && ev.extendedProps.location) {
                                html += '<br><small class="text-muted">Địa điểm: ' + ev
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
    </script>

    <!-- Modal HTML cho chi tiết ngày -->
    <div class="modal fade" id="modalSchedule" tabindex="-1" aria-labelledby="modalScheduleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalScheduleLabel">Lịch giảng dạy trong ngày</h5>
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
                    <h5 class="modal-title" id="modalEventLabel">Chi tiết sự kiện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalEventBody">
                    <!-- Nội dung sẽ được render bằng JS -->
                </div>
            </div>
        </div>
    </div>
@endpush
