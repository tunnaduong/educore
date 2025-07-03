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
            ['/', 'get', false], // 1
            ['/login', 'get', false], // 2
            // --- Các route cần đăng nhập với role admin ---
            ['/dashboard', 'get', true], // 3
            ['/users', 'get', true], // 4
            ['/users/create', 'get', true], // 5
            ["/users/{$userId}/edit", 'get', true], // 6
            ['/classrooms', 'get', true], // 7
            ['/classrooms/create', 'get', true], // 8
            ["/classrooms/{$classroomId}", 'get', true], // 9
            ["/classrooms/{$classroomId}/edit", 'get', true], // 10
            ["/classrooms/{$classroomId}/assign-students", 'get', true], // 11
            ["/classrooms/{$classroomId}/attendance", 'get', true],
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
            ['/assignments/create', 'get', true],
            ['/assignments', 'get', true],
            ["/assignments/{$assignmentId}", 'get', true],
            ["/assignments/{$assignmentId}/edit", 'get', true],
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
                    'name' => 'Admin Test',
                    'phone' => '0707006421',
                    'email' => 'admin_test_' . uniqid() . '@example.com',
                    'password' => bcrypt('tunganh2003'),
                    'role' => 'admin',
                    'is_active' => 1,
                ]
            );
            $this->actingAs($user);
        }
        $response = $this->$method($uri);
        $this->assertTrue(in_array($response->status(), [200, 302]));
    }
}
