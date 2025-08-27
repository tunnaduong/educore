<?php

namespace Tests\Browser;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test tạo user mới
     */
    public function test_create_user(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/users/create')
                ->type('name', 'Nguyễn Văn A')
                ->type('email', 'nguyenvana@educore.com')
                ->type('password', 'password123')
                ->select('role', 'teacher')
                ->press('Tạo người dùng')
                ->assertSee('Người dùng đã được tạo thành công');
        });
    }

    /**
     * Test tạo classroom mới
     */
    public function test_create_classroom(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/classrooms/create')
                ->type('name', 'Lớp 10A1')
                ->type('description', 'Lớp chuyên Toán')
                ->select('teacher_id', '1')
                ->press('Tạo lớp học')
                ->assertSee('Lớp học đã được tạo thành công');
        });
    }

    /**
     * Test quản lý students
     */
    public function test_manage_students(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/students')
                ->assertSee('Danh sách học sinh')
                ->click('@add-student-btn')
                ->type('name', 'Trần Thị B')
                ->type('email', 'tranthib@educore.com')
                ->type('phone', '0123456789')
                ->press('Thêm học sinh')
                ->assertSee('Học sinh đã được thêm thành công');
        });
    }

    /**
     * Test xem báo cáo
     */
    public function test_view_reports(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/reports')
                ->assertSee('Báo cáo')
                ->click('@attendance-report')
                ->assertSee('Báo cáo điểm danh');
        });
    }

    /**
     * Test quản lý tài chính
     */
    public function test_finance_management(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/finance')
                ->assertSee('Quản lý tài chính')
                ->click('@add-expense-btn')
                ->type('description', 'Mua sách giáo khoa')
                ->type('amount', '500000')
                ->select('category', 'equipment')
                ->press('Thêm chi phí')
                ->assertSee('Chi phí đã được thêm thành công');
        });
    }
}
