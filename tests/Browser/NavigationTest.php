<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NavigationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test navigation menu cho admin
     */
    public function test_admin_navigation(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/dashboard')
                    ->assertSee('Dashboard')
                    ->click('@users-menu')
                    ->assertSee('Quản lý người dùng')
                    ->click('@classrooms-menu')
                    ->assertSee('Quản lý lớp học')
                    ->click('@students-menu')
                    ->assertSee('Quản lý học sinh')
                    ->click('@reports-menu')
                    ->assertSee('Báo cáo')
                    ->click('@finance-menu')
                    ->assertSee('Quản lý tài chính');
        });
    }

    /**
     * Test navigation menu cho teacher
     */
    public function test_teacher_navigation(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher'
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                    ->visit('/teacher/dashboard')
                    ->assertSee('Dashboard')
                    ->click('@lessons-menu')
                    ->assertSee('Quản lý bài học')
                    ->click('@assignments-menu')
                    ->assertSee('Quản lý bài tập')
                    ->click('@attendance-menu')
                    ->assertSee('Điểm danh')
                    ->click('@grading-menu')
                    ->assertSee('Chấm điểm')
                    ->click('@quizzes-menu')
                    ->assertSee('Quản lý Quiz');
        });
    }

    /**
     * Test navigation menu cho student
     */
    public function test_student_navigation(): void
    {
        $student = User::factory()->create([
            'role' => 'student'
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                    ->visit('/student/dashboard')
                    ->assertSee('Dashboard')
                    ->click('@lessons-menu')
                    ->assertSee('Bài học')
                    ->click('@assignments-menu')
                    ->assertSee('Bài tập')
                    ->click('@schedules-menu')
                    ->assertSee('Lịch học')
                    ->click('@reports-menu')
                    ->assertSee('Báo cáo học tập')
                    ->click('@chat-menu')
                    ->assertSee('Chat');
        });
    }

    /**
     * Test responsive design
     */
    public function test_responsive_design(): void
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            // Test desktop view
            $browser->loginAs($user)
                    ->visit('/admin/dashboard')
                    ->resize(1920, 1080)
                    ->assertVisible('@desktop-menu');

            // Test tablet view
            $browser->resize(768, 1024)
                    ->assertVisible('@tablet-menu');

            // Test mobile view
            $browser->resize(375, 667)
                    ->assertVisible('@mobile-menu')
                    ->click('@mobile-menu-toggle')
                    ->assertVisible('@mobile-nav');
        });
    }

    /**
     * Test breadcrumb navigation
     */
    public function test_breadcrumb_navigation(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/users/create')
                    ->assertSee('Dashboard')
                    ->assertSee('Người dùng')
                    ->assertSee('Tạo mới')
                    ->click('@breadcrumb-dashboard')
                    ->assertPathIs('/admin/dashboard');
        });
    }

    /**
     * Test search functionality
     */
    public function test_search_functionality(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/users')
                    ->type('@search-input', 'admin')
                    ->press('@search-button')
                    ->assertSee('admin@educore.com');
        });
    }

    /**
     * Test pagination
     */
    public function test_pagination(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/users')
                    ->assertSee('Danh sách người dùng')
                    ->click('@pagination-next')
                    ->assertSee('Trang 2')
                    ->click('@pagination-prev')
                    ->assertSee('Trang 1');
        });
    }

    /**
     * Test modal dialogs
     */
    public function test_modal_dialogs(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/users')
                    ->click('@delete-user-btn')
                    ->assertVisible('@confirm-modal')
                    ->assertSee('Bạn có chắc chắn muốn xóa?')
                    ->click('@cancel-btn')
                    ->assertNotVisible('@confirm-modal');
        });
    }
} 