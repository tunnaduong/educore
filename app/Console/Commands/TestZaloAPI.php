<?php

namespace App\Console\Commands;

use App\Services\OneSmsService;
use Exception;
use Illuminate\Console\Command;

class TestZaloAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zalo:test {phone} {--type=otp} {--name=Test User} {--code=HV001} {--class=Test Class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Zalo ZNS API';

    protected OneSmsService $oneSmsService;

    public function __construct(OneSmsService $oneSmsService)
    {
        parent::__construct();
        $this->oneSmsService = $oneSmsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone');
        $type = $this->option('type');
        $name = $this->option('name');
        $code = $this->option('code');
        $class = $this->option('class');

        $this->info("Testing Zalo API for phone: {$phone}");
        $this->info("Type: {$type}");
        $this->info("Name: {$name}");
        $this->info("Code: {$code}");
        $this->info("Class: {$class}");

        try {
            switch ($type) {
                case 'otp':
                    $otp = rand(100000, 999999);
                    $result = $this->oneSmsService->sendOTP($phone, $otp, $name, $code);
                    break;

                case 'schedule':
                    $result = $this->oneSmsService->sendScheduleReminder(
                        $phone,
                        $name,
                        $code,
                        $class,
                        '14:00',
                        now()->format('d/m/Y')
                    );
                    break;

                case 'assignment':
                    $result = $this->oneSmsService->sendAssignmentResult(
                        $phone,
                        $name,
                        $code,
                        'Bài tập 1',
                        now()->format('d/m/Y'),
                        '85',
                        'Làm bài tốt!'
                    );
                    break;

                case 'payment':
                    $result = $this->oneSmsService->sendPaymentConfirmation(
                        $phone,
                        $name,
                        $code,
                        $class,
                        now()->format('d/m/Y'),
                        'TXN'.rand(100000, 999999)
                    );
                    break;

                case 'registration':
                    $result = $this->oneSmsService->sendRegistrationSuccess(
                        $phone,
                        $name,
                        $class,
                        now()->format('d/m/Y'),
                        'REG'.rand(100000, 999999)
                    );
                    break;

                case 'absent':
                    $result = $this->oneSmsService->sendAbsentNotification(
                        $phone,
                        $name,
                        $code,
                        $class,
                        now()->format('d/m/Y')
                    );
                    break;

                case 'late':
                    $result = $this->oneSmsService->sendLateNotification(
                        $phone,
                        $name,
                        $code,
                        $class,
                        now()->format('d/m/Y')
                    );
                    break;

                case 'deadline':
                    $result = $this->oneSmsService->sendAssignmentDeadline(
                        $phone,
                        $name,
                        $code,
                        $class,
                        '23:59',
                        now()->addDay()->format('d/m/Y')
                    );
                    break;

                case 'change':
                    $result = $this->oneSmsService->sendScheduleChange(
                        $phone,
                        $name,
                        $code,
                        $class,
                        '14:00 28/08/2025',
                        '15:00 28/08/2025',
                        'Giáo viên A'
                    );
                    break;

                case 'exam':
                    $result = $this->oneSmsService->sendOnlineExam(
                        $phone,
                        $name,
                        $code,
                        $class,
                        '14:00',
                        now()->format('d/m/Y')
                    );
                    break;

                default:
                    $this->error("Type không hợp lệ: {$type}");
                    $this->info('Các type có sẵn: otp, schedule, assignment, payment, registration, absent, late, deadline, change, exam');

                    return 1;
            }

            if ($result['success']) {
                $this->info('✓ Thành công!');
                $this->info('Message: '.$result['message']);
            } else {
                $this->error('✗ Thất bại!');
                $this->error('Message: '.$result['message']);
            }

            $this->info('Response: '.json_encode($result, JSON_PRETTY_PRINT));
        } catch (Exception $e) {
            $this->error('Lỗi: '.$e->getMessage());
        }
    }
}
