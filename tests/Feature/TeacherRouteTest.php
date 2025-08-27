<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Classroom;
use App\Models\Assignment;
use App\Models\Lesson;
use App\Models\Quiz;
use Tests\TestCase;

class TeacherRouteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo dữ liệu test cơ bản
        $classroom = Classroom::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Test Classroom',
                'description' => 'Test Description',
                'is_active' => true,
            ]
        );
        
        $assignment = Assignment::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'Test Assignment',
                'description' => 'Test Description',
                'due_date' => now()->addDays(7),
                'is_active' => true,
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
        
        // Gán teacher vào classroom
        $teacher = User::firstOrCreate(
            ['phone' => 'teacher'],
            [
                'id' => 2,
                'name' => 'Teacher',
                'email' => 'teacher@educore.me',
                'phone' => 'teacher',
                'password' => bcrypt('Teacher@12'),
                'role' => 'teacher',
                'is_active' => true,
            ]
        );
        
        // Gán teacher vào classroom
        $classroom->users()->syncWithoutDetaching([
            $teacher->id => ['role' => 'teacher']
        ]);
        
        // Tạo quiz
        $quiz = Quiz::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'Test Quiz',
                'description' => 'Test Quiz Description',
                'is_active' => true,
            ]
        );
    }

    public static function routeProvider()
    {
        $classroomId = 1;
        $quizId = 1;
        $assignmentId = 1;
        $lessonId = 1;
        $messageId = 1;
        $submissionId = 1;

        return [
            ['/dashboard', 'get'], // 0
            ['/teacher/my-class', 'get'], // 1
            ["/teacher/my-class/{$classroomId}", 'get'], // 2
            ['/teacher/quizzes', 'get'], // 3
            ['/teacher/quizzes/create', 'get'], // 4
            ["/teacher/quizzes/{$quizId}", 'get'], // 5
            ["/teacher/quizzes/{$quizId}/edit", 'get'], // 6
            ["/teacher/quizzes/{$quizId}/results", 'get'], // 7
            ['/teacher/assignments', 'get'], // 8
            ['/teacher/assignments/create', 'get'], // 9
            ["/teacher/assignments/{$assignmentId}/edit", 'get'], // 10
            ["/teacher/assignments/{$assignmentId}", 'get'], // 11
            ['/teacher/grading', 'get'], // 12
            ["/teacher/grading/{$assignmentId}", 'get'], // 13
            ['/teacher/lessons', 'get'], // 14
            ['/teacher/lessons/create', 'get'], // 15
            ["/teacher/lessons/{$lessonId}", 'get'], // 16
            ["/teacher/lessons/{$lessonId}/edit", 'get'], // 17
            ['/teacher/notifications', 'get'], // 18
            ['/teacher/attendance', 'get'], // 19
            ['/teacher/attendance/history', 'get'], // 20
            ["/teacher/attendance/{$classroomId}/take", 'get'], // 21
            ["/teacher/attendance/{$classroomId}/attendance-history", 'get'], // 22
            ['/teacher/schedules', 'get'], // 23
            ['/teacher/chat', 'get'], // 24
            ["/teacher/chat/download/{$messageId}", 'get'], // 25
            ['/teacher/chat/test', 'get'], // 26
            ['/teacher/ai', 'get'], // 27
            ["/teacher/ai/grading/{$submissionId}", 'get'], // 28
            ['/teacher/ai/quiz-generator', 'get'], // 29
            ['/teacher/ai/question-bank-generator', 'get'], // 30
            ['/teacher/reports', 'get'], // 31
            ['/teacher/evaluations', 'get'], // 32
        ];
    }

    /**
     * @dataProvider routeProvider
     */
    public function test_teacher_routes_return_200($uri, $method)
    {
        $user = User::firstOrCreate(
            ['phone' => 'teacher'],
            [
                'id' => 2,
                'name' => 'Teacher',
                'email' => 'teacher@educore.me',
                'phone' => 'teacher',
                'password' => bcrypt('Teacher@12'),
                'role' => 'teacher',
                'is_active' => true,
            ]
        );
        $this->actingAs($user);

        $response = $this->$method($uri);
        $this->assertTrue(
            in_array($response->status(), [200, 302]),
            "Route [{$method} {$uri}] trả về status code {$response->status()} (mong đợi 200 hoặc 302)"
        );
    }
}
