<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSmsService
{
    protected string $endpoint = 'https://zaloapi.conek.vn/SendSMSZalo';
    protected string $username;
    protected string $password;
    protected string $brandname = 'EduCore';

    public function __construct()
    {
        $this->username = config('services.onesms.username');
        $this->password = config('services.onesms.password');
    }

    /**
     * Gửi tin nhắn Zalo ZNS
     */
    public function sendZalo($phone, $templateId, $templateData, $id = null, $options = [])
    {
        try {
            $payload = [
                'id' => $id ?? uniqid('ec_', true),
                'username' => $this->username,
                'password' => $this->password,
                'brandname' => $this->brandname,
                'phone' => $this->formatPhone($phone),
                'template_id' => $templateId,
                'template_data' => $templateData,
                'type_send' => $options['type_send'] ?? 2, // 2 = ZNS
                'resend_sms' => $options['resend_sms'] ?? 1, // 1 = fallback SMS
                'brandname_sms' => $this->brandname,
                'unicode' => $options['unicode'] ?? 0,
                'message_sms' => $options['message_sms'] ?? 'EduCore thông báo',
            ];

            Log::info('OneSmsService: Sending Zalo message', [
                'phone' => $phone,
                'template_id' => $templateId,
                'id' => $payload['id'],
            ]);

            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($this->endpoint, $payload);

            $result = $response->json();

            Log::info('OneSmsService: API response', [
                'phone' => $phone,
                'response' => $result,
                'status' => $response->status(),
            ]);

            return $this->handleResponse($result);
        } catch (Exception $e) {
            Log::error('OneSmsService: Error sending Zalo message', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Lỗi gửi tin nhắn: ' . $e->getMessage(),
                'code' => 'ERROR'
            ];
        }
    }

    /**
     * Gửi OTP qua Zalo
     */
    public function sendOTP($phone, $otp, $id = null)
    {
        return $this->sendZalo(
            $phone,
            config('services.onesms.templates.otp_reset', '123456'),
            ['otp_code' => $otp],
            $id,
            [
                'type_send' => 2,
                'resend_sms' => 1,
                'message_sms' => "Ma OTP cua ban la: {$otp}"
            ]
        );
    }

    /**
     * Gửi thông báo lịch học
     */
    public function sendScheduleReminder($phone, $studentName, $studentCode, $className, $time, $date, $id = null)
    {
        return $this->sendZalo(
            $phone,
            config('services.onesms.templates.schedule_reminder', '123457'),
            [
                'customer_name' => $studentName,
                'mahocvien' => $studentCode,
                'class_name' => $className,
                'time' => $time,
                'date' => $date,
                'language_center' => 'EduCore'
            ],
            $id,
            [
                'type_send' => 2,
                'resend_sms' => 1,
                'message_sms' => "Nhac lich: {$className} luc {$time} ngay {$date}"
            ]
        );
    }

    /**
     * Gửi thông báo kết quả bài tập
     */
    public function sendAssignmentResult($phone, $studentName, $studentCode, $assignmentName, $date, $score, $comment, $id = null)
    {
        return $this->sendZalo(
            $phone,
            config('services.onesms.templates.assignment_result', '123458'),
            [
                'customer_name' => $studentName,
                'mahocvien' => $studentCode,
                'assignment_name' => $assignmentName,
                'date' => $date,
                'score' => $score,
                'comment' => $comment
            ],
            $id,
            [
                'type_send' => 2,
                'resend_sms' => 1,
                'message_sms' => "Ket qua: {$assignmentName} - {$score} diem"
            ]
        );
    }

    /**
     * Gửi xác nhận thanh toán
     */
    public function sendPaymentConfirmation($phone, $studentName, $studentCode, $className, $date, $transactionId, $id = null)
    {
        return $this->sendZalo(
            $phone,
            config('services.onesms.templates.payment_confirmation', '123459'),
            [
                'customer_name' => $studentName,
                'mahocvien' => $studentCode,
                'language_center' => 'EduCore',
                'class_name' => $className,
                'date' => $date,
                'transaction_id' => $transactionId
            ],
            $id,
            [
                'type_send' => 2,
                'resend_sms' => 1,
                'message_sms' => "Xac nhan thanh toan: {$className} - {$transactionId}"
            ]
        );
    }

    /**
     * Gửi thông báo đăng ký khóa học thành công
     */
    public function sendRegistrationSuccess($phone, $studentName, $className, $date, $registrationId, $id = null)
    {
        return $this->sendZalo(
            $phone,
            config('services.onesms.templates.registration_success', '123460'),
            [
                'customer_name' => $studentName,
                'class_name' => $className,
                'language_center' => 'EduCore',
                'date' => $date,
                'ID' => $registrationId
            ],
            $id,
            [
                'type_send' => 2,
                'resend_sms' => 1,
                'message_sms' => "Dang ky thanh cong: {$className}"
            ]
        );
    }

    /**
     * Gửi thông báo vắng mặt
     */
    public function sendAbsentNotification($phone, $studentName, $studentCode, $className, $date, $id = null)
    {
        return $this->sendZalo(
            $phone,
            config('services.onesms.templates.absent', '123461'),
            [
                'customer_name' => $studentName,
                'mahocvien' => $studentCode,
                'class_name' => $className,
                'date' => $date
            ],
            $id,
            [
                'type_send' => 2,
                'resend_sms' => 1,
                'message_sms' => "Vang mat: {$className} ngay {$date}"
            ]
        );
    }

    /**
     * Gửi thông báo đi muộn
     */
    public function sendLateNotification($phone, $studentName, $studentCode, $className, $date, $id = null)
    {
        return $this->sendZalo(
            $phone,
            config('services.onesms.templates.late', '123462'),
            [
                'customer_name' => $studentName,
                'mahocvien' => $studentCode,
                'class_name' => $className,
                'date' => $date
            ],
            $id,
            [
                'type_send' => 2,
                'resend_sms' => 1,
                'message_sms' => "Di muon: {$className} ngay {$date}"
            ]
        );
    }

    /**
     * Gửi thông báo nhắc nộp bài tập
     */
    public function sendAssignmentDeadline($phone, $studentName, $studentCode, $className, $time, $date, $id = null)
    {
        return $this->sendZalo(
            $phone,
            config('services.onesms.templates.assignment_deadline', '123463'),
            [
                'customer_name' => $studentName,
                'mahocvien' => $studentCode,
                'class_name' => $className,
                'time' => $time,
                'date' => $date
            ],
            $id,
            [
                'type_send' => 2,
                'resend_sms' => 1,
                'message_sms' => "Nop bai: {$className} truoc {$time} ngay {$date}"
            ]
        );
    }

    /**
     * Gửi thông báo thay đổi lịch học
     */
    public function sendScheduleChange($phone, $studentName, $studentCode, $className, $oldDateTime, $newDateTime, $teacherName, $id = null)
    {
        return $this->sendZalo(
            $phone,
            config('services.onesms.templates.schedule_change', '123464'),
            [
                'customer_name' => $studentName,
                'mahocvien' => $studentCode,
                'class_name' => $className,
                'old_date_time' => $oldDateTime,
                'new_date_time' => $newDateTime,
                'teacher_name' => $teacherName
            ],
            $id,
            [
                'type_send' => 2,
                'resend_sms' => 1,
                'message_sms' => "Thay doi lich: {$className} - {$newDateTime}"
            ]
        );
    }

    /**
     * Gửi thông báo lịch kiểm tra online
     */
    public function sendOnlineExam($phone, $studentName, $studentCode, $className, $time, $date, $id = null)
    {
        return $this->sendZalo(
            $phone,
            config('services.onesms.templates.online_exam', '123465'),
            [
                'customer_name' => $studentName,
                'mahocvien' => $studentCode,
                'class_name' => $className,
                'time' => $time,
                'date' => $date
            ],
            $id,
            [
                'type_send' => 2,
                'resend_sms' => 1,
                'message_sms' => "Kiem tra online: {$className} luc {$time} ngay {$date}"
            ]
        );
    }

    /**
     * Xử lý response từ API
     */
    protected function handleResponse($response)
    {
        $code = $response['code'] ?? 'UNKNOWN';

        $success = $code === '0';

        $messages = [
            '0' => 'Thành công',
            '1' => 'Lỗi hệ thống',
            '2' => 'Input không hợp lệ',
            '3' => 'Sai username/password',
            '7' => 'Hết quota',
            '8' => 'Template chưa khai báo',
            '9' => 'Dữ liệu không khớp template',
        ];

        return [
            'success' => $success,
            'message' => $messages[$code] ?? 'Lỗi không xác định',
            'code' => $code,
            'data' => $response
        ];
    }

    /**
     * Format số điện thoại
     */
    protected function formatPhone($phone)
    {
        // Loại bỏ tất cả ký tự không phải số
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Đảm bảo số điện thoại bắt đầu bằng 0
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            return $phone;
        }

        // Nếu số điện thoại có 11 số và bắt đầu bằng 84, chuyển về 0
        if (strlen($phone) === 11 && substr($phone, 0, 2) === '84') {
            return '0' . substr($phone, 2);
        }

        return $phone;
    }

    /**
     * Kiểm tra trạng thái tài khoản
     */
    public function checkAccountStatus()
    {
        try {
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://zaloapi.conek.vn/GetOaZalo', [
                'username' => $this->username,
                'password' => $this->password,
            ]);

            return $response->json();
        } catch (Exception $e) {
            Log::error('OneSmsService: Error checking account status', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Lỗi kiểm tra tài khoản: ' . $e->getMessage(),
            ];
        }
    }
}
