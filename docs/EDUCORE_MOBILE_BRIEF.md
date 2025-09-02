### EduCore — Tài liệu tổng quan & Brief tạo ứng dụng mobile

Phiên bản: 1.0
Ngày cập nhật: {{generate-date}}

---

## 1) Tổng quan dự án

EduCore là hệ thống quản lý đào tạo cho trung tâm tiếng Trung (và có thể mở rộng cho các trung tâm ngoại ngữ khác). Ứng dụng web hiện tại được xây dựng trên Laravel 11, Livewire 3, Bootstrap, MySQL. Hệ thống tập trung vào ba nhóm người dùng: Admin, Giảng viên, Học viên, cung cấp các nghiệp vụ quản trị lớp học, điểm danh, giao bài tập, chấm điểm, quiz/thi thử, học liệu, lịch học, thông báo, chat và báo cáo.

-   Backend: PHP 8.2+, Laravel 11.x, Livewire 3.x
-   Frontend (web): Vite, Bootstrap 5, Alpine/Livewire
-   Realtime: Pusher (Laravel Echo)
-   CSDL: MySQL

Tham khảo nhanh (từ `routes/web.php`):

-   Admin: quản lý người dùng, lớp học, lịch, điểm danh, bài tập, chấm điểm, quiz, học liệu, chat, thông báo, báo cáo, tài chính, AI hỗ trợ (grading, quiz/question bank generator).
-   Teacher: lớp của tôi, bài tập (tạo/sửa/chấm), quiz, bài giảng, điểm danh, thông báo, chat, báo cáo, đánh giá.
-   Student: bài học, bài tập (nộp/điểm), quiz (làm/xem lại), thông báo, báo cáo, lịch học, chat, đánh giá chất lượng học.

Đăng nhập/phiên: sử dụng Laravel auth (đăng nhập số điện thoại/mật khẩu theo README), phân quyền theo vai trò `admin`, `teacher`, `student`.

Đa ngôn ngữ: có route `lang.switch` hỗ trợ `vi`, `en`, `zh` (lưu trong session).

---

## 2) Các phân hệ chính (từ góc nhìn mobile)

-   Xác thực & phân quyền: đăng nhập, đổi mật khẩu, duy trì phiên; phân quyền màn hình theo `role`.
-   Dashboard theo vai trò: số liệu nhanh, lịch học sắp tới, thông báo gần đây.
-   Lớp học & Lịch
    -   Admin/Teacher: tạo/chỉnh sửa lớp, gán GV-HV, xem/điểm danh, lịch dạng list/calendar.
    -   Student: xem lớp đã tham gia, lịch học, nhắc buổi học.
-   Bài học (Lessons) & Học liệu: xem danh sách bài học, chi tiết bài, tài liệu (video/PDF/slide/link), tìm kiếm bài cũ.
-   Bài tập (Assignments) & Chấm điểm
    -   Teacher: tạo/sửa bài tập, duyệt danh sách nộp, chấm điểm, phản hồi.
    -   Student: xem bài tập, nộp (text/trắc nghiệm/upload ảnh/ghi âm/video), nhận điểm/phản hồi.
-   Quiz/HSK: tạo/làm quiz, lưu điểm, xem lại bài.
-   Điểm danh (Attendance): tạo buổi, đánh dấu, ghi chú lý do nghỉ; xem lịch sử.
-   Thông báo (Notifications): nhận/gửi thông báo theo vai trò/lớp; nhắc deadline.
-   Chat 1-1/nhóm lớp: nhắn tin, gửi file, tải file đính kèm.
-   Báo cáo/Thống kê: báo cáo theo học viên/lớp; tiến độ, tỷ lệ nộp bài, tham gia buổi học; gợi ý hỗ trợ.
-   Tài chính (Admin): tổng quan thu/chi, thanh toán theo học viên (nếu mở API cho mobile).
-   AI hỗ trợ (tùy chọn): grading gợi ý, tạo quiz, tạo ngân hàng câu hỏi.

---

## 3) Kiến trúc hiện tại & lưu ý khi mở sang mobile

Web hiện tại dùng Livewire (server-driven UI) nên chưa có API REST/GraphQL đầy đủ. Khi triển khai mobile:

-   Cần bổ sung lớp API (REST/JSON hoặc GraphQL) cho các domain: Auth, Users, Classrooms, Lessons, Assignments, Submissions, Quizzes, Attendance, Notifications, Chat, Reports, Finance.
-   Realtime: dùng Pusher (hoặc Socket) cho chat/notification/live updates.
-   Media: endpoints upload/tải file an toàn (hiện có `POST /chat/upload` controller cho web), cần chuẩn hóa cho mobile.
-   Phân trang/tìm kiếm/sort lọc: thống nhất query params.
-   I18n: server trả về khóa/label hoặc dữ liệu thuần, client chịu trách nhiệm dịch theo `vi/en/zh`.
-   Bảo mật: JWT/Passport/Sanctum; CSRF không dùng cho mobile, thay bằng token-based.

---

## 4) Phác thảo API (đề xuất)

Gợi ý các nhóm endpoint (REST):

-   Auth: `POST /api/auth/login`, `POST /api/auth/logout`, `GET /api/auth/me`
-   Users: `GET /api/users/me`, `PATCH /api/users/me`
-   Classrooms: `GET /api/classrooms`, `GET /api/classrooms/{id}`
-   Schedules: `GET /api/schedules?role=student|teacher`, `GET /api/classrooms/{id}/schedule`
-   Lessons: `GET /api/lessons`, `GET /api/lessons/{id}`
-   Assignments: `GET /api/assignments`, `GET /api/assignments/{id}`, `POST /api/assignments/{id}/submit`
-   Submissions: `GET /api/assignments/{id}/submissions` (teacher), `GET /api/me/submissions` (student)
-   Grading: `POST /api/submissions/{id}/grade`
-   Quiz: `GET /api/quizzes`, `POST /api/quizzes/{id}/start`, `POST /api/quizzes/{id}/submit`, `GET /api/quizzes/{id}/review`
-   Attendance: `GET /api/attendance/overview`, `POST /api/classrooms/{id}/attendance`, `GET /api/classrooms/{id}/attendance-history`
-   Notifications: `GET /api/notifications`, `POST /api/notifications` (admin/teacher)
-   Chat: `GET /api/chat/threads`, `GET /api/chat/threads/{id}/messages`, `POST /api/chat/threads/{id}/messages`, `POST /api/chat/upload`
-   Reports: `GET /api/reports/student/{id}`, `GET /api/reports/class/{id}`
-   Finance (tùy chọn): `GET /api/finance`, `GET /api/finance/payment/{userId}`, `GET /api/finance/expenses`

Ghi chú: Đây là đề xuất để team backend chuẩn hóa và hiện thực hóa. Mobile có thể làm song song dựa trên mock server.

---

## 5) Yêu cầu ứng dụng mobile (đa nền tảng)

-   Nền tảng: React Native.
-   Hỗ trợ 3 vai trò: Admin, Teacher, Student. Ẩn/mở màn hình theo role.
-   Đăng nhập bằng số điện thoại + mật khẩu; lưu phiên an toàn; tự gia hạn token.
-   Đa ngôn ngữ: `vi`, `en`, `zh` (tự chọn ngôn ngữ, ghi nhớ lựa chọn).
-   Thông báo đẩy (Firebase Cloud Messaging hoặc tương đương): nhắc lịch, deadline bài tập, điểm số mới, tin nhắn chat.
-   Realtime chat: dùng Pusher hoặc WebSocket, nhận/gửi file đính kèm.
-   Offline-first: cache dữ liệu gần đây (bài học, lịch, thông báo), hàng đợi nộp bài khi mất mạng.
-   Upload đa phương tiện: ảnh, audio, video (giới hạn dung lượng, nén client trước khi upload nếu cần).
-   Đồng bộ lịch hệ thống (tùy chọn): thêm buổi học vào calendar thiết bị.
-   Accessibility: cỡ chữ, độ tương phản.
-   Bảo mật: mã hóa storage nhạy cảm, khóa ứng dụng (biometrics) tùy chọn.

---

## 6) Luồng người dùng chính

-   Student:
    1. Đăng nhập → Dashboard → Lịch học → Bài học → Bài tập → Nộp bài → Nhận điểm → Thông báo/Chat.
    2. Làm quiz → Lưu kết quả → Xem lại.
-   Teacher:
    1. Đăng nhập → Lớp của tôi → Điểm danh → Giao bài → Chấm bài → Gửi thông báo → Chat.
    2. Tạo/Quản lý quiz; xem báo cáo.
-   Admin:
    1. Đăng nhập → Quản trị người dùng/lớp → Lịch/Điểm danh tổng quan → Báo cáo → Thông báo → (Tài chính) → Chat.

---

## 7) Danh sách màn hình (đề xuất)

-   Auth: Splash, Chọn ngôn ngữ, Login, Forgot/Reset, Profile.
-   Dashboard: theo vai trò; thẻ thống kê, lịch sắp tới, thông báo mới.
-   Lịch & Lớp: List/Calendar, Chi tiết lớp, Danh sách học viên.
-   Lessons: Danh sách, Chi tiết, Xem tài liệu (PDF/Video/Link).
-   Assignments: Danh sách, Chi tiết, Nộp bài (text/media), Lịch sử nộp; Chấm điểm (teacher).
-   Quiz: Danh sách, Làm quiz, Kết quả, Xem lại.
-   Attendance: Tạo/Take attendance (teacher/admin), Lịch sử (theo lớp).
-   Notifications: Danh sách, Chi tiết; Tạo thông báo (admin/teacher).
-   Chat: Threads, Messages, Upload/Tải file.
-   Reports: Báo cáo theo học viên/lớp; biểu đồ.
-   Finance (tùy chọn cho admin): Tổng quan, Thanh toán, Chi phí.

---

## 8) Phi chức năng & tiêu chuẩn

-   Hiệu năng: mở app < 2s (warm), chuyển màn < 300ms, pagination lazy-loading.
-   Ổn định: crash-free rate > 99.9%.
-   Khả dụng: offline cơ bản; retry upload; background sync.
-   Khả mở rộng: module hóa theo domain; DI, service/repository pattern.
-   Chất lượng: unit test cho logic, e2e smoke flows; lint/format CI.
-   Theo dõi: Sentry/Crashlytics, analytics sự kiện học tập (ẩn danh / tuân thủ pháp lý).

---

## 9) Kế hoạch tích hợp backend

1. Backend hiện thực `Auth + Users + Classrooms + Lessons + Assignments + Submissions + Quiz` (ưu tiên cho mobile) bằng Sanctum/JWT.
2. Chuẩn hóa upload/tải file, hạn mức dung lượng, virus scan nếu có.
3. Bổ sung webhook/FCM topic cho thông báo nhắc lịch, deadline, điểm số, chat.
4. Cung cấp môi trường staging + mock server (OpenAPI) để mobile phát triển song song.

---

## 10) Deliverables cho đội mobile

-   Tài liệu kiến trúc, luồng, design system (theme, typography, spacing), component library.
-   Bộ wireframe/high-fidelity cho tất cả màn hình chính.
-   OpenAPI/GraphQL schema (mock server) + hướng dẫn auth.
-   Checklist QA và bộ test case chính.

---

## 11) Prompt cho AI bên thứ ba tạo app mobile

Sao chép đoạn sau và gửi cho AI phát triển mobile:

"""
Bạn là chuyên gia phát triển ứng dụng mobile đa nền tảng. Hãy xây dựng ứng dụng EduCore (trung tâm tiếng Trung) theo yêu cầu sau:

1. Nền tảng & Kiến trúc

-   Ưu tiên React Native (TypeScript). Clean Architecture, tách domain/data/presentation, DI.
-   State management: RN: Redux Toolkit/Zustand + React Query.
-   Networking: dựa trên OpenAPI giả định (tạo mock server) theo các nhóm endpoint: Auth, Users, Classrooms, Schedules, Lessons, Assignments, Submissions, Grading, Quiz, Attendance, Notifications, Chat, Reports, Finance (tùy chọn).
-   Auth: Token-based (Sanctum/JWT), refresh token, secure storage (Keychain/Keystore).

2. Tính năng theo vai trò

-   Student: Dashboard, Lịch, Lessons, Assignments (xem/nộp), Quiz (làm/xem lại), Notifications, Chat, Reports.
-   Teacher: My Class, Attendance (take/history), Assignments (tạo/chấm), Lessons, Quiz (CRUD), Notifications, Chat, Reports.
-   Admin: Users, Classrooms, Schedules (list/calendar), Attendance overview, Assignments/Grading overview, Quiz, Lessons, Notifications, Reports, Finance (tùy chọn), Chat.

3. UI/UX

-   Đa ngôn ngữ `vi/en/zh`. Dark mode. Thiết kế responsive cho phone/tablet. Sử dụng Gluestack hoặc Nativebase.
-   Component tái sử dụng; danh sách có pagination/infinite scroll; skeleton loading, empty states, error states rõ ràng.

4. Realtime & Thông báo

-   Chat realtime qua Pusher/WebSocket; upload/tải file đính kèm. Push notifications qua FCM, hỗ trợ topic/segment theo vai trò/lớp.

5. Offline-first

-   Cache các màn hình chính, retry hàng đợi upload khi reconnect. Bảo toàn bản nháp bài nộp.

6. Chất lượng & DevEx

-   Lint/format, unit test logic, e2e smoke. Env cấu hình (.env.\*). CI build.

7. Bàn giao

-   Source code sạch, README chi tiết (chạy mock API, cấu hình env, build). Bộ ảnh chụp màn hình/video demo. Danh sách API dùng + mapping màn hình.

Input tham chiếu:

-   Web hiện tại: Laravel 11 + Livewire 3 + Bootstrap 5, MySQL; roles: `admin/teacher/student`.
-   Đa ngôn ngữ: `vi/en/zh` qua session.
-   Realtime: Pusher/Echo.
-   Chưa có API chính thức → tự thiết kế OpenAPI từ brief này, đảm bảo có: Auth, Users, Classrooms, Lessons, Assignments, Submissions, Quiz, Attendance, Notifications, Chat, Reports. Finance là tùy chọn.

Kết quả mong muốn: Một ứng dụng mobile chạy được trên Android/iOS, đáp ứng đầy đủ tính năng cốt lõi cho từng vai trò, có mock API để chạy độc lập, có sẵn hook để chuyển sang API thật khi backend hoàn thiện.
"""

---

## 12) Liên hệ & tham chiếu

-   Repo: `https://github.com/tunnaduong/educore`
-   Logo: `public/educore-logo.png`
-   Tech stack: xem `README.md`, `composer.json`, `package.json`.
