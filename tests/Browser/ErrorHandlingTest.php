<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ErrorHandlingTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test validation errors khi tạo user
     */
    public function test_user_validation_errors(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/users/create')
                ->press('Tạo người dùng')
                ->assertSee('Tên là bắt buộc')
                ->assertSee('Email là bắt buộc')
                ->assertSee('Mật khẩu là bắt buộc');
        });
    }

    /**
     * Test validation errors khi tạo classroom
     */
    public function test_classroom_validation_errors(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/classrooms/create')
                ->press('Tạo lớp học')
                ->assertSee('Tên lớp là bắt buộc')
                ->assertSee('Giáo viên là bắt buộc');
        });
    }

    /**
     * Test 404 error page
     */
    public function test_404_error_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/non-existent-page')
                ->assertSee('404')
                ->assertSee('Không tìm thấy trang')
                ->click('@back-home-btn')
                ->assertPathIs('/');
        });
    }

    /**
     * Test 403 forbidden error
     */
    public function test_403_forbidden_error(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                ->visit('/admin/dashboard')
                ->assertSee('403')
                ->assertSee('Không có quyền truy cập');
        });
    }

    /**
     * Test form validation với email không hợp lệ
     */
    public function test_invalid_email_validation(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/users/create')
                ->type('name', 'Test User')
                ->type('email', 'invalid-email')
                ->type('password', 'password123')
                ->press('Tạo người dùng')
                ->assertSee('Email không hợp lệ');
        });
    }

    /**
     * Test password confirmation validation
     */
    public function test_password_confirmation_validation(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/users/create')
                ->type('name', 'Test User')
                ->type('email', 'test@educore.com')
                ->type('password', 'password123')
                ->type('password_confirmation', 'different-password')
                ->press('Tạo người dùng')
                ->assertSee('Xác nhận mật khẩu không khớp');
        });
    }

    /**
     * Test file upload validation
     */
    public function test_file_upload_validation(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        // Create a temporary invalid file for upload
        $tmpFile = tempnam(sys_get_temp_dir(), 'invalid_');
        $invalidFile = $tmpFile . '.exe';
        rename($tmpFile, $invalidFile);
        file_put_contents($invalidFile, 'dummy content');

        try {
            $this->browse(function (Browser $browser) use ($student, $invalidFile) {
                $browser->loginAs($student)
                    ->visit('/student/assignments/1/submit')
                    ->type('content', 'Test content')
                    ->attach('file', $invalidFile)
                    ->press('Nộp bài')
                    ->assertSee('File không được hỗ trợ');
            });
        } finally {
            if (file_exists($invalidFile)) {
                unlink($invalidFile);
            }
        }
    /**
     * Test duplicate email validation
     */
    public function test_duplicate_email_validation(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $existingUser = User::factory()->create([
            'email' => 'existing@educore.com',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/users/create')
                ->type('name', 'Test User')
                ->type('email', 'existing@educore.com')
                ->type('password', 'password123')
                ->press('Tạo người dùng')
                ->assertSee('Email đã tồn tại');
        });
    }

    /**
     * Test required field validation
     */
    public function test_required_field_validation(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                ->visit('/teacher/lessons/create')
                ->press('Tạo bài học')
                ->assertSee('Tiêu đề là bắt buộc')
                ->assertSee('Nội dung là bắt buộc')
                ->assertSee('Lớp học là bắt buộc');
        });
    }
}
