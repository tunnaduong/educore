<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

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
            ['/users', 'get', true], // 3
            ['/users/create', 'get', true], // 4
            ["/users/{$userId}/edit", 'get', true], // 5
            ['/classrooms', 'get', true], // 6
            ['/classrooms/create', 'get', true], // 7
            ["/classrooms/{$classroomId}", 'get', true], // 8
            ["/classrooms/{$classroomId}/edit", 'get', true], // 9
            ["/classrooms/{$classroomId}/assign-students", 'get', true], // 10
            ["/classrooms/{$classroomId}/attendance", 'get', true], // 11
            ["/classrooms/{$classroomId}/attendance-history", 'get', true], // 12
            ['/students', 'get', true], // 13
            ['/students/create', 'get', true], // 14
            ["/students/{$studentId}/edit", 'get', true], // 15
            ["/students/{$studentId}", 'get', true], // 16
            ['/attendances', 'get', true], // 17
            ['/attendances/history', 'get', true], // 18
            ['/schedules', 'get', true], // 19
            ['/schedules/create', 'get', true], // 20
            ["/schedules/{$classroomId}/edit", 'get', true], // 21
            ["/schedules/{$classroomId}", 'get', true], // 22
            // --- Các route cho admin, teacher ---
            ['/assignments/create', 'get', true], // 23
            ['/assignments', 'get', true], // 24
            ["/assignments/{$assignmentId}", 'get', true], // 25
            ["/assignments/{$assignmentId}/edit", 'get', true], // 26
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
            "Route [{$method} {$uri}] trả về status code {$response->status()} (mong đợi 200 hoặc 302)\n" .
            "Nội dung trả về: " . mb_substr($response->getContent(), 0, 500) . "\n" . // chỉ lấy 500 ký tự đầu cho dễ đọc
            ($response->exception ? "Exception: " . $response->exception->getMessage() : "")
        );
    }
}
