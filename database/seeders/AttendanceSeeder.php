<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $classroom = Classroom::where('name', 'HSK2')->first();
        
        if (!$classroom) {
            echo "Classroom HSK2 not found\n";
            return;
        }

        $students = $classroom->students;
        echo "Found " . $students->count() . " students in HSK2\n";

        foreach ($students as $student) {
            $studentRecord = Student::where('user_id', $student->id)->first();
            
            if ($studentRecord) {
                // Tạo dữ liệu điểm danh cho tháng 9/2025
                Attendance::create([
                    'class_id' => $classroom->id,
                    'student_id' => $studentRecord->id,
                    'date' => '2025-09-15',
                    'present' => true,
                ]);

                Attendance::create([
                    'class_id' => $classroom->id,
                    'student_id' => $studentRecord->id,
                    'date' => '2025-09-16',
                    'present' => false,
                ]);

                Attendance::create([
                    'class_id' => $classroom->id,
                    'student_id' => $studentRecord->id,
                    'date' => '2025-09-17',
                    'present' => true,
                ]);

                echo "Created attendance records for: " . $student->name . "\n";
            }
        }

        echo "Total attendance records: " . Attendance::count() . "\n";
    }
}
