<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Payment;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Lấy tất cả học viên
        $students = User::where('role', 'student')->get();
        $classrooms = Classroom::all();

        if ($students->isEmpty() || $classrooms->isEmpty()) {
            return;
        }

        // Danh sách loại thanh toán
        $paymentTypes = [
            'tuition' => 'Học phí khóa học',
            'material' => 'Phí tài liệu',
            'exam' => 'Phí thi',
            'certificate' => 'Phí chứng chỉ',
            'other' => 'Phí khác',
        ];

        // Danh sách trạng thái thanh toán
        $paymentStatuses = [
            'unpaid' => 'Chưa thanh toán',
            'partial' => 'Thanh toán một phần',
            'paid' => 'Đã thanh toán',
        ];

        // Tạo 30-40 giao dịch thanh toán
        $paymentCount = rand(30, 40);

        for ($i = 0; $i < $paymentCount; $i++) {
            $student = $students->random();
            $classroom = $classrooms->random();
            $paymentType = $faker->randomElement(array_keys($paymentTypes));
            $paymentStatus = $faker->randomElement(array_keys($paymentStatuses));

            // Tạo số tiền hợp lý theo loại thanh toán
            $amount = $this->generateAmount($paymentType);

            // Tạo thời gian thanh toán (nếu đã thanh toán)
            $paidAt = $paymentStatus === 'paid' ? $faker->dateTimeBetween('-6 months', 'now') : null;

            // Tạo ghi chú
            $note = $this->generateNotes($paymentType, $classroom, $student, $faker);

            Payment::create([
                'user_id' => $student->id,
                'class_id' => $classroom->id,
                'amount' => $amount,
                'type' => $paymentType,
                'status' => $paymentStatus,
                'note' => $note,
                'paid_at' => $paidAt,
            ]);
        }
    }

    /**
     * Tạo số tiền hợp lý theo loại thanh toán
     */
    private function generateAmount($paymentType)
    {
        switch ($paymentType) {
            case 'tuition':
                return rand(2000000, 5000000); // 2-5 triệu VND
            case 'material':
                return rand(200000, 500000); // 200-500k VND
            case 'exam':
                return rand(500000, 1000000); // 500k-1 triệu VND
            case 'certificate':
                return rand(300000, 800000); // 300-800k VND
            case 'other':
                return rand(100000, 1000000); // 100k-1 triệu VND
            default:
                return rand(500000, 2000000); // 500k-2 triệu VND
        }
    }

    /**
     * Tạo ghi chú cho thanh toán
     */
    private function generateNotes($paymentType, $classroom, $student, $faker)
    {
        $notes = [];

        switch ($paymentType) {
            case 'tuition':
                $notes = [
                    "Học phí khóa học {$classroom->name}",
                    'Thanh toán học phí tháng ' . rand(1, 12),
                    "Học phí khóa học {$classroom->level}",
                    "Thanh toán học phí cho học viên {$student->name}",
                ];
                break;
            case 'material':
                $notes = [
                    'Phí tài liệu học tập',
                    'Phí sách giáo khoa và tài liệu bổ trợ',
                    "Phí tài liệu khóa học {$classroom->name}",
                    'Phí in ấn tài liệu học tập',
                ];
                break;
            case 'exam':
                $notes = [
                    "Phí thi HSK {$classroom->level}",
                    'Phí thi cuối khóa',
                    'Phí thi chứng chỉ quốc tế',
                    'Phí thi đánh giá năng lực',
                ];
                break;
            case 'certificate':
                $notes = [
                    'Phí cấp chứng chỉ hoàn thành khóa học',
                    "Phí chứng chỉ HSK {$classroom->level}",
                    'Phí chứng nhận năng lực tiếng Trung',
                    'Phí bằng tốt nghiệp khóa học',
                ];
                break;
            case 'other':
                $notes = [
                    'Phí phát sinh khác',
                    'Phí bảo hiểm học viên',
                    'Phí hoạt động ngoại khóa',
                    'Phí dịch vụ hỗ trợ học tập',
                ];
                break;
        }

        return $faker->randomElement($notes);
    }
}
