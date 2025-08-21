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
            'course_fee' => 'Học phí khóa học',
            'material_fee' => 'Phí tài liệu',
            'exam_fee' => 'Phí thi',
            'certificate_fee' => 'Phí chứng chỉ',
            'other' => 'Phí khác',
        ];

        // Danh sách phương thức thanh toán
        $paymentMethods = [
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'cash' => 'Tiền mặt',
            'momo' => 'Ví MoMo',
            'vnpay' => 'VNPay',
            'zalopay' => 'ZaloPay',
        ];

        // Danh sách trạng thái thanh toán
        $paymentStatuses = [
            'pending' => 'Chờ xử lý',
            'completed' => 'Hoàn thành',
            'failed' => 'Thất bại',
            'cancelled' => 'Đã hủy',
        ];

        // Tạo 30-40 giao dịch thanh toán
        $paymentCount = rand(30, 40);

        for ($i = 0; $i < $paymentCount; $i++) {
            $student = $students->random();
            $classroom = $classrooms->random();
            $paymentType = $faker->randomElement(array_keys($paymentTypes));
            $paymentMethod = $faker->randomElement(array_keys($paymentMethods));
            $paymentStatus = $faker->randomElement(array_keys($paymentStatuses));

            // Tạo số tiền hợp lý theo loại thanh toán
            $amount = $this->generateAmount($paymentType);

            // Tạo thời gian thanh toán
            $paymentDate = $faker->dateTimeBetween('-6 months', 'now');

            // Tạo thông tin giao dịch
            $transactionId = 'TXN'.strtoupper($faker->bothify('??????'));

            // Tạo ghi chú
            $notes = $this->generateNotes($paymentType, $classroom, $student, $faker);

            Payment::create([
                'user_id' => $student->id,
                'class_id' => $classroom->id,
                'amount' => $amount,
                'payment_type' => $paymentType,
                'payment_method' => $paymentMethod,
                'status' => $paymentStatus,
                'transaction_id' => $transactionId,
                'payment_date' => $paymentDate,
                'notes' => $notes,
                'proof_path' => $faker->optional(0.7)->filePath(), // 70% có file chứng minh
            ]);
        }
    }

    /**
     * Tạo số tiền hợp lý theo loại thanh toán
     */
    private function generateAmount($paymentType)
    {
        switch ($paymentType) {
            case 'course_fee':
                return rand(2000000, 5000000); // 2-5 triệu VND
            case 'material_fee':
                return rand(200000, 500000); // 200-500k VND
            case 'exam_fee':
                return rand(500000, 1000000); // 500k-1 triệu VND
            case 'certificate_fee':
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
            case 'course_fee':
                $notes = [
                    "Học phí khóa học {$classroom->name}",
                    'Thanh toán học phí tháng '.rand(1, 12),
                    "Học phí khóa học {$classroom->level}",
                    "Thanh toán học phí cho học viên {$student->name}",
                ];
                break;
            case 'material_fee':
                $notes = [
                    'Phí tài liệu học tập',
                    'Phí sách giáo khoa và tài liệu bổ trợ',
                    "Phí tài liệu khóa học {$classroom->name}",
                    'Phí in ấn tài liệu học tập',
                ];
                break;
            case 'exam_fee':
                $notes = [
                    "Phí thi HSK {$classroom->level}",
                    'Phí thi cuối khóa',
                    'Phí thi chứng chỉ quốc tế',
                    'Phí thi đánh giá năng lực',
                ];
                break;
            case 'certificate_fee':
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
