<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule: Kiểm tra license hết hạn hàng ngày
Schedule::command('licenses:check-expired')
    ->daily()
    ->at('00:00')
    ->timezone('Asia/Ho_Chi_Minh');
