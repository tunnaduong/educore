<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Admin\Auth\Login;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_login_component_renders_properly(): void
    {
        Livewire::test(Login::class)
            ->assertOk()
            ->assertSee('Đăng nhập');
    }
}
