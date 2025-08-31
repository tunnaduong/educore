<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Classroom;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Lấy tất cả các lớp học
        $classrooms = Classroom::all();

        if ($classrooms->isEmpty()) {
            return;
        }

        // Danh sách các loại bài tập
        $assignmentTypes = [
            ['image', 'text'],
            ['video', 'text'],
            ['text'],
            ['image'],
            ['video'],
            ['image', 'video', 'text'],
        ];

        // Danh sách tiêu đề bài tập theo cấp độ
        $assignmentTitles = [
            'HSK 1' => [
                'Bài tập viết chữ Hán cơ bản',
                'Luyện tập phát âm thanh mẫu',
                'Bài tập từ vựng chủ đề gia đình',
                'Luyện nghe đoạn hội thoại đơn giản',
                'Bài tập ngữ pháp câu đơn',
                'Viết đoạn văn giới thiệu bản thân',
                'Luyện tập số đếm từ 1-100',
                'Bài tập về thời gian và ngày tháng',
            ],
            'HSK 2' => [
                'Bài tập từ vựng chủ đề công việc',
                'Luyện tập câu phức tạp',
                'Bài tập đọc hiểu đoạn văn ngắn',
                'Viết email đơn giản',
                'Luyện nghe tin tức đơn giản',
                'Bài tập ngữ pháp thì quá khứ',
                'Luyện tập từ đồng nghĩa',
                'Bài tập về địa điểm và phương hướng',
            ],
            'HSK 3' => [
                'Bài tập từ vựng chủ đề văn hóa',
                'Luyện tập câu điều kiện',
                'Bài tập đọc hiểu văn bản trung bình',
                'Viết bài luận ngắn',
                'Luyện nghe phỏng vấn',
                'Bài tập ngữ pháp câu bị động',
                'Luyện tập thành ngữ cơ bản',
                'Bài tập về thời tiết và khí hậu',
            ],
            'HSK 4' => [
                'Bài tập từ vựng chủ đề kinh tế',
                'Luyện tập câu phức hợp',
                'Bài tập đọc hiểu văn bản dài',
                'Viết bài báo cáo',
                'Luyện nghe thuyết trình',
                'Bài tập ngữ pháp câu giả định',
                'Luyện tập thành ngữ nâng cao',
                'Bài tập về chính trị và xã hội',
            ],
            'HSK 5' => [
                'Bài tập từ vựng chủ đề học thuật',
                'Luyện tập văn phong trang trọng',
                'Bài tập đọc hiểu văn học',
                'Viết bài nghiên cứu',
                'Luyện nghe hội thảo',
                'Bài tập ngữ pháp câu phức tạp',
                'Luyện tập từ Hán Việt',
                'Bài tập về lịch sử và triết học',
            ],
            'HSK 6' => [
                'Bài tập từ vựng chủ đề chuyên ngành',
                'Luyện tập văn phong báo chí',
                'Bài tập đọc hiểu văn bản học thuật',
                'Viết bài luận văn',
                'Luyện nghe hội nghị quốc tế',
                'Bài tập ngữ pháp nâng cao',
                'Luyện tập từ cổ và từ Hán cổ',
                'Bài tập về văn hóa và nghệ thuật',
            ],
        ];

        // Mô tả bài tập mẫu
        $descriptions = [
            'Hoàn thành bài tập theo yêu cầu trong file đính kèm. Nộp bài trước deadline.',
            'Luyện tập kỹ năng nghe và trả lời câu hỏi. Ghi âm phần trả lời của bạn.',
            'Viết đoạn văn ngắn theo chủ đề đã cho. Sử dụng từ vựng và ngữ pháp đã học.',
            'Đọc hiểu văn bản và trả lời các câu hỏi. Chú ý đến ngữ cảnh và ý nghĩa.',
            'Luyện tập phát âm và ghi âm lại. So sánh với file mẫu để cải thiện.',
            'Hoàn thành bài tập trắc nghiệm. Chọn đáp án chính xác nhất.',
            'Viết email theo tình huống đã cho. Sử dụng văn phong phù hợp.',
            'Luyện tập từ vựng mới. Tạo câu với các từ đã học.',
        ];

        // Tạo bài tập cho mỗi lớp
        foreach ($classrooms as $classroom) {
            $level = $classroom->level;
            $titles = $assignmentTitles[$level] ?? $assignmentTitles['HSK 1'];

            // Tạo 2-4 bài tập cho mỗi lớp
            $assignmentCount = rand(2, 4);

            for ($i = 0; $i < $assignmentCount; $i++) {
                $title = $faker->randomElement($titles);
                $description = $faker->randomElement($descriptions);

                // Tạo deadline ngẫu nhiên (1-7 ngày từ hiện tại)
                $deadline = now()->addDays(rand(1, 7));

                // Chọn loại bài tập ngẫu nhiên
                $types = $faker->randomElement($assignmentTypes);

                // Tạo đường dẫn file (có thể null)
                $attachmentPath = $faker->optional(0.3)->filePath();
                $videoPath = $faker->optional(0.2)->filePath();

                Assignment::create([
                    'class_id' => $classroom->id,
                    'title' => $title,
                    'description' => $description,
                    'deadline' => $deadline,
                    'types' => $types,
                    'attachment_path' => $attachmentPath,
                    'video_path' => $videoPath,
                    'max_score' => $faker->randomFloat(1, 5, 10),
                ]);
            }
        }
    }
}
