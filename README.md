# EduCore – Hệ thống quản lý đào tạo trung tâm tiếng Trung hiện đại 🎓

**EduCore** là một hệ thống quản lý đào tạo hiện đại, tùy biến cao, được thiết kế dành riêng cho các trung tâm đào tạo, đặc biệt là các trung tâm dạy ngoại ngữ như tiếng Trung. Hệ thống hỗ trợ toàn diện từ quản lý người dùng, lớp học, điểm danh, giao bài tập, cho đến kiểm tra và báo cáo tiến độ học tập. EduCore được xây dựng với Laravel, Livewire, Bootstrap và Tailwind CSS, hướng đến trải nghiệm người dùng mượt mà, dễ sử dụng và dễ mở rộng.

---

## ✨ Key Features (Tính năng nổi bật)

### 1. **User Management & Role-Based Access**

-   Quản lý người dùng với các vai trò: **Admin**, **Giảng viên**, **Học viên**.
-   Phân quyền rõ ràng theo vai trò, hỗ trợ **đăng nhập bằng số điện thoại + mật khẩu**.
-   Tự động phân quyền theo phiên đăng nhập và loại tài khoản.

### 2. **Lớp học linh hoạt**

-   Tạo, chỉnh sửa, xoá lớp học.
-   Gán giảng viên & học viên.
-   Thiết lập **lịch học theo tuần/tháng**.
-   Phân loại lớp theo trình độ (Sơ cấp, HSK 3, HSK 4,...).

### 3. **Hồ sơ học viên**

-   Lưu trữ đầy đủ thông tin cá nhân và quá trình học tập.
-   Theo dõi **tiến độ học**, **điểm số**, **trạng thái** (đang học, nghỉ, bảo lưu).
-   Lưu **lịch sử điểm danh và bài tập**.

### 4. **Điểm danh chi tiết**

-   Giảng viên điểm danh từng buổi học.
-   Ghi chú lý do nghỉ, thống kê buổi học.
-   Tích hợp dữ liệu điểm danh vào hồ sơ học viên.

### 5. **Hệ thống giao bài tập theo lộ trình**

-   Giao bài tập theo từng **bài học cụ thể**
-   Hỗ trợ nhiều loại bài tập:

    -   Làm bài trực tuyến (trắc nghiệm, điền từ)
    -   Upload ảnh (bài viết tay)
    -   Ghi âm/video luyện nói

-   Hệ thống **deadline + khóa nộp bài tự động**, giảng viên chấm điểm và phản hồi.

### 6. **Quiz & Kiểm tra**

-   Tạo bài **thi thử HSK**, kiểm tra kỹ năng nghe – nói – đọc – viết.
-   Nhiều dạng bài: trắc nghiệm, điền từ, tự luận,...
-   Lưu trữ điểm, thời gian làm bài.

### 7. **Thư viện học liệu & xem lại bài cũ**

-   Học viên có thể xem lại bài đã học.
-   Hỗ trợ link video (YouTube, Drive...), slide, PDF,...
-   Tìm kiếm lại bài cũ theo tên/số bài.

### 8. **Thống kê và báo cáo**

-   Báo cáo theo từng học viên hoặc cả lớp.
-   Thống kê tiến độ học, điểm trung bình, tỷ lệ nộp bài, số buổi tham gia.
-   Gợi ý học viên cần hỗ trợ thêm.

### 9. **Thông báo & nhắc lịch tự động**

-   Gửi nhắc lịch học, hạn nộp bài qua **email** hoặc **Zalo** (nếu có tích hợp OA).
-   Tự động khóa form nộp bài sau deadline.
-   Giảng viên gửi thông báo chung hoặc riêng.

### 10. **Tương tác và chat**

-   Chat 1-1 giữa học viên và giảng viên.
-   Thông báo đầu lớp, gửi kèm file nếu cần.

### 11. **Phân loại & lọc học viên**

-   Lọc học viên theo **trình độ**, **trạng thái** học tập.
-   Hỗ trợ quản lý dễ dàng theo tiêu chí riêng.

---

## 👥 Our Team (Thành viên nhóm)

Dự án này được xây dựng bởi một nhóm các nhà phát triển tâm huyết.

| Tên thành viên | Vai trò                          | GitHub                                           |
| -------------- | -------------------------------- | ------------------------------------------------ |
| Dương Tùng Anh | Project Manager / Full-stack Dev | [@tunnaduong](https://github.com/tunnaduong)     |
| Hoàng Tuấn Anh | Frontend / UI-UX                 | [@anhhtpn00019](https://github.com/anhhtpn00019) |
| Nguyễn Đức Duy | Backend Dev                      | [@duyandie](https://github.com/duyandie)         |
| Phạm Linh Chi  | Tester / QA                      | [@chi-pn00026](https://github.com/chi-pn00026)   |
| Hồ Đức         | Frontend / UI-UX                 | [@Duch147](https://github.com/Duch147)           |
| Trần Duy Hải   | Documentation                    | [@DuyHai1708](https://github.com/DuyHai1708)     |

---

## 🚀 Tech Stack (Công nghệ sử dụng)

-   **Backend:** Laravel 11.x, PHP 8.3+
-   **Frontend:** Bootstrap 5.x, Tailwind CSS, Livewire 3.x / Alpine.js
-   **Database:** MySQL

---

## 🛠️ Installation Guide (Hướng dẫn cài đặt)

### Yêu cầu:

-   Laragon
-   MySQL
-   Apache/NGINX
-   PHP 8.3+
-   Composer

### Cài đặt:

```bash
git clone https://github.com/tunnaduong/educore.git
cd educore

cp .env.example .env
# Cập nhật thông tin kết nối DB và APP_URL

composer install
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

Sau khi cài đặt, truy cập địa chỉ `http://localhost:8000` (hoặc theo `APP_URL`).

---

## 🤝 Contributing

Chúng tôi hoan nghênh mọi đóng góp từ cộng đồng:

```bash
git checkout -b feature/YourFeature
git commit -m "Add YourFeature"
git push origin feature/YourFeature
```

Rồi mở Pull Request ❤️

---

## 📄 License

Dự án được cấp phép theo **MIT License**.

---

## 📬 Contact

Dự án được phát triển bởi Team No Sleep - FPT Polytechnic Hà Nam.  
Liên hệ: Dương Tùng Anh - `tunnaduong@gmail.com` (Nhóm trưởng)  
Repo: [github.com/tunnaduong/educore](https://github.com/tunnaduong/educore)

---

> ✨ _EduCore – Đồng hành cùng các trung tâm tiếng Trung xây dựng hệ thống học tập hiệu quả và hiện đại._
