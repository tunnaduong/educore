<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-lightbulb text-warning"></i>
                            @lang('general.help_center')
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Hướng dẫn sử dụng -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-book"></i>
                                            @lang('general.user_guide')
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="helpAccordion">
                                            <!-- Quản lý học sinh -->
                                            <div class="card">
                                                <div class="card-header" id="headingStudents">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseStudents">
                                                            <i class="fas fa-users"></i> Quản lý học sinh
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseStudents" class="collapse" data-parent="#helpAccordion">
                                                    <div class="card-body">
                                                        <ul>
                                                            <li><strong>Thêm học sinh mới:</strong> Vào menu "Học sinh" → "Thêm mới"</li>
                                                            <li><strong>Chỉnh sửa thông tin:</strong> Click vào tên học sinh trong danh sách</li>
                                                            <li><strong>Xem lịch sử điểm danh:</strong> Vào trang chi tiết học sinh → tab "Điểm danh"</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Quản lý lớp học -->
                                            <div class="card">
                                                <div class="card-header" id="headingClassrooms">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseClassrooms">
                                                            <i class="fas fa-graduation-cap"></i> Quản lý lớp học
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseClassrooms" class="collapse" data-parent="#helpAccordion">
                                                    <div class="card-body">
                                                        <ul>
                                                            <li><strong>Tạo lớp mới:</strong> Vào menu "Lớp học" → "Thêm mới"</li>
                                                            <li><strong>Phân công học sinh:</strong> Vào chi tiết lớp → "Phân công học sinh"</li>
                                                            <li><strong>Quản lý giáo viên:</strong> Chỉ định giáo viên chủ nhiệm khi tạo lớp</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Quản lý lịch học -->
                                            <div class="card">
                                                <div class="card-header" id="headingSchedules">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseSchedules">
                                                            <i class="fas fa-calendar-alt"></i> Quản lý lịch học
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseSchedules" class="collapse" data-parent="#helpAccordion">
                                                    <div class="card-body">
                                                        <ul>
                                                            <li><strong>Tạo lịch học:</strong> Vào menu "Lịch học" → "Thêm mới"</li>
                                                            <li><strong>Kiểm tra xung đột:</strong> Hệ thống tự động cảnh báo xung đột lịch</li>
                                                            <li><strong>Xuất lịch:</strong> Có thể xuất lịch ra file PDF hoặc Excel</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Quản lý bài tập -->
                                            <div class="card">
                                                <div class="card-header" id="headingAssignments">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseAssignments">
                                                            <i class="fas fa-tasks"></i> Quản lý bài tập
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseAssignments" class="collapse" data-parent="#helpAccordion">
                                                    <div class="card-body">
                                                        <ul>
                                                            <li><strong>Tạo bài tập:</strong> Vào menu "Bài tập" → "Thêm mới"</li>
                                                            <li><strong>Chấm điểm:</strong> Vào menu "Chấm bài" để xem và chấm bài nộp</li>
                                                            <li><strong>Theo dõi tiến độ:</strong> Xem báo cáo nộp bài của học sinh</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Quản lý bài kiểm tra -->
                                            <div class="card">
                                                <div class="card-header" id="headingQuizzes">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseQuizzes">
                                                            <i class="fas fa-question-circle"></i> Quản lý bài kiểm tra
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseQuizzes" class="collapse" data-parent="#helpAccordion">
                                                    <div class="card-body">
                                                        <ul>
                                                            <li><strong>Tạo bài kiểm tra:</strong> Vào menu "Bài kiểm tra" → "Thêm mới"</li>
                                                            <li><strong>Thêm câu hỏi:</strong> Hỗ trợ nhiều loại câu hỏi: trắc nghiệm, tự luận</li>
                                                            <li><strong>Thiết lập thời gian:</strong> Đặt thời gian làm bài và thời gian mở/đóng</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Báo cáo và thống kê -->
                                            <div class="card">
                                                <div class="card-header" id="headingReports">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseReports">
                                                            <i class="fas fa-chart-bar"></i> Báo cáo và thống kê
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseReports" class="collapse" data-parent="#helpAccordion">
                                                    <div class="card-body">
                                                        <ul>
                                                            <li><strong>Báo cáo lớp:</strong> Xem thống kê điểm danh, điểm số theo lớp</li>
                                                            <li><strong>Báo cáo học sinh:</strong> Theo dõi tiến độ học tập của từng học sinh</li>
                                                            <li><strong>Xuất báo cáo:</strong> Tải báo cáo dưới dạng PDF hoặc Excel</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin liên hệ -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-headset"></i>
                                            @lang('general.contact_support')
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-4">
                                            <i class="fas fa-users-cog fa-3x text-primary"></i>
                                            <h6 class="mt-2">@lang('general.support_team')</h6>
                                        </div>

                                        <div class="contact-info">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fas fa-envelope text-primary mr-3"></i>
                                                <div>
                                                    <strong>Email:</strong><br>
                                                    <a href="mailto:support@educore.com">support@educore.com</a>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fas fa-phone text-primary mr-3"></i>
                                                <div>
                                                    <strong>Điện thoại:</strong><br>
                                                    <a href="tel:+84123456789">+84 123 456 789</a>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fab fa-facebook text-primary mr-3"></i>
                                                <div>
                                                    <strong>Facebook:</strong><br>
                                                    <a href="https://facebook.com/educore" target="_blank">EduCore Support</a>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fab fa-telegram text-primary mr-3"></i>
                                                <div>
                                                    <strong>Telegram:</strong><br>
                                                    <a href="https://t.me/educore_support" target="_blank">@educore_support</a>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fas fa-clock text-primary mr-3"></i>
                                                <div>
                                                    <strong>@lang('general.working_hours'):</strong><br>
                                                    Thứ 2 - Thứ 6: 8:00 - 17:00<br>
                                                    Thứ 7: 8:00 - 12:00
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="text-center">
                                            <h6 class="text-muted">@lang('general.quick_response')</h6>
                                            <p class="text-muted small">
                                                @lang('general.response_time')
                                            </p>
                                            <button class="btn btn-primary btn-sm" onclick="window.open('mailto:support@educore.com?subject=Hỗ trợ EduCore')">
                                                <i class="fas fa-paper-plane"></i>
                                                @lang('general.send_email_now')
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ nhanh -->
                                <div class="card mt-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-question"></i>
                                            @lang('general.faq')
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="faq-item mb-2">
                                            <strong>Q: Làm sao để reset mật khẩu?</strong><br>
                                            <small class="text-muted">A: Liên hệ admin hoặc sử dụng chức năng "Quên mật khẩu"</small>
                                        </div>
                                        <div class="faq-item mb-2">
                                            <strong>Q: Có thể xuất dữ liệu ra Excel không?</strong><br>
                                            <small class="text-muted">A: Có, hầu hết các trang báo cáo đều có nút xuất Excel</small>
                                        </div>
                                        <div class="faq-item mb-2">
                                            <strong>Q: Làm sao để backup dữ liệu?</strong><br>
                                            <small class="text-muted">A: Hệ thống tự động backup hàng ngày, liên hệ admin để khôi phục</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
