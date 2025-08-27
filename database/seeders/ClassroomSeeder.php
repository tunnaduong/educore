<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy teachers và students từ cache
        $teachers = cache('teachers', []);
        $students = cache('students', []);

        if (empty($teachers) || empty($students)) {
            return;
        }

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