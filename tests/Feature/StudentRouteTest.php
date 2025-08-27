<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\User;
use Tests\TestCase;

class StudentRouteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Tạo classroom trước
        $classroom = Classroom::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Test Classroom',
                'description' => 'Test Description',
                'is_active' => true,
            ]
        );

        // Tạo dữ liệu test cơ bản
        $assignment = Assignment::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'Test Assignment',
                'description' => 'Test Description',
                'due_date' => now()->addDays(7),
                'is_active' => true,
                'classroom_id' => $classroom->id,
            ]
        );

        $lesson = Lesson::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'Test Lesson',
                'content' => 'Test Content',
                'is_active' => true,
            ]
        );

        // Tạo quiz
        $quiz = Quiz::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'Test Quiz',
                'description' => 'Test Quiz Description',
                'is_active' => true,
                'classroom_id' => $classroom->id,
            ]
        );

        // Tạo student profile
        $student = Student::firstOrCreate(
            ['user_id' => 3],
            [
                'student_id' => 'STU001',
                'status' => 'active',
                'is_active' => true,
            ]
        );

        // Gán assignment vào classroom
        $assignment->update(['classroom_id' => $classroom->id]);

        // Gán student vào classroom
        $classroom->users()->syncWithoutDetaching([
            3 => ['role' => 'student'],
        ]);
    }

    public static function routeProvider()
    {
        $lessonId = 1;
        $assignmentId = 1;
        $quizId = 1;
        $messageId = 1;

        return [
            ['/dashboard', 'get'], // 0
            ['/student/lessons', 'get'], // 1
            ["/student/lessons/{$lessonId}", 'get'], // 2
            ['/student/assignments', 'get'], // 3
            ['/student/assignments/submissions', 'get'], // 4
            ["/student/assignments/{$assignmentId}", 'get'], // 5
            ["/student/assignments/{$assignmentId}/submit", 'get'], // 6
            ["/student/quizzes/{$quizId}/do", 'get'], // 7
            ['/student/quizzes', 'get'], // 8
            ['/student/notifications', 'get'], // 9
            ['/student/reports', 'get'], // 10
            ['/student/schedules', 'get'], // 11
            ['/student/chat', 'get'], // 12
            ["/student/chat/download/{$messageId}", 'get'], // 13
            ['/student/evaluation', 'get'], // 14
        ];
    }

    /**
     * @dataProvider routeProvider
     */
    public function test_student_routes_return_200($uri, $method)
    {
        $this->actingAs($this->user);

        $response = $this->$method($uri);
        $this->assertTrue(
            in_array($response->status(), [200, 302]),
            "Route [{$method} {$uri}] trả về status code {$response->status()} (mong đợi 200 hoặc 302)"
        );
    }
}
