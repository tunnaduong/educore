<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AssignmentSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Lấy tất cả bài tập
        $assignments = Assignment::all();

        if ($assignments->isEmpty()) {
            return;
        }

        // Tạo bài nộp cho mỗi bài tập
        foreach ($assignments as $assignment) {
            // Lấy học viên của lớp
            $students = $assignment->classroom->students()->with('studentProfile')->get();

            if ($students->isEmpty()) {
                continue;
            }

            // Tạo bài nộp cho 70-90% học viên
            $submissionRate = rand(70, 90);
            $submissionCount = ceil(count($students) * $submissionRate / 100);

            // Chọn ngẫu nhiên học viên để nộp bài
            $submittingStudents = $students->random($submissionCount);

            foreach ($submittingStudents as $student) {
                // Tạo thời gian nộp bài (trước hoặc sau deadline)
                $isLate = $faker->boolean(20); // 20% nộp muộn
                $submittedAt = $isLate
                    ? $assignment->deadline->addDays(rand(1, 3))
                    : $assignment->deadline->subDays(rand(0, 2));

                // Tạo điểm số (nếu đã chấm)
                $isGraded = $faker->boolean(80); // 80% đã chấm
                $score = $isGraded ? rand(5, 10) : null;

                // Tạo nội dung bài nộp
                $content = $this->generateSubmissionContent($assignment, $faker);

                // Tạo file đính kèm (có thể null)
                $attachmentPath = $faker->optional(0.6)->filePath();

                // Tạo ghi chú của giáo viên
                $teacherNotes = $isGraded ? $this->generateTeacherNotes($score, $faker) : null;

                AssignmentSubmission::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->studentProfile->id,
                    'content' => $content,
                    'attachment_path' => $attachmentPath,
                    'submitted_at' => $submittedAt,
                    'score' => $score,
                    'teacher_notes' => $teacherNotes,
                    'status' => $isGraded ? 'graded' : 'submitted',
                ]);
            }
        }
    }

    /**
     * Tạo nội dung bài nộp
     */
    private function generateSubmissionContent($assignment, $faker)
    {
        $title = $assignment->title;

        // Tạo nội dung theo loại bài tập
        if (strpos($title, 'viết') !== false || strpos($title, 'Viết') !== false) {
            return $faker->paragraphs(rand(2, 4), true);
        } elseif (strpos($title, 'nghe') !== false || strpos($title, 'Nghe') !== false) {
            return 'Đã hoàn thành bài tập nghe hiểu. Ghi âm phần trả lời đã được đính kèm.';
        } elseif (strpos($title, 'đọc') !== false || strpos($title, 'Đọc') !== false) {
            return 'Đã đọc và hiểu nội dung. Trả lời các câu hỏi trong file đính kèm.';
        } elseif (strpos($title, 'phát âm') !== false || strpos($title, 'Phát âm') !== false) {
            return 'Đã ghi âm phát âm các từ vựng theo yêu cầu. File âm thanh đã đính kèm.';
        } else {
            return $faker->paragraphs(rand(1, 3), true);
        }
    }

    /**
     * Tạo ghi chú của giáo viên
     */
    private function generateTeacherNotes($score, $faker)
    {
        if ($score >= 9) {
            return $faker->randomElement([
                'Bài làm xuất sắc! Rất tốt.',
                'Hoàn thành tốt, đáp ứng đầy đủ yêu cầu.',
                'Công phu và chi tiết. Rất đáng khen.',
                'Hiểu bài sâu sắc, trình bày rõ ràng.',
            ]);
        } elseif ($score >= 7) {
            return $faker->randomElement([
                'Bài làm tốt, cần cải thiện một số điểm nhỏ.',
                'Đáp ứng yêu cầu cơ bản, cần chú ý chi tiết hơn.',
                'Hiểu bài, nhưng cần trình bày rõ ràng hơn.',
                'Có tiến bộ, tiếp tục phát huy.',
            ]);
        } elseif ($score >= 5) {
            return $faker->randomElement([
                'Cần cải thiện nhiều, chưa đáp ứng đầy đủ yêu cầu.',
                'Hiểu bài chưa sâu, cần ôn tập lại.',
                'Trình bày chưa rõ ràng, cần cố gắng hơn.',
                'Có lỗi cơ bản, cần chú ý hơn.',
            ]);
        } else {
            return $faker->randomElement([
                'Chưa đạt yêu cầu, cần làm lại.',
                'Hiểu sai đề bài, cần đọc kỹ yêu cầu.',
                'Cần ôn tập lại kiến thức cơ bản.',
                'Chưa nộp đầy đủ, cần bổ sung.',
            ]);
        }
    }
}
