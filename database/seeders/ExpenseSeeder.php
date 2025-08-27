<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Expense;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Lấy tất cả nhân viên và lớp học
        $staff = User::where('role', 'teacher')->get();
        $classrooms = Classroom::all();

        if ($staff->isEmpty()) {
            return;
        }

        // Danh sách loại chi phí
        $expenseTypes = [
            'salary' => 'Lương giáo viên',
            'material' => 'Tài liệu học tập',
            'utility' => 'Tiện ích (điện, nước, internet)',
            'rent' => 'Tiền thuê phòng học',
            'equipment' => 'Thiết bị giảng dạy',
            'marketing' => 'Marketing và quảng cáo',
            'maintenance' => 'Bảo trì và sửa chữa',
            'other' => 'Chi phí khác',
        ];

        // Danh sách mô tả chi phí
        $expenseDescriptions = [
            'salary' => [
                'Lương tháng cho giáo viên tiếng Trung',
                'Thưởng hiệu suất giảng dạy',
                'Lương phụ trợ giảng viên',
                'Phụ cấp chuyên môn cho giáo viên',
                'Lương tháng cho nhân viên hành chính',
            ],
            'material' => [
                'Mua sách giáo khoa tiếng Trung',
                'In ấn tài liệu học tập',
                'Mua dụng cụ học tập',
                'Tài liệu tham khảo cho giáo viên',
                'Phần mềm học tiếng Trung',
            ],
            'utility' => [
                'Tiền điện tháng',
                'Tiền nước tháng',
                'Phí internet và wifi',
                'Tiền điện thoại văn phòng',
                'Phí vệ sinh và dọn dẹp',
            ],
            'rent' => [
                'Tiền thuê phòng học tháng',
                'Tiền thuê văn phòng',
                'Phí dịch vụ chung cư',
                'Tiền thuê kho chứa tài liệu',
                'Phí bảo hiểm tài sản',
            ],
            'equipment' => [
                'Mua máy chiếu mới',
                'Sửa chữa máy tính',
                'Mua bàn ghế học tập',
                'Thiết bị âm thanh phòng học',
                'Mua máy in và máy photocopy',
            ],
            'marketing' => [
                'Quảng cáo trên mạng xã hội',
                'In tờ rơi quảng cáo',
                'Chi phí website và SEO',
                'Tham gia hội chợ giáo dục',
                'Quảng cáo trên báo chí',
            ],
            'maintenance' => [
                'Sửa chữa điều hòa',
                'Bảo trì hệ thống điện',
                'Sửa chữa cửa ra vào',
                'Bảo trì máy móc thiết bị',
                'Sơn sửa phòng học',
            ],
            'other' => [
                'Phí pháp lý và đăng ký kinh doanh',
                'Bảo hiểm trách nhiệm',
                'Chi phí đào tạo nhân viên',
                'Phí ngân hàng và giao dịch',
                'Chi phí đi lại và công tác',
            ],
        ];

        // Tạo 15-20 chi phí
        $expenseCount = rand(15, 20);

        for ($i = 0; $i < $expenseCount; $i++) {
            $staffMember = $staff->random();
            $classroom = $classrooms->isNotEmpty() ? $classrooms->random() : null;
            $expenseType = $faker->randomElement(array_keys($expenseTypes));
            $description = $faker->randomElement($expenseDescriptions[$expenseType]);

            // Tạo số tiền hợp lý theo loại chi phí
            $amount = $this->generateAmount($expenseType);

            // Tạo ngày chi phí
            $spentAt = $faker->dateTimeBetween('-6 months', 'now');

            Expense::create([
                'staff_id' => $staffMember->id,
                'class_id' => $classroom ? $classroom->id : null,
                'amount' => $amount,
                'type' => $expenseType,
                'note' => $description.($faker->optional(0.6)->sentence() ? ' - '.$faker->sentence() : ''),
                'spent_at' => $spentAt,
            ]);
        }
    }

    /**
     * Tạo số tiền hợp lý theo loại chi phí
     */
    private function generateAmount($expenseType)
    {
        switch ($expenseType) {
            case 'salary':
                return rand(5000000, 15000000); // 5-15 triệu VND
            case 'material':
                return rand(500000, 2000000); // 500k-2 triệu VND
            case 'utility':
                return rand(1000000, 3000000); // 1-3 triệu VND
            case 'rent':
                return rand(10000000, 30000000); // 10-30 triệu VND
            case 'equipment':
                return rand(2000000, 10000000); // 2-10 triệu VND
            case 'marketing':
                return rand(1000000, 5000000); // 1-5 triệu VND
            case 'maintenance':
                return rand(500000, 3000000); // 500k-3 triệu VND
            case 'other':
                return rand(500000, 2000000); // 500k-2 triệu VND
            default:
                return rand(1000000, 5000000); // 1-5 triệu VND
        }
    }
}
