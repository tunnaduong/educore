<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
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

        // Tạo dữ liệu điểm danh cho 3 tuần gần đây
        $startDate = Carbon::now()->subWeeks(3)->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();

        foreach ($classrooms as $classroom) {
            $schedule = $classroom->schedule;
            $days = $schedule['days'] ?? ['Monday', 'Wednesday', 'Friday'];

            // Lấy học viên của lớp
            $students = $classroom->students()->with('studentProfile')->get();

            if ($students->isEmpty()) {
                continue;
            }

            // Tạo điểm danh cho mỗi ngày học trong 3 tuần
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $dayName = $currentDate->format('l'); // Monday, Tuesday, etc.

                // Kiểm tra xem ngày này có phải là ngày học không
                if (in_array($dayName, $days)) {
                    foreach ($students as $student) {
                        // Tỷ lệ có mặt: 85-95%
                        $attendanceRate = $faker->numberBetween(85, 95);
                        $random = $faker->numberBetween(1, 100);

                        if ($random <= $attendanceRate) {
                            // Có mặt
                            $status = $faker->randomElement(['present', 'late']);
                            $notes = $status === 'late' ? 'Đến muộn 10 phút' : null;
                        } else {
                            // Vắng mặt
                            $status = 'absent';
                            $notes = $faker->optional(0.7)->randomElement([
                                'Bị ốm',
                                'Có việc gia đình',
                                'Đi công tác',
                                'Gặp sự cố giao thông',
                                'Có hẹn khám bệnh',
                                null
                            ]);
                        }

                        // Tạo thời gian điểm danh
                        $attendanceTime = $currentDate->copy();
                        if ($status === 'late') {
                            $attendanceTime->addMinutes($faker->numberBetween(5, 20));
                        } else {
                            $attendanceTime->addMinutes($faker->numberBetween(-10, 5));
                        }

                        Attendance::create([
                            'class_id' => $classroom->id,
                            'student_id' => $student->studentProfile->id,
                            'date' => $currentDate->format('Y-m-d'),
                            'status' => $status,
                            'notes' => $notes,
                            'recorded_at' => $attendanceTime,
                            'recorded_by' => $classroom->getFirstTeacher()->id ?? 1,
                        ]);
                    }
                }

                $currentDate->addDay();
            }
        }
    }
}