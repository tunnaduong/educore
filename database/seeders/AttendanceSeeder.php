<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Classroom;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

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
                            $present = true;
                            $reason = null;
                        } else {
                            // Vắng mặt
                            $present = false;
                            $reason = $faker->optional(0.7)->randomElement([
                                'Bị ốm',
                                'Có việc gia đình',
                                'Đi công tác',
                                'Gặp sự cố giao thông',
                                'Có hẹn khám bệnh',
                                null,
                            ]);
                        }

                        Attendance::create([
                            'class_id' => $classroom->id,
                            'student_id' => $student->studentProfile->id,
                            'date' => $currentDate->format('Y-m-d'),
                            'present' => $present,
                            'reason' => $reason,
                        ]);
                    }
                }

                $currentDate->addDay();
            }
        }
    }
}
