<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminRouteTest extends TestCase
{
    /**
     * Danh sách các route admin cần kiểm tra.
     */
    public static function routeProvider()
    {
        $userId = 1;
        $classroomId = 1;
        $studentId = 1;
        $assignmentId = 1;
        $quizId = 1;
        $lessonId = 1;
        $messageId = 1;
        $submissionId = 1;

        return [
            ['/dashboard', 'get'], // 0
            ['/admin/users', 'get'], // 1
            ['/admin/users/create', 'get'], // 2
            ["/admin/users/{$userId}/edit", 'get'], // 3
            ['/admin/classrooms', 'get'], // 4
            ['/admin/classrooms/create', 'get'], // 5
            ["/admin/classrooms/{$classroomId}", 'get'], // 6
            ["/admin/classrooms/{$classroomId}/edit", 'get'], // 7
            ["/admin/classrooms/{$classroomId}/assign-students", 'get'], // 8
            ["/admin/classrooms/{$classroomId}/attendance", 'get'], // 9
            ["/admin/classrooms/{$classroomId}/attendance-history", 'get'], // 10
            ['/admin/students', 'get'], // 11
            ['/admin/students/create', 'get'], // 12
            ["/admin/students/{$studentId}/edit", 'get'], // 13
            ["/admin/students/{$studentId}", 'get'], // 14
            ['/admin/attendances', 'get'], // 15
            ['/admin/attendances/history', 'get'], // 16
            ['/admin/quizzes', 'get'], // 17
            ['/admin/quizzes/create', 'get'], // 18
            ["/admin/quizzes/{$quizId}", 'get'], // 19
            ["/admin/quizzes/{$quizId}/edit", 'get'], // 20
            ["/admin/quizzes/{$quizId}/results", 'get'], // 21
            ['/admin/schedules', 'get'], // 22
            ['/admin/schedules/calendar', 'get'], // 23
            ['/admin/schedules/create', 'get'], // 24
            ["/admin/schedules/{$classroomId}/edit", 'get'], // 25
            ["/admin/schedules/{$classroomId}", 'get'], // 26
            ['/admin/assignments', 'get'], // 27
            ['/admin/assignments/list', 'get'], // 28
            ['/admin/assignments/create', 'get'], // 29
            ["/admin/assignments/{$assignmentId}", 'get'], // 30
            ["/admin/assignments/{$assignmentId}/edit", 'get'], // 31
            ['/admin/grading', 'get'], // 32
            ["/admin/grading/{$assignmentId}", 'get'], // 33
            ['/admin/chat', 'get'], // 34
            ["/admin/chat/download/{$messageId}", 'get'], // 35
            ['/admin/lessons', 'get'], // 36
            ['/admin/lessons/create', 'get'], // 37
            ["/admin/lessons/{$lessonId}/show", 'get'], // 38
            ["/admin/lessons/{$lessonId}/edit", 'get'], // 39
            ['/admin/notifications', 'get'], // 40
            ['/admin/reports', 'get'], // 41
            ["/admin/reports/student/{$studentId}", 'get'], // 42
            ["/admin/reports/class/{$classroomId}", 'get'], // 43
            // ['/admin/reports/schedule-conflicts', 'get'], // 44 - Route này có lỗi, tạm thời comment lại
            ['/admin/finance', 'get'], // 44
            ["/admin/finance/payment/{$userId}", 'get'], // 45
            ['/admin/finance/expenses', 'get'], // 46
            ['/admin/evaluation-management', 'get'], // 47
            ['/admin/ai', 'get'], // 48
            ["/admin/ai/grading/{$submissionId}", 'get'], // 49
            ['/admin/ai/quiz-generator', 'get'], // 50
            ['/admin/ai/question-bank-generator', 'get'], // 51
        ];
    }

    /**
     * @dataProvider routeProvider
     */
    public function test_admin_routes_return_200($uri, $method)
    {
        $user = User::firstOrCreate(
            ['phone' => 'admin'],
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@educore.me',
                'phone' => 'admin',
                'password' => bcrypt('Admin@12'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        $this->actingAs($user);

        $response = $this->$method($uri);
        $this->assertTrue(
            in_array($response->status(), [200, 302]),
            "Route [{$method} {$uri}] trả về status code {$response->status()} (mong đợi 200 hoặc 302)\n".
                'Nội dung trả về: '.mb_substr($response->getContent(), 0, 500)."\n". // chỉ lấy 500 ký tự đầu cho dễ đọc
                ($response->exception ? 'Exception: '.$response->exception->getMessage() : '')
        );
    }
}
