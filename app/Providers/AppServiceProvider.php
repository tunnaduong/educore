<?php

namespace App\Providers;

use App\Livewire\Admin\Students\AttendanceStats;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('components.attendance-stats', AttendanceStats::class);

        // Cấu hình pagination sử dụng Bootstrap
        Paginator::useBootstrapFive();

        // Đăng ký SePayWebhookListener cho Laravel 11
        // (Laravel 11 tự động discover listeners, nhưng đăng ký thủ công để chắc chắn)
        \Illuminate\Support\Facades\Event::listen(
            \SePay\SePay\Events\SePayWebhookEvent::class,
            \App\Listeners\SePayWebhookListener::class,
        );
    }
}
