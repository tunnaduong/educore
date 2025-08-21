<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class RouteStatusTest extends TestCase
{
    /**
     * Danh sách các route cần kiểm tra.
     */
    public static function routeProvider()
    {
        $userId = 1;
        $classroomId = 1;
        $studentId = 1;
        $assignmentId = 1;

        return [
            ['/', 'get', false], // 0
            ['/login', 'get', false], // 1
            // --- Các route cần đăng nhập với role admin ---
            ['/dashboard', 'get', true], // 2
            ['/admin/users', 'get', true], // 3
            ['/admin/users/create', 'get', true], // 4
            ["/admin/users/{$userId}/edit", 'get', true], // 5
            ['/admin/classrooms', 'get', true], // 6
            ['/admin/classrooms/create', 'get', true], // 7
            ["/admin/classrooms/{$classroomId}", 'get', true], // 8
            ["/admin/classrooms/{$classroomId}/edit", 'get', true], // 9
            ["/admin/classrooms/{$classroomId}/assign-students", 'get', true], // 10
            ["/admin/classrooms/{$classroomId}/attendance", 'get', true], // 11
            ["/admin/classrooms/{$classroomId}/attendance-history", 'get', true], // 12
            ['/admin/students', 'get', true], // 13
            ['/admin/students/create', 'get', true], // 14
            ["/admin/students/{$studentId}/edit", 'get', true], // 15
            ["/admin/students/{$studentId}", 'get', true], // 16
            ['/admin/attendances', 'get', true], // 17
            ['/admin/attendances/history', 'get', true], // 18
            ['/admin/schedules', 'get', true], // 19
            ['/admin/schedules/create', 'get', true], // 20
            ["/admin/schedules/{$classroomId}/edit", 'get', true], // 21
            ["/admin/schedules/{$classroomId}", 'get', true], // 22
            // --- Các route cho admin, teacher ---
            ['/admin/assignments/create', 'get', true], // 23
            ['/admin/assignments', 'get', true], // 24 (có thể là overview/list tuỳ mục đích)
            ["/admin/assignments/{$assignmentId}", 'get', true], // 25
            ["/admin/assignments/{$assignmentId}/edit", 'get', true], // 26
        ];
    }

    /**
     * @dataProvider routeProvider
     */
    public function test_routes_return_200($uri, $method, $needLogin)
    {
        if ($needLogin) {
            $user = User::firstOrCreate(
                ['phone' => '0707006421'],
                [
                    'id' => 1,
                    'name' => 'Dương Tùng Anh',
                    'email' => 'tunnaduong@gmail.com',
                    'phone' => '0707006421',
                    'password' => bcrypt('tunganh2003'),
                    'role' => 'admin',
                ]
            );
            $this->actingAs($user);
        }
        $response = $this->$method($uri);
        $this->assertTrue(
            in_array($response->status(), [200, 302]),
            "Route [{$method} {$uri}] trả về status code {$response->status()} (mong đợi 200 hoặc 302)\n".
            'Nội dung trả về: '.mb_substr($response->getContent(), 0, 500)."\n". // chỉ lấy 500 ký tự đầu cho dễ đọc
            ($response->exception ? 'Exception: '.$response->exception->getMessage() : '')
        );
    }
}
