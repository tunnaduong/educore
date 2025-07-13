<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Classroom;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $classrooms = Classroom::all();

        if ($students->count() > 0) {
            // Tạo thông báo mẫu cho từng học viên
            foreach ($students as $student) {
                // Thông báo thông tin
                Notification::create([
                    'title' => 'Chào mừng bạn đến với EduCore!',
                    'message' => 'Chúc mừng bạn đã tham gia hệ thống học tập trực tuyến. Hãy khám phá các tính năng mới và bắt đầu học tập ngay hôm nay!',
                    'type' => 'info',
                    'user_id' => $student->id,
                    'is_read' => false,
                    'created_at' => now()->subDays(2),
                ]);

                // Thông báo nhắc nhở
                Notification::create([
                    'title' => 'Nhắc nhở: Kiểm tra bài tập',
                    'message' => 'Bạn có bài tập mới cần hoàn thành. Vui lòng kiểm tra và nộp bài trước thời hạn.',
                    'type' => 'reminder',
                    'user_id' => $student->id,
                    'is_read' => false,
                    'created_at' => now()->subDay(),
                ]);

                // Thông báo thành công
                Notification::create([
                    'title' => 'Hoàn thành khóa học cơ bản',
                    'message' => 'Chúc mừng! Bạn đã hoàn thành xuất sắc khóa học cơ bản. Tiếp tục phát huy nhé!',
                    'type' => 'success',
                    'user_id' => $student->id,
                    'is_read' => true,
                    'created_at' => now()->subDays(3),
                ]);
            }

            // Tạo thông báo toàn hệ thống
            Notification::create([
                'title' => 'Bảo trì hệ thống',
                'message' => 'Hệ thống sẽ được bảo trì vào ngày mai từ 2:00 - 4:00 sáng. Vui lòng lưu ý và sắp xếp thời gian học tập phù hợp.',
                'type' => 'warning',
                'user_id' => null, // Thông báo cho tất cả
                'is_read' => false,
                'created_at' => now()->subHours(6),
            ]);

            // Tạo thông báo cho lớp học cụ thể
            if ($classrooms->count() > 0) {
                $classroom = $classrooms->first();
                Notification::create([
                    'title' => 'Lịch học thay đổi - ' . $classroom->name,
                    'message' => 'Lớp ' . $classroom->name . ' sẽ học vào thứ 6 thay vì thứ 5 tuần này. Vui lòng cập nhật lịch học.',
                    'type' => 'info',
                    'user_id' => null,
                    'class_id' => $classroom->id,
                    'is_read' => false,
                    'created_at' => now()->subHours(12),
                ]);
            }
        }

        $this->command->info('Đã tạo ' . Notification::count() . ' thông báo mẫu.');
    }
} 