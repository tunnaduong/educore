<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Livewire\Auth\Login;
use Livewire\Livewire;
use Tests\TestCase;

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
