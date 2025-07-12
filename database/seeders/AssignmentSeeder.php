<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\Classroom;
use Carbon\Carbon;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = Classroom::all();

        if ($classrooms->isEmpty()) {
            $this->command->info('Không có lớp học nào để tạo bài tập. Vui lòng tạo lớp học trước.');
            return;
        }

        $assignments = [
            [
                'title' => 'Bài tập viết chữ Hán cơ bản',
                'description' => 'Viết lại các từ vựng cơ bản bằng chữ Hán. Chụp ảnh bài viết và upload lên.',
                'types' => ['image'],
                'deadline' => Carbon::now()->addDays(7),
            ],
            [
                'title' => 'Luyện phát âm từ vựng',
                'description' => 'Ghi âm phát âm các từ vựng sau: 你好，谢谢，再见，对不起. Đảm bảo phát âm rõ ràng và chính xác.',
                'types' => ['audio'],
                'deadline' => Carbon::now()->addDays(5),
            ],
            [
                'title' => 'Điền từ vào chỗ trống',
                'description' => 'Điền các từ thích hợp vào chỗ trống trong đoạn văn sau: "我___学生，我___越南人。"',
                'types' => ['text'],
                'deadline' => Carbon::now()->addDays(3),
            ],
            [
                'title' => 'Bài luận về văn hóa Trung Quốc',
                'description' => 'Viết một bài luận ngắn (300-500 từ) về một khía cạnh văn hóa Trung Quốc mà bạn quan tâm. Bài luận cần có cấu trúc rõ ràng với mở bài, thân bài và kết luận.',
                'types' => ['essay'],
                'deadline' => Carbon::now()->addDays(8),
            ],
            [
                'title' => 'Video giới thiệu bản thân',
                'description' => 'Quay video giới thiệu bản thân bằng tiếng Trung. Video nên có độ dài 1-2 phút.',
                'types' => ['video'],
                'deadline' => Carbon::now()->addDays(10),
            ],
            [
                'title' => 'Bài tập tổng hợp',
                'description' => 'Hoàn thành bài tập tổng hợp bao gồm: viết chữ, phát âm và điền từ.',
                'types' => ['text', 'image', 'audio'],
                'deadline' => Carbon::now()->addDays(14),
            ],
        ];

        foreach ($classrooms as $classroom) {
            foreach ($assignments as $assignmentData) {
                Assignment::create([
                    'class_id' => $classroom->id,
                    'title' => $assignmentData['title'],
                    'description' => $assignmentData['description'],
                    'types' => $assignmentData['types'],
                    'deadline' => $assignmentData['deadline'],
                ]);
            }
        }

        $this->command->info('Đã tạo ' . count($assignments) * $classrooms->count() . ' bài tập mẫu.');
    }
}
