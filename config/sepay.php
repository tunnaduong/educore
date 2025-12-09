<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SEPay Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình cho SEPay webhook integration
    | Package: sepayvn/laravel-sepay
    |
    */

    'webhook_token' => env('SEPAY_WEBHOOK_TOKEN'),

    'pattern' => env('SEPAY_MATCH_PATTERN', 'SE'),

    /*
    |--------------------------------------------------------------------------
    | Bank Account Information
    |--------------------------------------------------------------------------
    |
    | Thông tin tài khoản ngân hàng để hiển thị trên trang upgrade
    |
    */

    'bank_account' => [
        'account_number' => env('SEPAY_BANK_ACCOUNT_NUMBER', ''),
        'account_name' => env('SEPAY_BANK_ACCOUNT_NAME', ''),
        'bank_name' => env('SEPAY_BANK_NAME', ''),
    ],

];
