<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Classroom;
use Carbon\Carbon;

class TeacherNotificationSeeder extends Seeder
{
    public function run()
    {
        // Lấy teacher đầu tiên
        $teacher = User::where('role', 'teacher')->first();
        
        if (!$teacher) {
            $this->command->info('Không tìm thấy teacher nào. Vui lòng chạy UserSeeder trước.');
            return;
        }

        // Lấy các lớp mà teacher đang dạy
        $classrooms = $teacher->teachingClassrooms;
        
        if ($classrooms->isEmpty()) {
            $this->command->info('Teacher chưa được gán vào lớp nào. Vui lòng chạy TeacherClassroomSeeder trước.');
            return;
        }

        $notificationTypes = ['info', 'warning', 'success', 'danger', 'reminder'];
        $notificationTitles = [
            'Thông báo lịch học tuần mới',
            'Nhắc nhở nộp bài tập',
            'Thông báo kiểm tra giữa kỳ',
            'Cập nhật lịch thi cuối kỳ',
            'Thông báo nghỉ học ngày mai',
            'Nhắc nhở chuẩn bị bài thuyết trình',
            'Thông báo thay đổi lịch học',
            'Nhắc nhở deadline nộp bài',
            'Thông báo kết quả kiểm tra',
            'Cập nhật tài liệu học tập'
        ];

        $notificationMessages = [
            'Lịch học tuần mới đã được cập nhật. Vui lòng kiểm tra và chuẩn bị bài vở đầy đủ.',
            'Hạn nộp bài tập số 3 là ngày mai. Vui lòng hoàn thành và nộp đúng hạn.',
            'Kiểm tra giữa kỳ sẽ diễn ra vào tuần tới. Các em cần ôn tập kỹ các chương đã học.',
            'Lịch thi cuối kỳ đã được cập nhật. Vui lòng kiểm tra và chuẩn bị ôn tập.',
            'Ngày mai lớp sẽ nghỉ học do thầy có việc đột xuất. Lịch học sẽ được bù vào cuối tuần.',
            'Tuần sau các em sẽ thuyết trình bài tập nhóm. Vui lòng chuẩn bị slide và nội dung.',
            'Lịch học thứ 4 tuần này sẽ được thay đổi từ 8h sáng thành 2h chiều.',
            'Deadline nộp bài tập cuối kỳ là ngày 15/12. Vui lòng hoàn thành đúng hạn.',
            'Kết quả kiểm tra giữa kỳ đã có. Các em có thể xem điểm trên hệ thống.',
            'Tài liệu học tập chương 5 đã được cập nhật. Vui lòng tải về và nghiên cứu.'
        ];

        foreach ($classrooms as $classroom) {
            // Tạo 5-10 thông báo cho mỗi lớp
            for ($i = 0; $i < rand(5, 10); $i++) {
                $type = $notificationTypes[array_rand($notificationTypes)];
                $titleIndex = array_rand($notificationTitles);
                
                $scheduledAt = null;
                if (rand(0, 1)) {
                    // 50% thông báo có lịch gửi
                    $scheduledAt = Carbon::now()->addDays(rand(-7, 14));
                }

                Notification::create([
                    'title' => $notificationTitles[$titleIndex],
                    'message' => $notificationMessages[$titleIndex],
                    'type' => $type,
                    'class_id' => $classroom->id,
                    'user_id' => $teacher->id,
                    'scheduled_at' => $scheduledAt,
                    'is_read' => rand(0, 1), // 50% đã đọc
                    'is_urgent' => rand(0, 1) === 1, // 50% khẩn cấp
                    'created_at' => Carbon::now()->subDays(rand(0, 30)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 30)),
                ]);
            }
        }

        $this->command->info('Đã tạo ' . ($classrooms->count() * rand(5, 10)) . ' thông báo cho teacher.');
    }
} 