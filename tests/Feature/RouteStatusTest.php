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
        $classroomId = 3;
        $studentId = 1;
        $assignmentId = 1;
        return [
            ['/', 'get', false],
            ['/login', 'get', false],
            // --- Các route cần đăng nhập với role admin ---
            ['/dashboard', 'get', true],
            ['/users', 'get', true],
            ['/users/create', 'get', true],
            ["/users/{$userId}/edit", 'get', true],
            ['/classrooms', 'get', true],
            ['/classrooms/create', 'get', true],
            ["/classrooms/{$classroomId}", 'get', true],
            ["/classrooms/{$classroomId}/edit", 'get', true],
            ["/classrooms/{$classroomId}/assign-students", 'get', true],
            ["/classrooms/{$classroomId}/attendance", 'get', true],
            ["/classrooms/{$classroomId}/attendance-history", 'get', true],
            ['/students', 'get', true],
            ['/students/create', 'get', true],
            ["/students/{$studentId}/edit", 'get', true],
            ["/students/{$studentId}", 'get', true],
            ['/attendances', 'get', true],
            ['/attendances/history', 'get', true],
            ['/schedules', 'get', true],
            ['/schedules/create', 'get', true],
            ["/schedules/{$classroomId}/edit", 'get', true],
            ["/schedules/{$classroomId}", 'get', true],
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
