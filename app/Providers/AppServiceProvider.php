<?php

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Livewire\Admin\Students\AttendanceStats;

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
    }
}
