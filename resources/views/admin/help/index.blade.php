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
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs" id="helpTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="guide-tab" data-toggle="tab" data-target="#guide" type="button" role="tab">
                                    <i class="fas fa-book"></i> Hướng dẫn sử dụng
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab">
                                    <i class="fas fa-headset"></i> Liên hệ với team Dev
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="helpTabsContent">
                            <!-- Tab 1: Hướng dẫn sử dụng -->
                            <div class="tab-pane fade show active" id="guide" role="tabpanel">
                                <div class="row mt-3">
                                    <!-- Thanh tìm kiếm -->
                                    <div class="col-12 mb-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="searchGuide" placeholder="Nhập từ khóa tìm kiếm hướng dẫn...">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="row">
                                            <!-- Danh mục bài hướng dẫn (bên trái) -->
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h6 class="card-title mb-0">
                                                            <i class="fas fa-list"></i> Danh mục
                                                        </h6>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="list-group list-group-flush" id="guideCategories">
                                                            <a href="#" class="list-group-item list-group-item-action active" data-category="account">
                                                                <i class="fas fa-user-cog"></i> Quản lý tài khoản
                                                            </a>
                                                            <a href="#" class="list-group-item list-group-item-action" data-category="students">
                                                                <i class="fas fa-users"></i> Quản lý học viên
                                                            </a>
                                                            <a href="#" class="list-group-item list-group-item-action" data-category="courses">
                                                                <i class="fas fa-graduation-cap"></i> Quản lý khóa học
                                                            </a>
                                                            <a href="#" class="list-group-item list-group-item-action" data-category="attendance">
                                                                <i class="fas fa-calendar-check"></i> Điểm danh & báo cáo
                                                            </a>
                                                            <a href="#" class="list-group-item list-group-item-action" data-category="system">
                                                                <i class="fas fa-cogs"></i> Cấu hình hệ thống
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Khu vực nội dung (bên phải) -->
                                            <div class="col-md-9">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <!-- Nội dung động sẽ được load bằng JavaScript -->
                                                        <div id="guideContent">
                                                            <div class="text-center py-5">
                                                                <i class="fas fa-book-open fa-3x text-muted"></i>
                                                                <h5 class="mt-3 text-muted">Chọn danh mục để xem hướng dẫn</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Liên hệ với team Dev -->
                            <div class="tab-pane fade" id="contact" role="tabpanel">
                                <div class="row mt-3">
                                    <!-- Form liên hệ nhanh -->
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-paper-plane"></i> Gửi yêu cầu trợ giúp
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <form id="contactForm" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="contactName">Họ tên <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="contactName" value="{{ auth()->user()->name }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="contactEmail">Email <span class="text-danger">*</span></label>
                                                                <input type="email" class="form-control" id="contactEmail" value="{{ auth()->user()->email }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="contactSubject">Tiêu đề vấn đề <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="contactSubject" placeholder="Nhập tiêu đề vấn đề..." required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="contactDescription">Mô tả vấn đề <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" id="contactDescription" rows="5" placeholder="Mô tả chi tiết vấn đề bạn gặp phải..." required></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="contactFile">Upload file ảnh/video lỗi (tối đa 10MB)</label>
                                                        <input type="file" class="form-control-file" id="contactFile" accept=".jpg,.jpeg,.png,.mp4,.avi,.mov">
                                                        <small class="form-text text-muted">Định dạng: JPG, PNG, MP4, AVI, MOV</small>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="contactPriority">Mức độ ưu tiên</label>
                                                        <select class="form-control" id="contactPriority">
                                                            <option value="low">Thấp</option>
                                                            <option value="medium" selected>Trung bình</option>
                                                            <option value="high">Cao</option>
                                                            <option value="urgent">Khẩn cấp</option>
                                                        </select>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-paper-plane"></i> Gửi yêu cầu
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Lịch sử hỗ trợ -->
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-history"></i> Lịch sử yêu cầu hỗ trợ
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Tiêu đề</th>
                                                                <th>Ngày gửi</th>
                                                                <th>Trạng thái</th>
                                                                <th>Thao tác</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="supportHistory">
                                                            <tr>
                                                                <td colspan="4" class="text-center text-muted">
                                                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                                                    <p>Chưa có yêu cầu hỗ trợ nào</p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Thông tin liên hệ trực tiếp -->
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header bg-success text-white">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-headset"></i> Thông tin liên hệ trực tiếp
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xem chi tiết yêu cầu hỗ trợ -->
    <div class="modal fade" id="supportDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết yêu cầu hỗ trợ</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="supportDetailContent">
                    <!-- Nội dung sẽ được load động -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dữ liệu hướng dẫn
        const guideData = {
            account: {
                title: "Quản lý tài khoản",
                content: `
                    <h4>Quản lý tài khoản</h4>
                    <p>Hướng dẫn chi tiết về cách quản lý tài khoản trong hệ thống EduCore.</p>
                    
                    <h5>1. Thay đổi thông tin cá nhân</h5>
                    <ol>
                        <li>Click vào tên người dùng ở góc trên bên phải</li>
                        <li>Chọn "Hồ sơ cá nhân"</li>
                        <li>Cập nhật thông tin cần thiết</li>
                        <li>Click "Lưu thay đổi"</li>
                    </ol>
                    
                    <h5>2. Đổi mật khẩu</h5>
                    <ol>
                        <li>Vào "Hồ sơ cá nhân"</li>
                        <li>Click tab "Bảo mật"</li>
                        <li>Nhập mật khẩu cũ và mật khẩu mới</li>
                        <li>Xác nhận mật khẩu mới</li>
                        <li>Click "Đổi mật khẩu"</li>
                    </ol>
                    
                    <div class="text-center my-3">
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="text-muted mt-2">[Ảnh chụp màn hình: Giao diện hồ sơ cá nhân]</p>
                        </div>
                    </div>
                    
                    <h5>Bài liên quan:</h5>
                    <ul>
                        <li><a href="#" data-category="system">Cấu hình hệ thống</a></li>
                        <li><a href="#" data-category="students">Quản lý học viên</a></li>
                    </ul>
                `
            },
            students: {
                title: "Quản lý học viên",
                content: `
                    <h4>Quản lý học viên</h4>
                    <p>Hướng dẫn chi tiết về cách quản lý thông tin học viên trong hệ thống.</p>
                    
                    <h5>1. Thêm học viên mới</h5>
                    <ol>
                        <li>Vào menu "Học viên" trên sidebar</li>
                        <li>Click nút "Thêm mới" (màu xanh)</li>
                        <li>Điền đầy đủ thông tin bắt buộc</li>
                        <li>Click "Lưu" để hoàn tất</li>
                    </ol>
                    
                    <h5>2. Chỉnh sửa thông tin học viên</h5>
                    <ol>
                        <li>Click vào tên học viên trong danh sách</li>
                        <li>Trang chi tiết sẽ hiển thị với các tab</li>
                        <li>Click nút "Chỉnh sửa"</li>
                        <li>Cập nhật thông tin cần thiết</li>
                        <li>Click "Cập nhật" để lưu thay đổi</li>
                    </ol>
                    
                    <div class="text-center my-3">
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="text-muted mt-2">[Ảnh chụp màn hình: Form thêm học viên mới]</p>
                        </div>
                    </div>
                    
                    <h5>3. Xem lịch sử điểm danh</h5>
                    <ol>
                        <li>Vào trang chi tiết học viên</li>
                        <li>Click tab "Điểm danh"</li>
                        <li>Xem thống kê và lịch sử điểm danh</li>
                    </ol>
                    
                    <h5>Bài liên quan:</h5>
                    <ul>
                        <li><a href="#" data-category="courses">Quản lý khóa học</a></li>
                        <li><a href="#" data-category="attendance">Điểm danh & báo cáo</a></li>
                    </ul>
                `
            },
            courses: {
                title: "Quản lý khóa học",
                content: `
                    <h4>Quản lý khóa học</h4>
                    <p>Hướng dẫn chi tiết về cách tạo và quản lý các khóa học trong hệ thống.</p>
                    
                    <h5>1. Tạo khóa học mới</h5>
                    <ol>
                        <li>Vào menu "Khóa học" trên sidebar</li>
                        <li>Click nút "Thêm mới"</li>
                        <li>Điền thông tin khóa học</li>
                        <li>Chọn giáo viên phụ trách</li>
                        <li>Click "Tạo khóa học"</li>
                    </ol>
                    
                    <h5>2. Phân công học viên</h5>
                    <ol>
                        <li>Vào trang chi tiết khóa học</li>
                        <li>Click tab "Học viên"</li>
                        <li>Chọn học viên từ danh sách</li>
                        <li>Click "Thêm vào khóa học"</li>
                    </ol>
                    
                    <div class="text-center my-3">
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="text-muted mt-2">[Ảnh chụp màn hình: Giao diện quản lý khóa học]</p>
                        </div>
                    </div>
                    
                    <h5>3. Quản lý lịch học</h5>
                    <ol>
                        <li>Trong trang khóa học, click "Lịch học"</li>
                        <li>Tạo lịch học cho khóa học</li>
                        <li>Kiểm tra xung đột lịch</li>
                        <li>Xuất lịch học</li>
                    </ol>
                    
                    <h5>Bài liên quan:</h5>
                    <ul>
                        <li><a href="#" data-category="students">Quản lý học viên</a></li>
                        <li><a href="#" data-category="attendance">Điểm danh & báo cáo</a></li>
                    </ul>
                `
            },
            attendance: {
                title: "Điểm danh & báo cáo",
                content: `
                    <h4>Điểm danh & báo cáo</h4>
                    <p>Hướng dẫn chi tiết về cách thực hiện điểm danh và xem báo cáo.</p>
                    
                    <h5>1. Thực hiện điểm danh</h5>
                    <ol>
                        <li>Vào menu "Điểm danh" trên sidebar</li>
                        <li>Chọn lớp cần điểm danh</li>
                        <li>Chọn ngày học</li>
                        <li>Đánh dấu học viên có mặt/vắng</li>
                        <li>Click "Lưu điểm danh"</li>
                    </ol>
                    
                    <h5>2. Xem báo cáo điểm danh</h5>
                    <ol>
                        <li>Vào menu "Báo cáo"</li>
                        <li>Chọn loại báo cáo cần xem</li>
                        <li>Chọn thời gian báo cáo</li>
                        <li>Xem thống kê chi tiết</li>
                        <li>Xuất báo cáo nếu cần</li>
                    </ol>
                    
                    <div class="text-center my-3">
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="text-muted mt-2">[Ảnh chụp màn hình: Giao diện điểm danh]</p>
                        </div>
                    </div>
                    
                    <h5>3. Thống kê tổng quan</h5>
                    <ul>
                        <li>Tỷ lệ đi học theo lớp</li>
                        <li>Học viên vắng nhiều</li>
                        <li>Xu hướng điểm danh theo thời gian</li>
                    </ul>
                    
                    <h5>Bài liên quan:</h5>
                    <ul>
                        <li><a href="#" data-category="courses">Quản lý khóa học</a></li>
                        <li><a href="#" data-category="students">Quản lý học viên</a></li>
                    </ul>
                `
            },
            system: {
                title: "Cấu hình hệ thống",
                content: `
                    <h4>Cấu hình hệ thống</h4>
                    <p>Hướng dẫn chi tiết về cách cấu hình các thiết lập hệ thống.</p>
                    
                    <h5>1. Cài đặt chung</h5>
                    <ol>
                        <li>Vào menu "Cài đặt" trên sidebar</li>
                        <li>Chọn tab "Cài đặt chung"</li>
                        <li>Thay đổi thông tin trung tâm</li>
                        <li>Cập nhật logo và thông tin liên hệ</li>
                        <li>Click "Lưu cài đặt"</li>
                    </ol>
                    
                    <h5>2. Quản lý người dùng</h5>
                    <ol>
                        <li>Vào menu "Người dùng"</li>
                        <li>Thêm/sửa/xóa tài khoản</li>
                        <li>Phân quyền cho từng vai trò</li>
                        <li>Quản lý nhóm người dùng</li>
                    </ol>
                    
                    <div class="text-center my-3">
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="text-muted mt-2">[Ảnh chụp màn hình: Giao diện cài đặt hệ thống]</p>
                        </div>
                    </div>
                    
                    <h5>3. Sao lưu dữ liệu</h5>
                    <ol>
                        <li>Vào "Cài đặt" → "Sao lưu"</li>
                        <li>Chọn loại dữ liệu cần sao lưu</li>
                        <li>Chọn thời gian sao lưu</li>
                        <li>Click "Tạo bản sao lưu"</li>
                    </ol>
                    
                    <h5>Bài liên quan:</h5>
                    <ul>
                        <li><a href="#" data-category="account">Quản lý tài khoản</a></li>
                        <li><a href="#" data-category="attendance">Điểm danh & báo cáo</a></li>
                    </ul>
                `
            }
        };

        // Xử lý tìm kiếm
        $('#searchGuide').on('input', function() {
            const keyword = $(this).val().toLowerCase();
            if (keyword.length > 0) {
                // Tìm kiếm trong nội dung
                const results = [];
                Object.keys(guideData).forEach(category => {
                    if (guideData[category].title.toLowerCase().includes(keyword) || 
                        guideData[category].content.toLowerCase().includes(keyword)) {
                        results.push(category);
                    }
                });
                
                // Hiển thị kết quả
                if (results.length > 0) {
                    loadGuideContent(results[0]);
                }
            }
        });

        // Xử lý click danh mục
        $('#guideCategories a').on('click', function(e) {
            e.preventDefault();
            const category = $(this).data('category');
            
            // Cập nhật active state
            $('#guideCategories a').removeClass('active');
            $(this).addClass('active');
            
            // Load nội dung
            loadGuideContent(category);
        });

        // Load nội dung hướng dẫn
        function loadGuideContent(category) {
            if (guideData[category]) {
                $('#guideContent').html(guideData[category].content);
                
                // Xử lý link bài liên quan
                $('#guideContent a[data-category]').on('click', function(e) {
                    e.preventDefault();
                    const targetCategory = $(this).data('category');
                    $('#guideCategories a[data-category="' + targetCategory + '"]').click();
                });
            }
        }

        // Xử lý form liên hệ
        $('#contactForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('name', $('#contactName').val());
            formData.append('email', $('#contactEmail').val());
            formData.append('subject', $('#contactSubject').val());
            formData.append('description', $('#contactDescription').val());
            formData.append('priority', $('#contactPriority').val());
            
            const file = $('#contactFile')[0].files[0];
            if (file) {
                formData.append('file', file);
            }
            
            // Hiển thị loading
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Đang gửi...').prop('disabled', true);
            
            // Gửi request (giả lập)
            setTimeout(() => {
                alert('Yêu cầu hỗ trợ đã được gửi thành công! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
                
                // Reset form
                $('#contactForm')[0].reset();
                $('#contactSubject').val('');
                $('#contactDescription').val('');
                
                // Reset button
                submitBtn.html(originalText).prop('disabled', false);
                
                // Thêm vào lịch sử (giả lập)
                addToHistory({
                    subject: formData.get('subject'),
                    date: new Date().toLocaleDateString('vi-VN'),
                    status: 'Chờ xử lý'
                });
            }, 2000);
        });

        // Thêm vào lịch sử
        function addToHistory(item) {
            const historyHtml = `
                <tr>
                    <td>${item.subject}</td>
                    <td>${item.date}</td>
                    <td><span class="badge badge-warning">${item.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewSupportDetail('${item.subject}')">
                            <i class="fas fa-eye"></i> Xem
                        </button>
                    </td>
                </tr>
            `;
            
            if ($('#supportHistory tr').length === 1 && $('#supportHistory tr td').attr('colspan')) {
                $('#supportHistory').html(historyHtml);
            } else {
                $('#supportHistory').prepend(historyHtml);
            }
        }

        // Xem chi tiết yêu cầu hỗ trợ
        function viewSupportDetail(subject) {
            $('#supportDetailContent').html(`
                <h5>${subject}</h5>
                <p><strong>Ngày gửi:</strong> ${new Date().toLocaleDateString('vi-VN')}</p>
                <p><strong>Trạng thái:</strong> <span class="badge badge-warning">Chờ xử lý</span></p>
                <hr>
                <h6>Nội dung:</h6>
                <p>Đây là nội dung chi tiết của yêu cầu hỗ trợ...</p>
            `);
            $('#supportDetailModal').modal('show');
        }

        // Load nội dung mặc định
        loadGuideContent('account');
    </script>
</x-layouts.dash-admin>
