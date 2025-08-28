# 🧪 Bảng Testcase Tổng Thể – EduCore

Tài liệu này liệt kê các testcase chức năng, bảo mật và hiệu năng chính cho toàn hệ thống EduCore (Laravel + Livewire). Bao phủ 3 vai trò: Admin, Teacher, Student và các module: Users, Classrooms, Schedules, Attendance, Assignments, Grading, Lessons, Quiz, Chat, Notifications, Reports, Finance, Evaluation, cùng các tính năng nền như i18n, upload, video embed.

## Quy ước

- ID: MODULE-XXX (ví dụ: AUTH-001, QUIZ-010)
- Priority: P1 (Critical), P2 (High), P3 (Normal)
- Type: F (Functional), S (Security), P (Performance), U (Usability)
- Ký hiệu route: theo `routes/web.php` (GET/POST cùng tên route nếu có)

## Tiền điều kiện chung

- DB đã migrate/seed phù hợp (có thể dùng các seeder sẵn có: `UserSeeder`, `TeacherClassroomSeeder`, `AssignmentSeeder`, `NotificationSeeder`, ...)
- Storage đã link: `php artisan storage:link`
- Broadcasting cấu hình khi test Chat (Pusher/Echo) nếu cần realtime

---

## 1) Authentication & RBAC & i18n

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước thực hiện | Kết quả mong đợi | Route/URL | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| AUTH-001 | Đăng nhập hợp lệ | All | User tồn tại | Truy cập /login, nhập số ĐT + mật khẩu đúng | Redirect tới `dashboard` | GET /login | P1 | F |
| AUTH-002 | Đăng nhập sai mật khẩu | All | User tồn tại | Nhập sai mật khẩu | Thông báo lỗi, không đăng nhập | GET /login | P1 | F |
| AUTH-003 | Đăng xuất | All | Đã đăng nhập | Nhấn Logout | Session bị hủy, về /login | POST /logout | P1 | F |
| AUTH-004 | Chuyển ngôn ngữ | All | — | Nhấn /lang/vi, /lang/en, /lang/zh | UI chuyển ngôn ngữ, session locale set | GET /lang/{locale} | P2 | F/U |
| RBAC-001 | Chặn truy cập khi chưa đăng nhập | All | Chưa login | Truy cập /dashboard | Redirect về /login | GET /dashboard | P1 | S |
| RBAC-002 | Admin vào route Admin | Admin | Đã login Admin | Truy cập /admin/users | Truy cập được | /admin/... | P1 | F/S |
| RBAC-003 | Teacher bị chặn route Admin | Teacher | Đã login Teacher | Truy cập /admin/users | 403 hoặc redirect | /admin/... | P1 | S |
| RBAC-004 | Student bị chặn route Teacher | Student | Đã login Student | Truy cập /teacher/quizzes | 403 hoặc redirect | /teacher/... | P1 | S |

---

## 2) Dashboard

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| DASH-001 | Vào dashboard sau login | Admin/Teacher/Student | Đăng nhập | Truy cập /dashboard | Hiển thị trang tổng quan phù hợp vai trò | GET /dashboard | P2 | F/U |

---

## 3) Admin – Users

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| USERS-001 | Danh sách users | Admin | — | Truy cập users.index | Hiển thị list, tìm kiếm, phân trang | /admin/users | P2 | F |
| USERS-002 | Tạo user mới | Admin | — | Vào users.create, nhập form hợp lệ, submit | User tạo thành công | /admin/users/create | P1 | F |
| USERS-003 | Sửa user | Admin | Có user | Vào users.edit, cập nhật, submit | Cập nhật thành công | /admin/users/{id}/edit | P1 | F |

---

## 4) Admin – Classrooms & Assign Students & Schedules

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| CLASS-001 | Danh sách lớp | Admin | — | Truy cập classrooms.index | Hiển thị danh sách lớp | /admin/classrooms | P2 | F |
| CLASS-002 | Tạo lớp | Admin | — | classrooms.create, nhập hợp lệ, submit | Tạo lớp thành công | /admin/classrooms/create | P1 | F |
| CLASS-003 | Sửa lớp | Admin | Có lớp | classrooms.edit, cập nhật, submit | Cập nhật thành công | /admin/classrooms/{id}/edit | P1 | F |
| CLASS-004 | Xem lớp | Admin | Có lớp | classrooms.show | Hiển thị chi tiết | /admin/classrooms/{id} | P3 | F |
| CLASS-005 | Gán học sinh | Admin | Có lớp, học sinh | classrooms.assign-students | Gán học sinh, lưu | /admin/classrooms/{id}/assign-students | P1 | F |
| CONFLICT-001 | Cảnh báo trùng lịch khi gán | Admin | Học sinh đã ở lớp trùng khung giờ | Thực hiện gán học sinh | Modal cảnh báo trùng lịch (theo `ScheduleConflictHelper`) | /admin/classrooms/{id}/assign-students | P1 | F/S |
| SCHED-001 | Danh sách lịch học | Admin | Có lớp | /admin/schedules | Hiển thị list | /admin/schedules | P3 | F |
| SCHED-002 | Tạo lịch học | Admin | Có lớp | /admin/schedules/create, nhập hợp lệ | Tạo lịch thành công | /admin/schedules/create | P2 | F |
| SCHED-003 | Sửa lịch học | Admin | Có lớp | /admin/schedules/{class}/edit | Cập nhật thành công | /admin/schedules/{class}/edit | P2 | F |

---

## 5) Attendance (Admin + Teacher)

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| ATT-001 | Admin – Tổng quan điểm danh | Admin | Có dữ liệu | /admin/attendances | Hiển thị tổng quan | /admin/attendances | P3 | F |
| ATT-002 | Admin – Lịch sử điểm danh | Admin | Có dữ liệu | /admin/attendances/history | Hiển thị lịch sử | /admin/attendances/history | P3 | F |
| ATT-003 | Admin – Điểm danh lớp | Admin | Có lớp | /admin/classrooms/{id}/attendance | Có thể điểm danh | /admin/classrooms/{id}/attendance | P2 | F |
| ATT-004 | Validation: Không điểm danh tương lai | Admin/Teacher | Theo tài liệu ATTENDANCE_VALIDATION_README.md | Chọn ngày tương lai | Bị chặn, thông báo rõ ràng | N/A | P1 | F/S |
| ATT-005 | Validation: Không trước giờ học | Admin/Teacher | Có lịch giờ học | Điểm danh trước start time | Bị chặn đúng thông điệp | N/A | P1 | F/S |
| ATT-006 | Validation: Không sau giờ học | Admin/Teacher | Có lịch giờ học | Điểm danh sau end time | Bị chặn đúng thông điệp | N/A | P1 | F/S |
| ATT-007 | Teacher – Điểm danh lớp | Teacher | Có lớp phụ trách | /teacher/attendance/{class}/take | Điểm danh trong giờ hợp lệ | /teacher/attendance/{class}/take | P1 | F |

---

## 6) Assignments & Grading

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| ASSG-001 | Admin – Tổng quan bài tập | Admin | — | /admin/assignments | Hiển thị tổng quan | /admin/assignments | P3 | F |
| ASSG-002 | Admin – Tạo bài tập | Admin | Có lớp | /admin/assignments/create, nhập hợp lệ | Tạo thành công | /admin/assignments/create | P1 | F |
| ASSG-003 | Teacher – Danh sách bài tập | Teacher | — | /teacher/assignments | Hiển thị list | /teacher/assignments | P3 | F |
| ASSG-004 | Teacher – Tạo bài tập | Teacher | Có lớp | /teacher/assignments/create | Tạo thành công | /teacher/assignments/create | P1 | F |
| ASSG-005 | Student – Xem danh sách | Student | Có bài tập | /student/assignments | List + filter | /student/assignments | P2 | F/U |
| ASSG-006 | Student – Xem chi tiết | Student | Có bài tập | /student/assignments/{id} | Hiển thị chi tiết | /student/assignments/{id} | P2 | F |
| ASSG-007 | Student – Nộp bài (text) | Student | Còn hạn | /student/assignments/{id}/submit | Nộp thành công, lưu nội dung | /student/assignments/{id}/submit | P1 | F |
| ASSG-008 | Student – Nộp ảnh/audio/video | Student | Còn hạn, file hợp lệ | Upload file đúng định dạng/kích thước | Lưu file, preview được | /student/assignments/{id}/submit | P1 | F |
| ASSG-009 | Student – Quá hạn không nộp | Student | Quá deadline | Cố gắng submit | Bị chặn theo quy tắc | /student/assignments/{id}/submit | P1 | F/S |
| GRADE-001 | Admin – Chấm bài | Admin | Có submissions | /admin/grading/{assignment} | Nhập điểm + nhận xét, lưu | /admin/grading/{assignment} | P1 | F |
| GRADE-002 | Teacher – Chấm bài | Teacher | Có submissions | /teacher/grading/{assignment} | Lưu điểm + feedback | /teacher/grading/{assignment} | P1 | F |

---

## 7) Lessons & Video Embed

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| LES-001 | Admin – CRUD bài học | Admin | Có lớp | Tạo/sửa/xóa bài học | Lưu dữ liệu, xóa soft/hard theo thiết kế | /admin/lessons/... | P2 | F |
| LES-002 | Teacher – CRUD bài học | Teacher | Có lớp phụ trách | /teacher/lessons (index/create/edit/show) | Thao tác thành công, đúng quyền | /teacher/lessons/... | P2 | F/S |
| LES-003 | Video Embed – YouTube | Admin/Teacher/Student | Bài học có URL YouTube | Mở chi tiết bài học | Player nhúng hiển thị | N/A | P2 | F/U |
| LES-004 | Video Embed – Drive/Vimeo | Admin/Teacher/Student | URL hợp lệ | Mở chi tiết | Preview/Player hiển thị | N/A | P2 | F/U |
| LES-005 | Fallback video không hỗ trợ | All | URL không hỗ trợ embed | Mở chi tiết | Hiển thị link fallback | N/A | P3 | U |

---

## 8) Quiz (Admin/Teacher/Student)

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| QUIZ-001 | Admin – CRUD quiz | Admin | Có lớp | /admin/quizzes (create/edit/show) | Lưu dữ liệu, xem kết quả | /admin/quizzes/... | P2 | F |
| QUIZ-002 | Teacher – CRUD quiz | Teacher | Có lớp | /teacher/quizzes (create/edit/show) | Lưu dữ liệu, xem kết quả | /teacher/quizzes/... | P2 | F |
| QUIZ-003 | Student – Danh sách quiz | Student | Có quiz assigned | /student/quizzes | Hiển thị danh sách | /student/quizzes | P2 | F |
| QUIZ-004 | Student – Làm bài | Student | Trước deadline | /student/quizzes/{id}/do | UI câu hỏi, autosave, điều hướng câu | /student/quizzes/{id}/do | P1 | F/U |
| QUIZ-005 | Timer hiển thị & đổi màu | Student | Có time_limit | Quan sát timer >10, ≤10, ≤5 phút | Đổi màu xanh/vàng/đỏ, cảnh báo 5’/1’ | /student/quizzes/{id}/do | P1 | F/U |
| QUIZ-006 | Auto-submit khi hết giờ | Student | time_limit > 0 | Chờ hết thời gian | Tự động nộp, tính điểm theo luật | /student/quizzes/{id}/do | P1 | F |
| QUIZ-007 | Autosave câu trả lời | Student | — | Chọn đáp án, nhập text, đợi debounce | Lưu realtime theo loại input | /student/quizzes/{id}/do | P1 | F |
| QUIZ-008 | Bảo vệ làm lại sau submit | Student | Đã submit | Reload trang/do lại | Bị chặn theo rule | /student/quizzes/{id}/do | P1 | S |
| QUIZ-009 | Xem review kết quả | Student | Đã làm | /student/quizzes/{id}/review | Hiển thị điểm, câu trả lời | /student/quizzes/{id}/review | P2 | F |
| QUIZ-010 | Lệnh test hệ thống | Dev/QA | — | `php artisan quiz:test --check/create` | Kết quả/quiz test tạo OK | CLI | P3 | F |

---

## 9) Chat (Admin/Teacher/Student)

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route/Channel | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| CHAT-001 | Danh sách & UI chat | Admin/Teacher/Student | Đã cấu hình Pusher/Echo | Truy cập trang chat | Hiển thị tabs lớp/người dùng | /{role}/chat | P2 | F/U |
| CHAT-002 | Nhắn tin realtime 1-1 | All | Hai user khác nhau | Gửi/nhận tin nhắn | Hiện realtime, typing indicator | Private channel chat-user-{id} | P1 | F |
| CHAT-003 | Chat nhóm lớp | All | Cùng lớp | Gửi tin nhắn lớp | Hiện realtime toàn thành viên | Public channel chat-class-{classId} | P1 | F |
| CHAT-004 | Upload & download file | All | File hợp lệ | Kéo thả/upload, tải xuống | Lưu file, tải được, kiểm soát quyền | /{role}/chat/download/{messageId} | P1 | F/S |
| CHAT-005 | Unread counter | All | Có tin chưa đọc | Mở/đọc tin | Đếm chưa đọc giảm chính xác | UI | P2 | F/U |
| CHAT-006 | Search trong chat | All | Có nhiều hội thoại | Tìm kiếm theo tên | Kết quả đúng, paginate | UI | P3 | F |
| CHAT-007 | Authorization channel | All | — | Join channel không có quyền | Bị từ chối | routes/channels.php | P1 | S |

---

## 10) Notifications

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| NOTI-001 | Admin – Danh sách thông báo | Admin | — | /admin/notifications | List + filter + search | /admin/notifications | P2 | F |
| NOTI-002 | Teacher – Danh sách thông báo | Teacher | — | /teacher/notifications | List + filter + search | /teacher/notifications | P2 | F |
| NOTI-003 | Student – Xem thông báo | Student | Có dữ liệu | /student/notifications | Hiển thị + đánh dấu đã đọc | /student/notifications | P2 | F |
| NOTI-004 | Lên lịch gửi thông báo | Admin/Teacher | Có scheduled_at | Tạo thông báo với lịch gửi | Command gửi chạy, thông báo đến đúng lúc | CLI + UI | P2 | F |
| NOTI-005 | Notification bell realtime | Student | Đang đăng nhập | Quan sát bell | Hiển thị số chưa đọc realtime | Navbar bell | P3 | F/U |

---

## 11) Reports

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| REP-001 | Admin – Reports tổng quan | Admin | — | /admin/reports | Hiển thị báo cáo | /admin/reports | P3 | F |
| REP-002 | Admin – Report học viên | Admin | Có học viên | /admin/reports/student/{id} | Hiển thị thống kê học viên | /admin/reports/student/{id} | P3 | F |
| REP-003 | Admin – Report lớp | Admin | Có lớp | /admin/reports/class/{id} | Hiển thị thống kê lớp | /admin/reports/class/{id} | P3 | F |
| REP-004 | Report trùng lịch | Admin | Có dữ liệu trùng | /admin/reports/schedule-conflicts | Danh sách trùng, modal scroll | /admin/reports/schedule-conflicts | P2 | F/U |
| T-REP-001 | Teacher – Reports | Teacher | — | /teacher/reports | Hiển thị báo cáo | /teacher/reports | P3 | F |
| S-REP-001 | Student – Reports | Student | — | /student/reports | Hiển thị kết quả học tập | /student/reports | P3 | F |

---

## 12) Finance (Admin)

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| FIN-001 | Thống kê tài chính | Admin | — | /admin/finance | Hiển thị tổng quan | /admin/finance | P3 | F |
| FIN-002 | Xem thanh toán user | Admin | Có user | /admin/finance/payment/{user} | Hiển thị chi tiết | /admin/finance/payment/{user} | P3 | F |
| FIN-003 | Quản lý chi phí | Admin | Có dữ liệu | /admin/finance/expenses | CRUD chi phí | /admin/finance/expenses | P2 | F |

---

## 13) Evaluation

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| EVAL-001 | Admin – Evaluation Management | Admin | — | /admin/evaluation-management | Hiển thị trang quản trị | /admin/evaluation-management | P3 | F |
| EVAL-002 | Teacher – Evaluation Report | Teacher | — | /teacher/evaluations | Hiển thị báo cáo đánh giá | /teacher/evaluations | P3 | F |
| EVAL-003 | Student – Evaluation | Student | — | /student/evaluation | Truy cập trang đánh giá | /student/evaluation | P3 | F |

---

## 14) Teacher – My Class & Schedules

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| TCLASS-001 | My Class – Danh sách | Teacher | Có lớp phụ trách | /teacher/my-class | Hiển thị lớp | /teacher/my-class | P3 | F |
| TCLASS-002 | My Class – Chi tiết lớp | Teacher | Có lớp | /teacher/my-class/{classId} | Hiển thị chi tiết, HSSV | /teacher/my-class/{id} | P3 | F |
| TSCHED-001 | Schedules – Danh sách | Teacher | — | /teacher/schedules | Hiển thị lịch | /teacher/schedules | P3 | F |

---

## 15) Student – Lessons, Schedules, Chat

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Route | Priority | Type |
|---|---|---|---|---|---|---|---|---|
| SLES-001 | Lessons – Danh sách | Student | Có bài học | /student/lessons | List + paginate | /student/lessons | P3 | F |
| SLES-002 | Lessons – Chi tiết | Student | Có bài học | /student/lessons/{id} | Hiển thị nội dung + video embed | /student/lessons/{id} | P3 | F/U |
| SSCHED-001 | Schedules | Student | Có lịch | /student/schedules | Hiển thị lịch học | /student/schedules | P3 | F |
| SCHAT-001 | Chat | Student | Pusher sẵn sàng | /student/chat | Sử dụng đầy đủ tính năng chat | /student/chat | P2 | F |

---

## 16) Upload/File Handling (Global)

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Priority | Type |
|---|---|---|---|---|---|---|---|
| UP-001 | Tạo symbolic link | Dev/QA | — | `php artisan storage:link` | Tạo link thành công | P1 | F |
| UP-002 | Kiểm tra storage | Dev/QA | — | `php artisan storage:check --fix` | Sửa quyền/thư mục nếu thiếu | P1 | F |
| UP-003 | Test upload Lessons | Teacher/Admin | File hợp lệ | Upload tài liệu bài học | Lưu file, preview được | P1 | F |
| UP-004 | Test upload Assignments | Student | File hợp lệ | Upload ảnh/audio/video | Lưu file, hạn mức size đúng | P1 | F |

---

## 17) Security & Direct Access

| ID | Mô tả | Vai trò | Tiền điều kiện | Bước | Kết quả mong đợi | Priority | Type |
|---|---|---|---|---|---|---|---|
| SEC-001 | Truy cập thẳng route khác vai trò | All | Đăng nhập không đúng vai trò | Nhập URL /admin/... với Teacher/Student | 403 hoặc redirect hợp lệ | P1 | S |
| SEC-002 | Download file chat trái quyền | All | Không thuộc hội thoại | Gọi /{role}/chat/download/{messageId} | Bị chặn | P1 | S |
| SEC-003 | Quiz: ngăn sửa câu trả lời sau submit | Student | Đã submit | Thử POST sửa dữ liệu | Từ chối, log hợp lệ | P1 | S |

---

## 18) i18n & UI/Usability

| ID | Mô tả | Vai trò | Bước | Kết quả mong đợi | Priority | Type |
|---|---|---|---|---|---|---|
| I18N-001 | Chuyển ngôn ngữ vi/en/zh | All | /lang/{locale} | Nội dung đổi ngôn ngữ, giữ session | P3 | U |
| UI-001 | Responsive các trang chính | All | Thu/phóng, mobile | Không vỡ layout, usable | P3 | U |

---

## 19) Performance & Pagination

| ID | Mô tả | Phạm vi | Tiêu chí | Priority | Type |
|---|---|---|---|---|---|
| PERF-001 | Pagination danh sách lớn | Users, Messages, Assignments | Thời gian phản hồi < 2s với 1k+ bản ghi | P3 | P |
| PERF-002 | Realtime chat ổn định | Chat | Không drop events, độ trễ chấp nhận được | P2 | P |
| PERF-003 | Quiz autosave | Quiz | Lưu ổn định, không mất dữ liệu khi refresh | P1 | P |

---

## 20) Smoke suite theo vai trò

### Admin

- Đăng nhập Admin → Users index → Classrooms create → Assign students (check conflict) → Schedules create → Attendance overview → Assignments create → Grading → Lessons create → Quiz create → Notifications → Reports (schedule-conflicts) → Finance → Logout

### Teacher

- Đăng nhập Teacher → My Class → Lessons create (+embed video) → Assignments create → Grading → Attendance take (validate thời gian) → Quizzes create → Notifications → Chat (1-1 + lớp) → Reports → Logout

### Student

- Đăng nhập Student → Lessons view → Assignments view/submit (file) → Quizzes do (timer + autosave + auto-submit) → Review quiz → Notifications → Schedules → Chat → Evaluation → Logout

---

## Gợi ý tổ chức chạy test

- Dùng `phpunit` cho Feature/Unit (ví dụ đã có `tests/Feature/RouteStatusTest.php`)
- E2E thủ công theo bảng trên; có thể tích hợp Playwright/Cypress nếu bổ sung frontend tests
- Với Chat/Quiz dùng thêm artisan commands kiểm tra nhanh: `chat:check`, `quiz:test`, `notifications:send-scheduled`

---

Tài liệu này có thể mở rộng thêm chi tiết kiểm thử dữ liệu biên, invalid inputs, và test case âm (negative) cho từng form khi triển khai QA chính thức.
