<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evaluation;
use App\Models\Student;
use App\Models\EvaluationQuestion;

class EvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả học viên
        $students = Student::all();

        if ($students->count() == 0) {
            $this->command->info('Không có học viên nào để tạo đánh giá!');
            return;
        }

        // Tạo đánh giá cho mỗi học viên
        foreach ($students as $student) {
            // Kiểm tra xem học viên đã có đánh giá chưa
            if (!$student->evaluations()->exists()) {
                Evaluation::create([
                    'student_id' => $student->id,
                    'teacher_ratings' => [
                        0 => rand(3, 5), // Giảng viên truyền đạt nội dung dễ hiểu
                        1 => rand(3, 5), // Giảng viên sẵn sàng giải đáp thắc mắc
                        2 => rand(3, 5), // Giảng viên có sử dụng ví dụ/thực hành
                        3 => rand(3, 5), // Phong thái giảng dạy chuyên nghiệp
                        4 => rand(3, 5), // Giảng viên đúng giờ và đảm bảo thời lượng
                    ],
                    'course_ratings' => [
                        0 => rand(3, 5), // Nội dung bài giảng phù hợp
                        1 => rand(3, 5), // Tài liệu học tập dễ tiếp cận
                        2 => rand(3, 5), // Bài tập và kiểm tra giúp củng cố kiến thức
                        3 => rand(3, 5), // Hệ thống học trực tuyến ổn định
                    ],
                    'personal_satisfaction' => rand(3, 5),
                    'suggestions' => 'Khóa học rất tốt, mong muốn có thêm bài tập thực hành.',
                    'submitted_at' => now(),
                ]);
            }
        }

        $this->command->info('Đã tạo đánh giá cho ' . $students->count() . ' học viên!');
    }
}
