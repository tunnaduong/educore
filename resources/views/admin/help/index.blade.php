<x-layouts.dash-admin active="help">
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
                                                        <h6 class="text-primary mb-3">📋 Tổng quan quản lý học sinh</h6>
                                                        <p>Module quản lý học sinh cho phép bạn thêm, chỉnh sửa, xem thông tin chi tiết và theo dõi tiến độ học tập của từng học sinh trong hệ thống.</p>
                                                        
                                                        <div class="alert alert-info">
                                                            <strong>💡 Lưu ý:</strong> Tất cả thông tin học sinh được mã hóa và bảo mật theo quy định GDPR.
                                                        </div>

                                                        <h6 class="text-success mt-4">➕ Thêm học sinh mới</h6>
                                                        <ol>
                                                            <li>Đăng nhập vào hệ thống với quyền Admin</li>
                                                            <li>Vào menu <strong>"Học sinh"</strong> trên sidebar bên trái</li>
                                                            <li>Click nút <strong>"Thêm mới"</strong> (màu xanh) ở góc trên bên phải</li>
                                                            <li>Điền đầy đủ thông tin bắt buộc:
                                                                <ul>
                                                                    <li><strong>Họ và tên:</strong> Tên đầy đủ của học sinh</li>
                                                                    <li><strong>Email:</strong> Email chính thức (dùng để đăng nhập)</li>
                                                                    <li><strong>Số điện thoại:</strong> Số liên lạc khẩn cấp</li>
                                                                    <li><strong>Ngày sinh:</strong> Định dạng DD/MM/YYYY</li>
                                                                    <li><strong>Địa chỉ:</strong> Địa chỉ hiện tại</li>
                                                                </ul>
                                                            </li>
                                                            <li>Click <strong>"Lưu"</strong> để hoàn tất</li>
                                                        </ol>
                                                        
                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Form thêm học sinh mới]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-warning mt-4">✏️ Chỉnh sửa thông tin học sinh</h6>
                                                        <ol>
                                                            <li>Từ danh sách học sinh, click vào <strong>tên học sinh</strong> cần chỉnh sửa</li>
                                                            <li>Trang chi tiết sẽ hiển thị với các tab: Thông tin, Điểm danh, Bài tập, Điểm số</li>
                                                            <li>Click nút <strong>"Chỉnh sửa"</strong> (biểu tượng bút chì)</li>
                                                            <li>Cập nhật thông tin cần thiết</li>
                                                            <li>Click <strong>"Cập nhật"</strong> để lưu thay đổi</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Trang chi tiết học sinh với các tab]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-info mt-4">📊 Xem lịch sử điểm danh</h6>
                                                        <ol>
                                                            <li>Vào trang chi tiết học sinh</li>
                                                            <li>Click tab <strong>"Điểm danh"</strong></li>
                                                            <li>Xem thống kê:
                                                                <ul>
                                                                    <li>Tổng số buổi học</li>
                                                                    <li>Số buổi có mặt</li>
                                                                    <li>Số buổi vắng</li>
                                                                    <li>Tỷ lệ đi học (%)</li>
                                                                </ul>
                                                            </li>
                                                            <li>Có thể lọc theo tháng/năm để xem chi tiết</li>
                                                        </ol>

                                                        <h6 class="text-danger mt-4">🗑️ Xóa học sinh</h6>
                                                        <div class="alert alert-warning">
                                                            <strong>⚠️ Cảnh báo:</strong> Hành động này không thể hoàn tác. Dữ liệu học sinh sẽ được lưu trữ trong 30 ngày trước khi xóa vĩnh viễn.
                                                        </div>
                                                        <ol>
                                                            <li>Chỉ có thể xóa học sinh không còn tham gia lớp học nào</li>
                                                            <li>Click nút <strong>"Xóa"</strong> (màu đỏ) trong trang chi tiết</li>
                                                            <li>Xác nhận hành động trong popup</li>
                                                        </ol>

                                                        <h6 class="text-secondary mt-4">🔍 Tìm kiếm và lọc</h6>
                                                        <ul>
                                                            <li><strong>Tìm theo tên:</strong> Nhập tên học sinh vào ô tìm kiếm</li>
                                                            <li><strong>Lọc theo lớp:</strong> Chọn lớp cụ thể từ dropdown</li>
                                                            <li><strong>Lọc theo trạng thái:</strong> Đang học, Tạm nghỉ, Đã tốt nghiệp</li>
                                                            <li><strong>Sắp xếp:</strong> Theo tên, ngày tham gia, điểm trung bình</li>
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
                                                        <h6 class="text-primary mb-3">🏫 Tổng quan quản lý lớp học</h6>
                                                        <p>Module quản lý lớp học giúp bạn tạo và quản lý các lớp học, phân công giáo viên, học sinh và theo dõi tiến độ học tập của từng lớp.</p>

                                                        <div class="alert alert-info">
                                                            <strong>💡 Lưu ý:</strong> Mỗi lớp học có thể có tối đa 30 học sinh và 1 giáo viên chủ nhiệm.
                                                        </div>

                                                        <h6 class="text-success mt-4">➕ Tạo lớp học mới</h6>
                                                        <ol>
                                                            <li>Vào menu <strong>"Lớp học"</strong> trên sidebar</li>
                                                            <li>Click nút <strong>"Thêm mới"</strong> (màu xanh)</li>
                                                            <li>Điền thông tin lớp học:
                                                                <ul>
                                                                    <li><strong>Tên lớp:</strong> Ví dụ: "Lớp 10A1 - Toán nâng cao"</li>
                                                                    <li><strong>Mô tả:</strong> Thông tin chi tiết về lớp học</li>
                                                                    <li><strong>Giáo viên chủ nhiệm:</strong> Chọn từ danh sách giáo viên</li>
                                                                    <li><strong>Số lượng học sinh tối đa:</strong> Mặc định 30</li>
                                                                    <li><strong>Ngày bắt đầu:</strong> Ngày khai giảng lớp</li>
                                                                    <li><strong>Ngày kết thúc:</strong> Dự kiến kết thúc khóa học</li>
                                                                </ul>
                                                            </li>
                                                            <li>Click <strong>"Tạo lớp"</strong> để hoàn tất</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Form tạo lớp học mới]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-warning mt-4">👥 Phân công học sinh vào lớp</h6>
                                                        <ol>
                                                            <li>Vào trang chi tiết lớp học</li>
                                                            <li>Click tab <strong>"Học sinh"</strong> hoặc nút <strong>"Phân công học sinh"</strong></li>
                                                            <li>Chọn học sinh từ danh sách bên trái</li>
                                                            <li>Click nút <strong>"Thêm vào lớp"</strong> (mũi tên sang phải)</li>
                                                            <li>Xác nhận thông tin học sinh đã được thêm</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Giao diện phân công học sinh]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-info mt-4">📊 Quản lý thông tin lớp</h6>
                                                        <ul>
                                                            <li><strong>Thông tin cơ bản:</strong> Tên lớp, mô tả, giáo viên chủ nhiệm</li>
                                                            <li><strong>Danh sách học sinh:</strong> Xem tất cả học sinh trong lớp</li>
                                                            <li><strong>Lịch học:</strong> Xem lịch học của lớp</li>
                                                            <li><strong>Bài tập:</strong> Quản lý bài tập được giao cho lớp</li>
                                                            <li><strong>Điểm danh:</strong> Theo dõi điểm danh của lớp</li>
                                                            <li><strong>Báo cáo:</strong> Xem báo cáo tổng hợp của lớp</li>
                                                        </ul>

                                                        <h6 class="text-danger mt-4">⚙️ Cài đặt lớp học</h6>
                                                        <ol>
                                                            <li>Trong trang chi tiết lớp, click nút <strong>"Cài đặt"</strong></li>
                                                            <li>Có thể thay đổi:
                                                                <ul>
                                                                    <li>Giáo viên chủ nhiệm</li>
                                                                    <li>Số lượng học sinh tối đa</li>
                                                                    <li>Trạng thái lớp (Đang hoạt động/Tạm dừng)</li>
                                                                    <li>Thông báo tự động</li>
                                                                </ul>
                                                            </li>
                                                        </ol>

                                                        <h6 class="text-secondary mt-4">📈 Theo dõi tiến độ lớp</h6>
                                                        <ul>
                                                            <li><strong>Tỷ lệ đi học:</strong> Thống kê điểm danh của cả lớp</li>
                                                            <li><strong>Điểm trung bình:</strong> Điểm số trung bình của lớp</li>
                                                            <li><strong>Bài tập hoàn thành:</strong> Số bài tập đã nộp/tổng số</li>
                                                            <li><strong>Biểu đồ tiến độ:</strong> Theo dõi sự tiến bộ theo thời gian</li>
                                                        </ul>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Báo cáo tiến độ lớp học]</p>
                                                            </div>
                                                        </div>
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
                                                        <h6 class="text-primary mb-3">📅 Tổng quan quản lý lịch học</h6>
                                                        <p>Module lịch học giúp bạn tạo và quản lý lịch học cho từng lớp, tránh xung đột lịch và đảm bảo hiệu quả trong việc sắp xếp thời gian học tập.</p>

                                                        <div class="alert alert-info">
                                                            <strong>💡 Lưu ý:</strong> Hệ thống tự động kiểm tra xung đột lịch và gửi thông báo khi có vấn đề.
                                                        </div>

                                                        <h6 class="text-success mt-4">➕ Tạo lịch học mới</h6>
                                                        <ol>
                                                            <li>Vào menu <strong>"Lịch học"</strong> trên sidebar</li>
                                                            <li>Click nút <strong>"Thêm mới"</strong> (màu xanh)</li>
                                                            <li>Chọn lớp học cần tạo lịch</li>
                                                            <li>Điền thông tin lịch học:
                                                                <ul>
                                                                    <li><strong>Môn học:</strong> Chọn môn học từ danh sách</li>
                                                                    <li><strong>Giáo viên:</strong> Giáo viên phụ trách môn học</li>
                                                                    <li><strong>Thời gian:</strong> Ngày và giờ học</li>
                                                                    <li><strong>Phòng học:</strong> Chọn phòng học phù hợp</li>
                                                                    <li><strong>Thời lượng:</strong> Số phút của buổi học</li>
                                                                    <li><strong>Lặp lại:</strong> Hàng tuần, hàng tháng hoặc một lần</li>
                                                                </ul>
                                                            </li>
                                                            <li>Click <strong>"Tạo lịch"</strong> để hoàn tất</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Form tạo lịch học mới]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-warning mt-4">⚠️ Kiểm tra xung đột lịch</h6>
                                                        <p>Hệ thống tự động kiểm tra các loại xung đột sau:</p>
                                                        <ul>
                                                            <li><strong>Xung đột giáo viên:</strong> Giáo viên đã có lịch dạy khác</li>
                                                            <li><strong>Xung đột phòng học:</strong> Phòng học đã được sử dụng</li>
                                                            <li><strong>Xung đột lớp học:</strong> Lớp đã có lịch học khác</li>
                                                            <li><strong>Xung đột thời gian:</strong> Thời gian trùng lặp</li>
                                                        </ul>

                                                        <div class="alert alert-warning">
                                                            <strong>🔔 Thông báo:</strong> Khi phát hiện xung đột, hệ thống sẽ hiển thị cảnh báo màu đỏ và đề xuất thời gian thay thế.
                                                        </div>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Cảnh báo xung đột lịch học]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-info mt-4">📊 Xem và quản lý lịch học</h6>
                                                        <ol>
                                                            <li>Trong trang lịch học, bạn có thể:
                                                                <ul>
                                                                    <li><strong>Xem lịch theo tuần:</strong> Lịch học trong tuần hiện tại</li>
                                                                    <li><strong>Xem lịch theo tháng:</strong> Tổng quan lịch học trong tháng</li>
                                                                    <li><strong>Lọc theo lớp:</strong> Chỉ xem lịch của lớp cụ thể</li>
                                                                    <li><strong>Lọc theo giáo viên:</strong> Xem lịch dạy của giáo viên</li>
                                                                </ul>
                                                            </li>
                                                            <li>Click vào buổi học để xem chi tiết hoặc chỉnh sửa</li>
                                                        </ol>

                                                        <h6 class="text-danger mt-4">✏️ Chỉnh sửa lịch học</h6>
                                                        <ol>
                                                            <li>Click vào buổi học cần chỉnh sửa</li>
                                                            <li>Chọn <strong>"Chỉnh sửa"</strong> từ menu</li>
                                                            <li>Thay đổi thông tin cần thiết</li>
                                                            <li>Click <strong>"Cập nhật"</strong> để lưu thay đổi</li>
                                                        </ol>

                                                        <h6 class="text-secondary mt-4">📤 Xuất lịch học</h6>
                                                        <ol>
                                                            <li>Trong trang lịch học, click nút <strong>"Xuất lịch"</strong></li>
                                                            <li>Chọn định dạng xuất:
                                                                <ul>
                                                                    <li><strong>PDF:</strong> Để in hoặc chia sẻ</li>
                                                                    <li><strong>Excel:</strong> Để chỉnh sửa thêm</li>
                                                                    <li><strong>Calendar (.ics):</strong> Để import vào Google Calendar</li>
                                                                </ul>
                                                            </li>
                                                            <li>Chọn phạm vi thời gian cần xuất</li>
                                                            <li>Click <strong>"Tải xuống"</strong></li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Giao diện xuất lịch học]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-dark mt-4">🔔 Thông báo lịch học</h6>
                                                        <ul>
                                                            <li><strong>Thông báo cho học sinh:</strong> Tự động gửi email/SMS khi có lịch học mới</li>
                                                            <li><strong>Nhắc nhở buổi học:</strong> Gửi thông báo trước 30 phút</li>
                                                            <li><strong>Thông báo thay đổi:</strong> Khi lịch học bị thay đổi</li>
                                                            <li><strong>Báo cáo vắng mặt:</strong> Thông báo cho phụ huynh khi học sinh vắng</li>
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
                                                        <h6 class="text-primary mb-3">📝 Tổng quan quản lý bài tập</h6>
                                                        <p>Module bài tập cho phép bạn tạo, giao bài tập cho học sinh, theo dõi tiến độ nộp bài và chấm điểm một cách hiệu quả.</p>

                                                        <div class="alert alert-info">
                                                            <strong>💡 Lưu ý:</strong> Hệ thống hỗ trợ nhiều loại bài tập: văn bản, file đính kèm, hình ảnh, âm thanh và video.
                                                        </div>

                                                        <h6 class="text-success mt-4">➕ Tạo bài tập mới</h6>
                                                        <ol>
                                                            <li>Vào menu <strong>"Bài tập"</strong> trên sidebar</li>
                                                            <li>Click nút <strong>"Thêm mới"</strong> (màu xanh)</li>
                                                            <li>Điền thông tin bài tập:
                                                                <ul>
                                                                    <li><strong>Tiêu đề:</strong> Tên bài tập rõ ràng</li>
                                                                    <li><strong>Mô tả:</strong> Hướng dẫn chi tiết cho học sinh</li>
                                                                    <li><strong>Lớp học:</strong> Chọn lớp cần giao bài</li>
                                                                    <li><strong>Môn học:</strong> Môn học liên quan</li>
                                                                    <li><strong>Hạn nộp:</strong> Thời gian cuối cùng nộp bài</li>
                                                                    <li><strong>Điểm tối đa:</strong> Số điểm có thể đạt được</li>
                                                                </ul>
                                                            </li>
                                                            <li>Chọn loại bài tập:
                                                                <ul>
                                                                    <li><strong>Văn bản:</strong> Học sinh nhập text trực tiếp</li>
                                                                    <li><strong>File đính kèm:</strong> Upload file Word, PDF, Excel</li>
                                                                    <li><strong>Hình ảnh:</strong> Upload ảnh bài làm</li>
                                                                    <li><strong>Âm thanh:</strong> Ghi âm bài nói</li>
                                                                    <li><strong>Video:</strong> Quay video bài thuyết trình</li>
                                                                </ul>
                                                            </li>
                                                            <li>Upload file đề bài (nếu có)</li>
                                                            <li>Click <strong>"Tạo bài tập"</strong> để hoàn tất</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Form tạo bài tập mới]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-warning mt-4">📋 Quản lý danh sách bài tập</h6>
                                                        <ol>
                                                            <li>Trong trang danh sách bài tập, bạn có thể:
                                                                <ul>
                                                                    <li><strong>Xem tất cả bài tập:</strong> Đã tạo, đang diễn ra, đã kết thúc</li>
                                                                    <li><strong>Lọc theo lớp:</strong> Chỉ xem bài tập của lớp cụ thể</li>
                                                                    <li><strong>Lọc theo môn học:</strong> Bài tập theo từng môn</li>
                                                                    <li><strong>Tìm kiếm:</strong> Theo tên bài tập hoặc mô tả</li>
                                                                </ul>
                                                            </li>
                                                            <li>Click vào bài tập để xem chi tiết và danh sách bài nộp</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Danh sách bài tập với bộ lọc]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-info mt-4">✅ Chấm điểm bài tập</h6>
                                                        <ol>
                                                            <li>Vào menu <strong>"Chấm bài"</strong> trên sidebar</li>
                                                            <li>Chọn bài tập cần chấm</li>
                                                            <li>Xem danh sách bài nộp:
                                                                <ul>
                                                                    <li><strong>Đã nộp:</strong> Học sinh đã nộp bài</li>
                                                                    <li><strong>Chưa nộp:</strong> Học sinh chưa nộp bài</li>
                                                                    <li><strong>Đã chấm:</strong> Bài đã được chấm điểm</li>
                                                                    <li><strong>Chưa chấm:</strong> Bài chưa được chấm</li>
                                                                </ul>
                                                            </li>
                                                            <li>Click <strong>"Xem"</strong> để xem nội dung bài nộp</li>
                                                            <li>Nhập điểm và nhận xét</li>
                                                            <li>Click <strong>"Lưu"</strong> để hoàn tất chấm điểm</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Giao diện chấm điểm bài tập]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-danger mt-4">📊 Theo dõi tiến độ nộp bài</h6>
                                                        <ul>
                                                            <li><strong>Thống kê tổng quan:</strong> Số bài đã nộp/tổng số học sinh</li>
                                                            <li><strong>Biểu đồ tiến độ:</strong> Theo dõi số bài nộp theo thời gian</li>
                                                            <li><strong>Danh sách chưa nộp:</strong> Học sinh chưa nộp bài</li>
                                                            <li><strong>Thông báo nhắc nhở:</strong> Tự động gửi email nhắc nhở</li>
                                                        </ul>

                                                        <h6 class="text-secondary mt-4">📈 Báo cáo bài tập</h6>
                                                        <ol>
                                                            <li>Trong trang chi tiết bài tập, click <strong>"Báo cáo"</strong></li>
                                                            <li>Xem các thống kê:
                                                                <ul>
                                                                    <li>Tỷ lệ nộp bài (%)</li>
                                                                    <li>Điểm trung bình của lớp</li>
                                                                    <li>Điểm cao nhất/thấp nhất</li>
                                                                    <li>Phân bố điểm theo mức độ</li>
                                                                </ul>
                                                            </li>
                                                            <li>Xuất báo cáo ra PDF hoặc Excel</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Báo cáo thống kê bài tập]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-dark mt-4">🔔 Thông báo và nhắc nhở</h6>
                                                        <ul>
                                                            <li><strong>Thông báo giao bài:</strong> Tự động thông báo cho học sinh khi có bài tập mới</li>
                                                            <li><strong>Nhắc nhở hạn nộp:</strong> Gửi email nhắc nhở trước 1 ngày</li>
                                                            <li><strong>Thông báo chấm điểm:</strong> Thông báo cho học sinh khi bài được chấm</li>
                                                            <li><strong>Báo cáo cho phụ huynh:</strong> Gửi báo cáo định kỳ về tình hình bài tập</li>
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
                                                        <h6 class="text-primary mb-3">📋 Tổng quan quản lý bài kiểm tra</h6>
                                                        <p>Module bài kiểm tra cho phép bạn tạo các bài kiểm tra trực tuyến với nhiều loại câu hỏi khác nhau, thiết lập thời gian và tự động chấm điểm.</p>

                                                        <div class="alert alert-info">
                                                            <strong>💡 Lưu ý:</strong> Hệ thống hỗ trợ câu hỏi trắc nghiệm, tự luận, đúng/sai và có thể tự động chấm điểm cho câu hỏi trắc nghiệm.
                                                        </div>

                                                        <h6 class="text-success mt-4">➕ Tạo bài kiểm tra mới</h6>
                                                        <ol>
                                                            <li>Vào menu <strong>"Bài kiểm tra"</strong> trên sidebar</li>
                                                            <li>Click nút <strong>"Thêm mới"</strong> (màu xanh)</li>
                                                            <li>Điền thông tin cơ bản:
                                                                <ul>
                                                                    <li><strong>Tiêu đề:</strong> Tên bài kiểm tra</li>
                                                                    <li><strong>Mô tả:</strong> Hướng dẫn cho học sinh</li>
                                                                    <li><strong>Lớp học:</strong> Chọn lớp cần làm bài</li>
                                                                    <li><strong>Môn học:</strong> Môn học liên quan</li>
                                                                    <li><strong>Thời gian làm bài:</strong> Số phút cho phép</li>
                                                                    <li><strong>Điểm tối đa:</strong> Tổng điểm có thể đạt được</li>
                                                                </ul>
                                                            </li>
                                                            <li>Thiết lập thời gian:
                                                                <ul>
                                                                    <li><strong>Thời gian mở:</strong> Khi nào bài kiểm tra bắt đầu</li>
                                                                    <li><strong>Thời gian đóng:</strong> Khi nào bài kiểm tra kết thúc</li>
                                                                    <li><strong>Thời gian làm bài:</strong> Số phút tối đa cho mỗi học sinh</li>
                                                                </ul>
                                                            </li>
                                                            <li>Click <strong>"Tạo bài kiểm tra"</strong> để tiếp tục</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Form tạo bài kiểm tra mới]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-warning mt-4">❓ Thêm câu hỏi vào bài kiểm tra</h6>
                                                        <ol>
                                                            <li>Sau khi tạo bài kiểm tra, click <strong>"Thêm câu hỏi"</strong></li>
                                                            <li>Chọn loại câu hỏi:
                                                                <ul>
                                                                    <li><strong>Trắc nghiệm:</strong> Chọn 1 đáp án đúng từ nhiều lựa chọn</li>
                                                                    <li><strong>Trắc nghiệm nhiều đáp án:</strong> Chọn nhiều đáp án đúng</li>
                                                                    <li><strong>Đúng/Sai:</strong> Câu hỏi True/False</li>
                                                                    <li><strong>Tự luận:</strong> Học sinh viết câu trả lời</li>
                                                                    <li><strong>Điền từ:</strong> Điền từ vào chỗ trống</li>
                                                                </ul>
                                                            </li>
                                                            <li>Nhập nội dung câu hỏi</li>
                                                            <li>Thêm đáp án (nếu là câu hỏi trắc nghiệm)</li>
                                                            <li>Đánh dấu đáp án đúng</li>
                                                            <li>Nhập điểm cho câu hỏi</li>
                                                            <li>Click <strong>"Lưu câu hỏi"</strong></li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Giao diện thêm câu hỏi]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-info mt-4">⚙️ Cài đặt bài kiểm tra</h6>
                                                        <ul>
                                                            <li><strong>Hiển thị câu hỏi:</strong> Ngẫu nhiên hoặc theo thứ tự</li>
                                                            <li><strong>Hiển thị đáp án:</strong> Ngay sau khi làm xong hoặc sau khi hết thời gian</li>
                                                            <li><strong>Cho phép làm lại:</strong> Học sinh có thể làm lại bài kiểm tra</li>
                                                            <li><strong>Giới hạn thời gian:</strong> Tự động nộp bài khi hết thời gian</li>
                                                            <li><strong>Chống gian lận:</strong> Không cho phép mở tab khác</li>
                                                        </ul>

                                                        <h6 class="text-danger mt-4">📊 Xem kết quả bài kiểm tra</h6>
                                                        <ol>
                                                            <li>Vào trang chi tiết bài kiểm tra</li>
                                                            <li>Click tab <strong>"Kết quả"</strong></li>
                                                            <li>Xem thống kê:
                                                                <ul>
                                                                    <li>Số học sinh đã làm bài</li>
                                                                    <li>Điểm trung bình của lớp</li>
                                                                    <li>Điểm cao nhất/thấp nhất</li>
                                                                    <li>Thời gian làm bài trung bình</li>
                                                                </ul>
                                                            </li>
                                                            <li>Click vào tên học sinh để xem chi tiết bài làm</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Báo cáo kết quả bài kiểm tra]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-secondary mt-4">✅ Chấm điểm câu hỏi tự luận</h6>
                                                        <ol>
                                                            <li>Trong trang kết quả, tìm câu hỏi tự luận</li>
                                                            <li>Click <strong>"Chấm điểm"</strong> bên cạnh câu trả lời</li>
                                                            <li>Đọc câu trả lời của học sinh</li>
                                                            <li>Nhập điểm và nhận xét</li>
                                                            <li>Click <strong>"Lưu điểm"</strong></li>
                                                        </ol>

                                                        <h6 class="text-dark mt-4">📈 Phân tích câu hỏi</h6>
                                                        <ul>
                                                            <li><strong>Tỷ lệ đúng:</strong> Bao nhiêu % học sinh trả lời đúng</li>
                                                            <li><strong>Phân tích đáp án:</strong> Học sinh thường chọn đáp án nào</li>
                                                            <li><strong>Độ khó câu hỏi:</strong> Dựa trên tỷ lệ đúng</li>
                                                            <li><strong>Độ phân biệt:</strong> Câu hỏi có phân biệt được học sinh giỏi/yếu</li>
                                                        </ul>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Phân tích chi tiết từng câu hỏi]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-primary mt-4">🔔 Thông báo và nhắc nhở</h6>
                                                        <ul>
                                                            <li><strong>Thông báo bài kiểm tra mới:</strong> Tự động thông báo cho học sinh</li>
                                                            <li><strong>Nhắc nhở thời gian:</strong> Thông báo trước 30 phút khi bài kiểm tra sắp bắt đầu</li>
                                                            <li><strong>Thông báo kết quả:</strong> Gửi email kết quả cho học sinh</li>
                                                            <li><strong>Báo cáo cho phụ huynh:</strong> Gửi báo cáo điểm số định kỳ</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Quản lý bài học -->
                                            <div class="card">
                                                <div class="card-header" id="headingLessons">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseLessons">
                                                            <i class="fas fa-book-open"></i> Quản lý bài học & Tài nguyên
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseLessons" class="collapse" data-parent="#helpAccordion">
                                                    <div class="card-body">
                                                        <h6 class="text-primary mb-3">📚 Tổng quan quản lý bài học</h6>
                                                        <p>Module bài học cho phép bạn tạo và quản lý các bài học, tài liệu học tập, video bài giảng và tài nguyên giáo dục cho học sinh.</p>

                                                        <div class="alert alert-info">
                                                            <strong>💡 Lưu ý:</strong> Hệ thống hỗ trợ nhiều định dạng file: PDF, Word, PowerPoint, video, audio và hình ảnh.
                                                        </div>

                                                        <h6 class="text-success mt-4">➕ Tạo bài học mới</h6>
                                                        <ol>
                                                            <li>Vào menu <strong>"Bài học"</strong> trên sidebar</li>
                                                            <li>Click nút <strong>"Thêm mới"</strong> (màu xanh)</li>
                                                            <li>Điền thông tin bài học:
                                                                <ul>
                                                                    <li><strong>Tiêu đề:</strong> Tên bài học</li>
                                                                    <li><strong>Mô tả:</strong> Nội dung tóm tắt bài học</li>
                                                                    <li><strong>Môn học:</strong> Chọn môn học liên quan</li>
                                                                    <li><strong>Lớp học:</strong> Chọn lớp cần học bài này</li>
                                                                    <li><strong>Thời lượng:</strong> Số phút dự kiến học</li>
                                                                    <li><strong>Độ khó:</strong> Dễ, Trung bình, Khó</li>
                                                                </ul>
                                                            </li>
                                                            <li>Upload tài liệu bài học</li>
                                                            <li>Click <strong>"Tạo bài học"</strong> để hoàn tất</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Form tạo bài học mới]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-warning mt-4">📁 Quản lý tài nguyên</h6>
                                                        <ul>
                                                            <li><strong>Tài liệu PDF:</strong> Sách giáo khoa, bài tập</li>
                                                            <li><strong>Video bài giảng:</strong> Ghi hình buổi học</li>
                                                            <li><strong>Audio:</strong> Bài giảng âm thanh</li>
                                                            <li><strong>Hình ảnh:</strong> Minh họa, biểu đồ</li>
                                                            <li><strong>Link YouTube:</strong> Video bổ sung</li>
                                                            <li><strong>File đính kèm:</strong> Bài tập, tài liệu tham khảo</li>
                                                        </ul>

                                                        <h6 class="text-info mt-4">📖 Tổ chức bài học</h6>
                                                        <ol>
                                                            <li>Trong trang chi tiết bài học, click <strong>"Thêm nội dung"</strong></li>
                                                            <li>Sắp xếp thứ tự các phần:
                                                                <ul>
                                                                    <li><strong>Giới thiệu:</strong> Mục tiêu bài học</li>
                                                                    <li><strong>Nội dung chính:</strong> Kiến thức cần học</li>
                                                                    <li><strong>Ví dụ:</strong> Minh họa thực tế</li>
                                                                    <li><strong>Bài tập:</strong> Luyện tập</li>
                                                                    <li><strong>Tổng kết:</strong> Ôn tập kiến thức</li>
                                                                </ul>
                                                            </li>
                                                            <li>Upload file cho từng phần</li>
                                                            <li>Click <strong>"Lưu"</strong> để hoàn tất</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Giao diện tổ chức nội dung bài học]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-danger mt-4">👥 Phân quyền bài học</h6>
                                                        <ul>
                                                            <li><strong>Chỉ định lớp:</strong> Chỉ lớp được chọn mới xem được</li>
                                                            <li><strong>Thời gian mở:</strong> Khi nào bài học được mở</li>
                                                            <li><strong>Thời gian đóng:</strong> Khi nào bài học bị khóa</li>
                                                            <li><strong>Yêu cầu hoàn thành:</strong> Học sinh phải học xong bài trước</li>
                                                        </ul>

                                                        <h6 class="text-secondary mt-4">📊 Theo dõi tiến độ học</h6>
                                                        <ul>
                                                            <li><strong>Số học sinh đã học:</strong> Bao nhiêu % đã hoàn thành</li>
                                                            <li><strong>Thời gian học:</strong> Thời gian trung bình học bài</li>
                                                            <li><strong>Điểm đánh giá:</strong> Điểm học sinh đánh giá bài học</li>
                                                            <li><strong>Phản hồi:</strong> Ý kiến của học sinh về bài học</li>
                                                        </ul>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Thống kê tiến độ học bài]</p>
                                                            </div>
                                                        </div>
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
                                                        <h6 class="text-primary mb-3">📊 Tổng quan báo cáo và thống kê</h6>
                                                        <p>Module báo cáo cung cấp các thống kê chi tiết về tình hình học tập, điểm danh, điểm số và tiến độ của học sinh, lớp học để giúp bạn đưa ra quyết định quản lý hiệu quả.</p>

                                                        <div class="alert alert-info">
                                                            <strong>💡 Lưu ý:</strong> Tất cả báo cáo có thể được xuất ra PDF hoặc Excel để chia sẻ với phụ huynh hoặc lưu trữ.
                                                        </div>

                                                        <h6 class="text-success mt-4">📈 Báo cáo tổng quan</h6>
                                                        <ol>
                                                            <li>Vào menu <strong>"Báo cáo"</strong> trên sidebar</li>
                                                            <li>Trang tổng quan hiển thị:
                                                                <ul>
                                                                    <li><strong>Tổng số học sinh:</strong> Đang học, tạm nghỉ, đã tốt nghiệp</li>
                                                                    <li><strong>Tổng số lớp học:</strong> Đang hoạt động, đã kết thúc</li>
                                                                    <li><strong>Tỷ lệ đi học trung bình:</strong> Toàn trường</li>
                                                                    <li><strong>Điểm trung bình:</strong> Theo từng môn học</li>
                                                                    <li><strong>Biểu đồ tiến độ:</strong> Theo thời gian</li>
                                                                </ul>
                                                            </li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Dashboard báo cáo tổng quan]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-warning mt-4">🏫 Báo cáo lớp học</h6>
                                                        <ol>
                                                            <li>Click <strong>"Báo cáo lớp"</strong> trong menu báo cáo</li>
                                                            <li>Chọn lớp cần xem báo cáo</li>
                                                            <li>Xem các thống kê:
                                                                <ul>
                                                                    <li><strong>Thông tin lớp:</strong> Tên lớp, giáo viên, số học sinh</li>
                                                                    <li><strong>Điểm danh:</strong> Tỷ lệ đi học theo tháng/tuần</li>
                                                                    <li><strong>Điểm số:</strong> Điểm trung bình các môn học</li>
                                                                    <li><strong>Bài tập:</strong> Tỷ lệ nộp bài và điểm trung bình</li>
                                                                    <li><strong>Bài kiểm tra:</strong> Kết quả các bài kiểm tra</li>
                                                                </ul>
                                                            </li>
                                                            <li>Click <strong>"Xuất báo cáo"</strong> để tải về</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Báo cáo chi tiết lớp học]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-info mt-4">👤 Báo cáo học sinh</h6>
                                                        <ol>
                                                            <li>Click <strong>"Báo cáo học sinh"</strong> trong menu báo cáo</li>
                                                            <li>Chọn học sinh cần xem báo cáo</li>
                                                            <li>Xem thông tin chi tiết:
                                                                <ul>
                                                                    <li><strong>Thông tin cá nhân:</strong> Họ tên, lớp, giáo viên chủ nhiệm</li>
                                                                    <li><strong>Lịch sử điểm danh:</strong> Chi tiết từng buổi học</li>
                                                                    <li><strong>Điểm số:</strong> Điểm các môn học theo thời gian</li>
                                                                    <li><strong>Bài tập:</strong> Danh sách bài tập đã nộp và điểm</li>
                                                                    <li><strong>Bài kiểm tra:</strong> Kết quả các bài kiểm tra</li>
                                                                    <li><strong>Biểu đồ tiến độ:</strong> Sự tiến bộ theo thời gian</li>
                                                                </ul>
                                                            </li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Báo cáo chi tiết học sinh]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-danger mt-4">📅 Báo cáo điểm danh</h6>
                                                        <ol>
                                                            <li>Click <strong>"Báo cáo điểm danh"</strong></li>
                                                            <li>Chọn thời gian cần xem báo cáo</li>
                                                            <li>Xem thống kê:
                                                                <ul>
                                                                    <li><strong>Tỷ lệ đi học:</strong> Theo lớp, theo học sinh</li>
                                                                    <li><strong>Học sinh vắng nhiều:</strong> Danh sách cần quan tâm</li>
                                                                    <li><strong>Lý do vắng:</strong> Thống kê các lý do vắng mặt</li>
                                                                    <li><strong>Xu hướng:</strong> Biểu đồ tỷ lệ đi học theo thời gian</li>
                                                                </ul>
                                                            </li>
                                                        </ol>

                                                        <h6 class="text-secondary mt-4">📊 Báo cáo điểm số</h6>
                                                        <ul>
                                                            <li><strong>Điểm trung bình:</strong> Theo môn học, theo lớp</li>
                                                            <li><strong>Phân bố điểm:</strong> Bao nhiêu học sinh đạt điểm giỏi, khá, trung bình</li>
                                                            <li><strong>So sánh:</strong> Điểm số giữa các lớp, các môn</li>
                                                            <li><strong>Tiến bộ:</strong> Sự tiến bộ của học sinh theo thời gian</li>
                                                            <li><strong>Học sinh yếu:</strong> Danh sách cần hỗ trợ thêm</li>
                                                        </ul>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Biểu đồ phân bố điểm số]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-dark mt-4">📤 Xuất báo cáo</h6>
                                                        <ol>
                                                            <li>Trong bất kỳ trang báo cáo nào, click nút <strong>"Xuất báo cáo"</strong></li>
                                                            <li>Chọn định dạng:
                                                                <ul>
                                                                    <li><strong>PDF:</strong> Để in hoặc chia sẻ</li>
                                                                    <li><strong>Excel:</strong> Để chỉnh sửa thêm</li>
                                                                </ul>
                                                            </li>
                                                            <li>Chọn phạm vi dữ liệu cần xuất</li>
                                                            <li>Click <strong>"Tải xuống"</strong></li>
                                                        </ol>

                                                        <h6 class="text-primary mt-4">🔔 Báo cáo tự động</h6>
                                                        <ul>
                                                            <li><strong>Báo cáo tuần:</strong> Tự động gửi cho phụ huynh mỗi tuần</li>
                                                            <li><strong>Báo cáo tháng:</strong> Tổng hợp tình hình học tập hàng tháng</li>
                                                            <li><strong>Báo cáo học kỳ:</strong> Kết quả học tập theo học kỳ</li>
                                                            <li><strong>Cảnh báo:</strong> Thông báo khi học sinh có vấn đề</li>
                                                        </ul>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Cài đặt báo cáo tự động]</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Quản lý tài chính -->
                                            <div class="card">
                                                <div class="card-header" id="headingFinance">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFinance">
                                                            <i class="fas fa-coins"></i> Quản lý tài chính & Thu chi
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseFinance" class="collapse" data-parent="#helpAccordion">
                                                    <div class="card-body">
                                                        <h6 class="text-primary mb-3">💰 Tổng quan quản lý tài chính</h6>
                                                        <p>Module tài chính giúp bạn theo dõi thu chi, quản lý học phí, chi phí vận hành và tạo báo cáo tài chính chi tiết cho trung tâm.</p>

                                                        <div class="alert alert-info">
                                                            <strong>💡 Lưu ý:</strong> Tất cả giao dịch tài chính được ghi lại và có thể xuất báo cáo theo thời gian.
                                                        </div>

                                                        <h6 class="text-success mt-4">📊 Dashboard tài chính</h6>
                                                        <ol>
                                                            <li>Vào menu <strong>"Thống kê thu chi"</strong> trên sidebar</li>
                                                            <li>Xem tổng quan:
                                                                <ul>
                                                                    <li><strong>Tổng thu:</strong> Học phí đã thu trong tháng</li>
                                                                    <li><strong>Tổng chi:</strong> Chi phí vận hành</li>
                                                                    <li><strong>Lợi nhuận:</strong> Thu - Chi</li>
                                                                    <li><strong>Học phí chưa thu:</strong> Nợ học phí</li>
                                                                    <li><strong>Biểu đồ thu chi:</strong> Theo thời gian</li>
                                                                </ul>
                                                            </li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Dashboard tài chính tổng quan]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-warning mt-4">💳 Quản lý học phí</h6>
                                                        <ol>
                                                            <li>Click <strong>"Quản lý học phí"</strong> trong menu tài chính</li>
                                                            <li>Xem danh sách học sinh và học phí:
                                                                <ul>
                                                                    <li><strong>Học sinh:</strong> Tên, lớp, số tiền học phí</li>
                                                                    <li><strong>Hạn nộp:</strong> Ngày phải nộp học phí</li>
                                                                    <li><strong>Trạng thái:</strong> Đã nộp, Chưa nộp, Quá hạn</li>
                                                                    <li><strong>Số tiền:</strong> Học phí cần nộp</li>
                                                                </ul>
                                                            </li>
                                                            <li>Click vào học sinh để xem chi tiết thanh toán</li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Danh sách quản lý học phí]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-info mt-4">📝 Ghi nhận thanh toán</h6>
                                                        <ol>
                                                            <li>Trong trang chi tiết học sinh, click <strong>"Ghi nhận thanh toán"</strong></li>
                                                            <li>Nhập thông tin:
                                                                <ul>
                                                                    <li><strong>Số tiền:</strong> Số tiền đã thanh toán</li>
                                                                    <li><strong>Phương thức:</strong> Tiền mặt, Chuyển khoản, Thẻ</li>
                                                                    <li><strong>Ngày thanh toán:</strong> Ngày thực hiện giao dịch</li>
                                                                    <li><strong>Ghi chú:</strong> Thông tin bổ sung</li>
                                                                </ul>
                                                            </li>
                                                            <li>Upload ảnh chứng từ (nếu có)</li>
                                                            <li>Click <strong>"Lưu"</strong> để hoàn tất</li>
                                                        </ol>

                                                        <h6 class="text-danger mt-4">💸 Quản lý chi phí</h6>
                                                        <ol>
                                                            <li>Click <strong>"Quản lý chi phí"</strong> trong menu tài chính</li>
                                                            <li>Thêm chi phí mới:
                                                                <ul>
                                                                    <li><strong>Loại chi phí:</strong> Lương giáo viên, Điện nước, Văn phòng phẩm</li>
                                                                    <li><strong>Số tiền:</strong> Chi phí phát sinh</li>
                                                                    <li><strong>Ngày chi:</strong> Ngày thực hiện chi</li>
                                                                    <li><strong>Mô tả:</strong> Chi tiết khoản chi</li>
                                                                </ul>
                                                            </li>
                                                            <li>Upload hóa đơn, chứng từ</li>
                                                            <li>Click <strong>"Thêm chi phí"</strong></li>
                                                        </ol>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Form thêm chi phí mới]</p>
                                                            </div>
                                                        </div>

                                                        <h6 class="text-secondary mt-4">📈 Báo cáo tài chính</h6>
                                                        <ul>
                                                            <li><strong>Báo cáo thu chi:</strong> Theo ngày, tuần, tháng, năm</li>
                                                            <li><strong>Báo cáo học phí:</strong> Tình hình thu học phí</li>
                                                            <li><strong>Báo cáo chi phí:</strong> Phân tích chi phí theo loại</li>
                                                            <li><strong>Dự báo tài chính:</strong> Dự kiến thu chi trong tương lai</li>
                                                            <li><strong>So sánh:</strong> Thu chi giữa các thời kỳ</li>
                                                        </ul>

                                                        <h6 class="text-dark mt-4">🔔 Thông báo tài chính</h6>
                                                        <ul>
                                                            <li><strong>Nhắc nhở học phí:</strong> Thông báo cho phụ huynh khi sắp đến hạn</li>
                                                            <li><strong>Báo cáo định kỳ:</strong> Gửi báo cáo tài chính cho ban lãnh đạo</li>
                                                            <li><strong>Cảnh báo chi phí:</strong> Khi chi phí vượt ngân sách</li>
                                                            <li><strong>Thông báo thanh toán:</strong> Xác nhận khi nhận được thanh toán</li>
                                                        </ul>

                                                        <div class="text-center my-3">
                                                            <div class="bg-light p-3 rounded">
                                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">[Ảnh chụp màn hình: Báo cáo tài chính chi tiết]</p>
                                                            </div>
                                                        </div>
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
                                        <div class="faq-item mb-3">
                                            <strong>Q: Làm sao để reset mật khẩu?</strong><br>
                                            <small class="text-muted">A: Liên hệ admin hoặc sử dụng chức năng "Quên mật khẩu" trên trang đăng nhập</small>
                                        </div>
                                        <div class="faq-item mb-3">
                                            <strong>Q: Có thể xuất dữ liệu ra Excel không?</strong><br>
                                            <small class="text-muted">A: Có, hầu hết các trang báo cáo đều có nút xuất Excel ở góc trên bên phải</small>
                                        </div>
                                        <div class="faq-item mb-3">
                                            <strong>Q: Làm sao để backup dữ liệu?</strong><br>
                                            <small class="text-muted">A: Hệ thống tự động backup hàng ngày, liên hệ admin để khôi phục dữ liệu</small>
                                        </div>
                                        <div class="faq-item mb-3">
                                            <strong>Q: Có thể tạo nhiều lớp học cùng lúc không?</strong><br>
                                            <small class="text-muted">A: Hiện tại chỉ có thể tạo từng lớp một, nhưng có thể copy thông tin từ lớp cũ</small>
                                        </div>
                                        <div class="faq-item mb-3">
                                            <strong>Q: Làm sao để gửi thông báo cho tất cả học sinh?</strong><br>
                                            <small class="text-muted">A: Vào menu "Thông báo" → "Tạo mới" → Chọn "Tất cả học sinh" trong phần người nhận</small>
                                        </div>
                                        <div class="faq-item mb-3">
                                            <strong>Q: Có thể tạo bài kiểm tra với thời gian khác nhau cho từng học sinh không?</strong><br>
                                            <small class="text-muted">A: Có, trong cài đặt bài kiểm tra có thể thiết lập thời gian riêng cho từng học sinh</small>
                                        </div>
                                        <div class="faq-item mb-3">
                                            <strong>Q: Làm sao để xem lịch sử đăng nhập của học sinh?</strong><br>
                                            <small class="text-muted">A: Vào trang chi tiết học sinh → Tab "Hoạt động" → Xem lịch sử đăng nhập</small>
                                        </div>
                                        <div class="faq-item mb-3">
                                            <strong>Q: Có thể tạo báo cáo tùy chỉnh không?</strong><br>
                                            <small class="text-muted">A: Hiện tại chưa có tính năng này, nhưng có thể xuất dữ liệu ra Excel để tùy chỉnh thêm</small>
                                        </div>
                                        <div class="faq-item mb-3">
                                            <strong>Q: Làm sao để thay đổi ngôn ngữ hệ thống?</strong><br>
                                            <small class="text-muted">A: Click vào biểu tượng ngôn ngữ trên header → Chọn ngôn ngữ mong muốn</small>
                                        </div>
                                        <div class="faq-item mb-3">
                                            <strong>Q: Có thể tạo bài tập với deadline khác nhau cho từng học sinh không?</strong><br>
                                            <small class="text-muted">A: Có, trong cài đặt bài tập có thể thiết lập deadline riêng cho từng học sinh</small>
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
</x-layouts.dash-admin>
