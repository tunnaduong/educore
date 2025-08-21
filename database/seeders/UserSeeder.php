<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Admin
        User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '0987654321',
            'password' => bcrypt('Admin@12'),
            'role' => 'admin',
            'is_active' => true,
        ]);

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
                'email' => strtolower(str_replace(' ', '', $name)).'@educore.test',
                'phone' => '09'.str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'is_active' => true,
            ]);
            $teachers[] = $teacher;
        }

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
                'email' => strtolower(str_replace(' ', '', $name)).'@educore.test',
                'phone' => '09'.str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'password' => bcrypt('password'),
                'role' => 'student',
                'is_active' => true,
            ]);

            // Tạo profile học viên
            Student::create([
                'user_id' => $student->id,
                'status' => $faker->randomElement(['active', 'inactive', 'graduated']),
                'joined_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);

            $students[] = $student;
        }

        // Tạo các lớp học và gán giáo viên, học viên
        $this->createClassrooms($teachers, $students);
    }

    private function createClassrooms($teachers, $students)
    {
        $levels = ['HSK 1', 'HSK 2', 'HSK 3', 'HSK 4', 'HSK 5', 'HSK 6'];
        $schedules = [
            ['days' => ['Monday', 'Wednesday', 'Friday'], 'time' => '19:00 - 20:30'],
            ['days' => ['Tuesday', 'Thursday'], 'time' => '18:00 - 19:30'],
            ['days' => ['Monday', 'Friday'], 'time' => '20:00 - 21:30'],
            ['days' => ['Wednesday', 'Saturday'], 'time' => '09:00 - 10:30'],
            ['days' => ['Tuesday', 'Thursday', 'Saturday'], 'time' => '14:00 - 15:30'],
            ['days' => ['Monday', 'Wednesday'], 'time' => '16:00 - 17:30'],
            ['days' => ['Friday', 'Sunday'], 'time' => '10:00 - 11:30'],
            ['days' => ['Tuesday', 'Friday'], 'time' => '20:30 - 22:00'],
        ];

        $classNames = [
            'HSK 1 - Lớp cơ bản buổi tối',
            'HSK 1 - Lớp cơ bản buổi chiều',
            'HSK 2 - Lớp trung cấp 1',
            'HSK 2 - Lớp trung cấp 2',
            'HSK 3 - Lớp nâng cao 1',
            'HSK 3 - Lớp nâng cao 2',
            'HSK 4 - Lớp chuyên sâu',
            'HSK 5 - Lớp cao cấp',
        ];

        for ($i = 0; $i < 8; $i++) {
            $class = Classroom::create([
                'name' => $classNames[$i],
                'level' => $levels[$i % 6],
                'schedule' => $schedules[$i],
                'notes' => 'Lớp học tiếng Trung chất lượng cao',
                'status' => 'active',
            ]);

            // Gán giáo viên
            $teacher = $teachers[$i % count($teachers)];
            $class->users()->attach($teacher->id, ['role' => 'teacher']);

            // Gán học viên (3-5 học viên mỗi lớp)
            $classStudents = array_slice($students, $i * 3, rand(3, 5));
            foreach ($classStudents as $student) {
                $class->users()->attach($student->id, ['role' => 'student']);
            }
        }
    }
}
