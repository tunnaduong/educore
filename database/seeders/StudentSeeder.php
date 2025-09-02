<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Tạo 25 học viên với tên tiếng Việt thực tế
        $studentNames = [
            'Nguyễn Văn An',
            'Trần Thị Bình',
            'Lê Hoàng Cường',
            'Phạm Thị Dung',
            'Hoàng Văn Em',
            'Vũ Thị Phương',
            'Đỗ Minh Giang',
            'Ngô Thị Hà',
            'Bùi Văn Hùng',
            'Lý Thị Kim',
            'Đinh Văn Long',
            'Tô Thị Mai',
            'Hồ Văn Nam',
            'Dương Thị Ngọc',
            'Võ Minh Phúc',
            'Lưu Thị Quỳnh',
            'Trịnh Văn Sơn',
            'Nguyễn Thị Thanh',
            'Lê Văn Tuấn',
            'Phan Thị Uyên',
            'Hoàng Văn Việt',
            'Trần Thị Xuân',
            'Vũ Minh Yến',
            'Đỗ Thị Thúy',
            'Ngô Văn Bảo',
        ];

        $students = [];
        foreach ($studentNames as $index => $name) {
            $student = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '', $name)) . '@educore.test',
                'phone' => '09' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'password' => bcrypt('password'),
                'role' => 'student',
                'is_active' => true,
            ]);

            // Tạo profile học viên
            Student::create([
                'user_id' => $student->id,
                'status' => $faker->randomElement(['active', 'dropped', 'paused', 'new']),
                'joined_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);

            $students[] = $student;
        }

        // Lưu students vào cache để các seeder khác có thể sử dụng
        cache(['students' => $students], 3600);

        // Tạo tài khoản mặc định: student / 123123
        $defaultStudent = User::firstOrCreate(
            ['email' => 'student@educore.test'],
            [
                'name' => 'Student',
                'phone' => '0900000000',
                'password' => bcrypt('123123'),
                'role' => 'student',
                'is_active' => true,
            ]
        );

        // Đảm bảo có hồ sơ Student tương ứng
        Student::firstOrCreate(
            ['user_id' => $defaultStudent->id],
            [
                'status' => 'active',
                'joined_at' => now(),
            ]
        );
    }
}
