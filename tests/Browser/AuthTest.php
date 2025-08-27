<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test đăng nhập thành công với admin
     */
    public function test_admin_login_success(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@educore.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->visit('/login')
                    ->type('email', 'admin@educore.com')
                    ->type('password', 'password')
                    ->press('Đăng nhập')
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Dashboard');
        });
    }

    /**
     * Test đăng nhập thành công với teacher
     */
    public function test_teacher_login_success(): void
    {
        $teacher = User::factory()->teacher()->create([
            'email' => 'teacher@educore.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->visit('/login')
                    ->type('email', 'teacher@educore.com')
                    ->type('password', 'password')
                    ->press('Đăng nhập')
                    ->assertPathIs('/teacher/dashboard')
                    ->assertSee('Dashboard');
        });
    }

    /**
     * Test đăng nhập thành công với student
     */
    public function test_student_login_success(): void
    {
        $student = User::factory()->student()->create([
            'email' => 'student@educore.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->visit('/login')
                    ->type('email', 'student@educore.com')
                    ->type('password', 'password')
                    ->press('Đăng nhập')
                    ->assertPathIs('/student/dashboard')
                    ->assertSee('Dashboard');
        });
    }

    /**
     * Test đăng nhập thất bại với thông tin sai
     */
    public function test_login_failure(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('input[name="email"]', 'wrong@email.com')
                    ->type('input[name="password"]', 'wrongpassword')
                    ->press('Đăng nhập')
                    ->assertSee('Thông tin đăng nhập không chính xác');
        });
    }

    /**
     * Test đăng xuất
     */
    public function test_logout(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'test@educore.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/dashboard')
                    ->click('@logout-button')
                    ->assertPathIs('/login');
        });
    }
} 