<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BasicTest extends DuskTestCase
{
    /**
     * Test cơ bản để kiểm tra Dusk hoạt động
     */
    public function test_basic_functionality(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('EduCore')
                    ->screenshot('home-page');
        });
    }

    /**
     * Test tạo user đơn giản
     */
    public function test_create_simple_user(): void
    {
        $this->browse(function (Browser $browser) {
            // Test tạo user trong database
            $user = \App\Models\User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@educore.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);

            $this->assertDatabaseHas('users', [
                'email' => 'test@educore.com',
                'role' => 'admin'
            ]);

            $browser->visit('/')
                    ->assertSee('EduCore');
        });
    }

    /**
     * Test login với dữ liệu từ seeder
     */
    public function test_login_with_seeded_data(): void
    {
        $this->browse(function (Browser $browser) {
            // Kiểm tra dữ liệu từ seeder
            $this->assertDatabaseHas('users', [
                'email' => 'admin@educore.com',
                'role' => 'admin'
            ]);

            $this->assertDatabaseHas('users', [
                'email' => 'teacher@educore.com',
                'role' => 'teacher'
            ]);

            $this->assertDatabaseHas('users', [
                'email' => 'student@educore.com',
                'role' => 'student'
            ]);

            $browser->visit('/login')
                    ->assertPresent('input[name="email"]')
                    ->assertPresent('input[name="password"]');
        });
    }

    /**
     * Test login form có tồn tại
     */
    public function test_login_form_exists(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertPresent('input[name="email"]')
                    ->assertPresent('input[name="password"]')
                    ->screenshot('login-page');
        });
    }
} 