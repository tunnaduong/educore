<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 8 giáo viên với tên tiếng Việt thực tế
        $teacherNames = [
            'Đinh Đăng Hùng',
            'Nguyễn Thị Mai',
            'Trần Văn Nam',
            'Lê Thị Hương',
            'Phạm Văn Tuấn',
            'Hoàng Thị Lan',
            'Vũ Đình Quang',
            'Đỗ Thị Thảo',
        ];

        $teachers = [];
        foreach ($teacherNames as $index => $name) {
            $teacher = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '', $name)) . '@educore.test',
                'phone' => '09' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'is_active' => true,
            ]);
            $teachers[] = $teacher;
        }

        // Lưu teachers vào cache để các seeder khác có thể sử dụng
        cache(['teachers' => $teachers], 3600);
    }
}