<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Classroom;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LessonSeeder extends Seeder
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

        // Danh sách tiêu đề bài giảng theo cấp độ
        $lessonTitles = [
            'HSK 1' => [
                'Bài 1: Chào hỏi cơ bản',
                'Bài 2: Giới thiệu bản thân',
                'Bài 3: Số đếm từ 1-10',
                'Bài 4: Màu sắc cơ bản',
                'Bài 5: Thành viên gia đình',
                'Bài 6: Thời gian và ngày tháng',
                'Bài 7: Đồ ăn và thức uống',
                'Bài 8: Phương tiện giao thông',
                'Bài 9: Nghề nghiệp cơ bản',
                'Bài 10: Địa điểm trong thành phố',
            ],
            'HSK 2' => [
                'Bài 1: Mua sắm và thương mại',
                'Bài 2: Du lịch và khách sạn',
                'Bài 3: Thời tiết và khí hậu',
                'Bài 4: Sở thích và giải trí',
                'Bài 5: Công việc và văn phòng',
                'Bài 6: Y tế và sức khỏe',
                'Bài 7: Giáo dục và học tập',
                'Bài 8: Thể thao và vận động',
                'Bài 9: Nghệ thuật và văn hóa',
                'Bài 10: Công nghệ và internet',
            ],
            'HSK 3' => [
                'Bài 1: Văn hóa truyền thống Trung Quốc',
                'Bài 2: Kinh tế và thương mại',
                'Bài 3: Môi trường và bảo vệ thiên nhiên',
                'Bài 4: Xã hội và cộng đồng',
                'Bài 5: Khoa học và công nghệ',
                'Bài 6: Văn học và nghệ thuật',
                'Bài 7: Lịch sử và địa lý',
                'Bài 8: Chính trị và luật pháp',
                'Bài 9: Y học và sức khỏe',
                'Bài 10: Giáo dục và nghiên cứu',
            ],
            'HSK 4' => [
                'Bài 1: Văn hóa doanh nghiệp',
                'Bài 2: Thương mại quốc tế',
                'Bài 3: Du lịch văn hóa',
                'Bài 4: Môi trường toàn cầu',
                'Bài 5: Xã hội hiện đại',
                'Bài 6: Công nghệ tiên tiến',
                'Bài 7: Văn học đương đại',
                'Bài 8: Lịch sử hiện đại',
                'Bài 9: Chính sách công',
                'Bài 10: Nghiên cứu khoa học',
            ],
            'HSK 5' => [
                'Bài 1: Văn hóa học thuật',
                'Bài 2: Kinh tế học',
                'Bài 3: Sinh thái học',
                'Bài 4: Xã hội học',
                'Bài 5: Khoa học máy tính',
                'Bài 6: Văn học cổ điển',
                'Bài 7: Lịch sử cổ đại',
                'Bài 8: Triết học phương Đông',
                'Bài 9: Y học cổ truyền',
                'Bài 10: Giáo dục đại học',
            ],
            'HSK 6' => [
                'Bài 1: Văn hóa chuyên sâu',
                'Bài 2: Kinh tế vĩ mô',
                'Bài 3: Bảo tồn môi trường',
                'Bài 4: Phát triển xã hội',
                'Bài 5: Công nghệ AI',
                'Bài 6: Văn học hiện đại',
                'Bài 7: Lịch sử thế giới',
                'Bài 8: Triết học hiện đại',
                'Bài 9: Y học hiện đại',
                'Bài 10: Nghiên cứu chuyên ngành',
            ],
        ];

        // Nội dung bài giảng mẫu
        $lessonContents = [
            'HSK 1' => [
                'Học cách chào hỏi cơ bản trong tiếng Trung. Thực hành các câu chào hỏi thông dụng.',
                'Giới thiệu bản thân bằng tiếng Trung. Học cách nói tên, tuổi, quốc tịch.',
                'Học số đếm từ 1-10 và cách sử dụng trong câu.',
                'Học tên các màu sắc cơ bản và cách mô tả màu sắc.',
                'Học từ vựng về các thành viên trong gia đình.',
                'Học cách nói thời gian, ngày tháng trong tiếng Trung.',
                'Học từ vựng về đồ ăn, thức uống và cách đặt món.',
                'Học từ vựng về phương tiện giao thông.',
                'Học từ vựng về các nghề nghiệp cơ bản.',
                'Học từ vựng về các địa điểm trong thành phố.',
            ],
            'HSK 2' => [
                'Học từ vựng và câu về mua sắm, thương mại.',
                'Học từ vựng về du lịch, khách sạn và đặt phòng.',
                'Học cách mô tả thời tiết và khí hậu.',
                'Học từ vựng về sở thích và hoạt động giải trí.',
                'Học từ vựng về công việc văn phòng và môi trường làm việc.',
                'Học từ vựng về y tế, sức khỏe và khám bệnh.',
                'Học từ vựng về giáo dục và học tập.',
                'Học từ vựng về thể thao và các hoạt động vận động.',
                'Học từ vựng về nghệ thuật và văn hóa.',
                'Học từ vựng về công nghệ và internet.',
            ],
            'HSK 3' => [
                'Tìm hiểu về văn hóa truyền thống Trung Quốc.',
                'Học từ vựng về kinh tế và thương mại.',
                'Học về môi trường và bảo vệ thiên nhiên.',
                'Học từ vựng về xã hội và cộng đồng.',
                'Học từ vựng về khoa học và công nghệ.',
                'Tìm hiểu về văn học và nghệ thuật Trung Quốc.',
                'Học về lịch sử và địa lý Trung Quốc.',
                'Học từ vựng về chính trị và luật pháp.',
                'Học từ vựng về y học và sức khỏe.',
                'Học từ vựng về giáo dục và nghiên cứu.',
            ],
            'HSK 4' => [
                'Tìm hiểu về văn hóa doanh nghiệp Trung Quốc.',
                'Học từ vựng về thương mại quốc tế.',
                'Tìm hiểu về du lịch văn hóa.',
                'Học về môi trường toàn cầu và biến đổi khí hậu.',
                'Học từ vựng về xã hội hiện đại.',
                'Học từ vựng về công nghệ tiên tiến.',
                'Tìm hiểu về văn học đương đại Trung Quốc.',
                'Học về lịch sử hiện đại Trung Quốc.',
                'Học từ vựng về chính sách công.',
                'Học từ vựng về nghiên cứu khoa học.',
            ],
            'HSK 5' => [
                'Tìm hiểu về văn hóa học thuật Trung Quốc.',
                'Học từ vựng về kinh tế học.',
                'Học từ vựng về sinh thái học.',
                'Học từ vựng về xã hội học.',
                'Học từ vựng về khoa học máy tính.',
                'Tìm hiểu về văn học cổ điển Trung Quốc.',
                'Học về lịch sử cổ đại Trung Quốc.',
                'Tìm hiểu về triết học phương Đông.',
                'Học từ vựng về y học cổ truyền.',
                'Học từ vựng về giáo dục đại học.',
            ],
            'HSK 6' => [
                'Tìm hiểu sâu về văn hóa Trung Quốc.',
                'Học từ vựng về kinh tế vĩ mô.',
                'Học về bảo tồn môi trường và phát triển bền vững.',
                'Học từ vựng về phát triển xã hội.',
                'Học từ vựng về công nghệ AI và trí tuệ nhân tạo.',
                'Tìm hiểu về văn học hiện đại Trung Quốc.',
                'Học về lịch sử thế giới và quan hệ quốc tế.',
                'Tìm hiểu về triết học hiện đại.',
                'Học từ vựng về y học hiện đại.',
                'Học từ vựng về nghiên cứu chuyên ngành.',
            ],
        ];

        // Tạo bài giảng cho mỗi lớp
        foreach ($classrooms as $classroom) {
            $level = $classroom->level;
            $titles = $lessonTitles[$level] ?? $lessonTitles['HSK 1'];
            $contents = $lessonContents[$level] ?? $lessonContents['HSK 1'];

            // Tạo 3-5 bài giảng cho mỗi lớp
            $lessonCount = rand(3, 5);

            for ($i = 0; $i < $lessonCount; $i++) {
                $title = $titles[$i] ?? $faker->sentence(3);
                $content = $contents[$i] ?? $faker->paragraph(3);

                // Tạo đường dẫn file (có thể null)
                $attachmentPath = $faker->optional(0.4)->filePath();
                $videoPath = $faker->optional(0.3)->filePath();

                Lesson::create([
                    'classroom_id' => $classroom->id,
                    'title' => $title,
                    'content' => $content,
                    'attachment_path' => $attachmentPath,
                    'video_path' => $videoPath,
                ]);
            }
        }
    }
}