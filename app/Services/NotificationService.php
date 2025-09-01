<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Notification;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected OneSmsService $oneSmsService;

    public function __construct(OneSmsService $oneSmsService)
    {
        $this->oneSmsService = $oneSmsService;
    }

    /**
     * Gửi thông báo qua Zalo ZNS
     */
    public function sendZaloNotification($notification, $users = null)
    {
        try {
            // Nếu không có users, lấy từ notification
            if (! $users) {
                $users = $this->getUsersForNotification($notification);
            }

            $successCount = 0;
            $failedCount = 0;
            $results = [];

            foreach ($users as $user) {
                if (! $user->phone) {
                    Log::warning('NotificationService: User has no phone number', [
                        'user_id' => $user->id,
                        'notification_id' => $notification->id,
                    ]);
                    $failedCount++;

                    continue;
                }

                $result = $this->sendNotificationToUser($notification, $user);
                $results[] = $result;

                if ($result['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                }
            }

            Log::info('NotificationService: Zalo notification sent', [
                'notification_id' => $notification->id,
                'total_users' => count($users),
                'success_count' => $successCount,
                'failed_count' => $failedCount,
            ]);

            return [
                'success' => $successCount > 0,
                'total' => count($users),
                'success_count' => $successCount,
                'failed_count' => $failedCount,
                'results' => $results,
            ];
        } catch (Exception $e) {
            Log::error('NotificationService: Error sending Zalo notification', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Gửi thông báo đến một user cụ thể
     */
    protected function sendNotificationToUser($notification, $user)
    {
        try {
            // Xác định template và data dựa trên loại thông báo
            $templateConfig = $this->getTemplateForNotification($notification, $user);

            if (! $templateConfig) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy template cho loại thông báo này',
                    'user_id' => $user->id,
                ];
            }

            $result = $this->oneSmsService->sendZalo(
                $user->phone,
                $templateConfig['template_id'],
                $templateConfig['data'],
                'notification_'.$notification->id.'_'.$user->id
            );

            return array_merge($result, ['user_id' => $user->id]);
        } catch (Exception $e) {
            Log::error('NotificationService: Error sending to user', [
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'user_id' => $user->id,
            ];
        }
    }

    /**
     * Lấy danh sách users cho notification
     */
    protected function getUsersForNotification($notification)
    {
        $query = User::where('role', 'student');

        // Nếu notification có class_id, chỉ gửi cho học viên trong lớp đó
        if ($notification->class_id) {
            $classroom = Classroom::find($notification->class_id);
            if ($classroom) {
                $query->whereIn('id', $classroom->students->pluck('id'));
            }
        }

        // Nếu notification có user_id, chỉ gửi cho user đó
        if ($notification->user_id) {
            $query->where('id', $notification->user_id);
        }

        return $query->whereNotNull('phone')->get();
    }

    /**
     * Xác định template và data cho notification
     */
    protected function getTemplateForNotification($notification, $user)
    {
        $templates = config('services.onesms.templates');

        switch ($notification->type) {
            case 'reminder':
                return [
                    'template_id' => $templates['schedule_reminder'],
                    'data' => [
                        'customer_name' => $user->name,
                        'mahocvien' => $user->student_code ?? 'N/A',
                        'class_name' => $notification->classroom?->name ?? 'Lớp học',
                        'time' => now()->format('H:i'),
                        'date' => now()->format('d/m/Y'),
                        'language_center' => 'EduCore',
                    ],
                ];

            case 'assignment_result':
                return [
                    'template_id' => $templates['assignment_result'],
                    'data' => [
                        'customer_name' => $user->name,
                        'mahocvien' => $user->student_code ?? 'N/A',
                        'assignment_name' => 'Bài tập',
                        'date' => now()->format('d/m/Y'),
                        'score' => 'Điểm',
                        'comment' => 'Nhận xét',
                    ],
                ];

            case 'payment_confirmation':
                return [
                    'template_id' => $templates['payment_confirmation'],
                    'data' => [
                        'customer_name' => $user->name,
                        'mahocvien' => $user->student_code ?? 'N/A',
                        'language_center' => 'EduCore',
                        'class_name' => 'Khóa học',
                        'date' => now()->format('d/m/Y'),
                        'transaction_id' => 'N/A',
                    ],
                ];

            default:
                // Template mặc định cho thông báo chung
                return [
                    'template_id' => $templates['schedule_reminder'],
                    'data' => [
                        'customer_name' => $user->name,
                        'mahocvien' => $user->student_code ?? 'N/A',
                        'class_name' => $notification->classroom?->name ?? 'Lớp học',
                        'time' => now()->format('H:i'),
                        'date' => now()->format('d/m/Y'),
                        'language_center' => 'EduCore',
                    ],
                ];
        }
    }

    /**
     * Gửi OTP qua Zalo
     */
    public function sendOTP($user, $otp)
    {
        if (! $user->phone) {
            return [
                'success' => false,
                'message' => 'User không có số điện thoại',
            ];
        }

        return $this->oneSmsService->sendOTP($user->phone, $otp, 'otp_'.$user->id);
    }

    /**
     * Gửi nhắc lịch học
     */
    public function sendScheduleReminder($user, $schedule)
    {
        if (! $user->phone) {
            return [
                'success' => false,
                'message' => 'User không có số điện thoại',
            ];
        }

        $classroom = $schedule->classroom;

        return $this->oneSmsService->sendScheduleReminder(
            $user->phone,
            $user->name,
            $user->student_code ?? 'N/A',
            $classroom->name,
            $schedule->start_time->format('H:i'),
            $schedule->date->format('d/m/Y'),
            'schedule_'.$schedule->id.'_'.$user->id
        );
    }

    /**
     * Gửi kết quả bài tập
     */
    public function sendAssignmentResult($user, $submission)
    {
        if (! $user->phone) {
            return [
                'success' => false,
                'message' => 'User không có số điện thoại',
            ];
        }

        $assignment = $submission->assignment;
        $classroom = $assignment->classroom;

        return $this->oneSmsService->sendAssignmentResult(
            $user->phone,
            $user->name,
            $user->student_code ?? 'N/A',
            $assignment->title,
            $submission->updated_at->format('d/m/Y'),
            $submission->score ?? 'N/A',
            $submission->feedback ?? 'Không có nhận xét',
            'assignment_'.$submission->id
        );
    }

    /**
     * Gửi xác nhận thanh toán
     */
    public function sendPaymentConfirmation($user, $payment)
    {
        if (! $user->phone) {
            return [
                'success' => false,
                'message' => 'User không có số điện thoại',
            ];
        }

        $classroom = $payment->classroom;

        return $this->oneSmsService->sendPaymentConfirmation(
            $user->phone,
            $user->name,
            $user->student_code ?? 'N/A',
            $classroom->name,
            $payment->created_at->format('d/m/Y'),
            $payment->transaction_id ?? 'N/A',
            'payment_'.$payment->id
        );
    }

    /**
     * Gửi thông báo đăng ký khóa học thành công
     */
    public function sendRegistrationSuccess($user, $classroom)
    {
        if (! $user->phone) {
            return [
                'success' => false,
                'message' => 'User không có số điện thoại',
            ];
        }

        return $this->oneSmsService->sendRegistrationSuccess(
            $user->phone,
            $user->name,
            $classroom->name,
            now()->format('d/m/Y'),
            'REG_'.$classroom->id.'_'.$user->id,
            'registration_'.$classroom->id.'_'.$user->id
        );
    }

    /**
     * Gửi thông báo vắng mặt
     */
    public function sendAbsentNotification($user, $attendance)
    {
        if (! $user->phone) {
            return [
                'success' => false,
                'message' => 'User không có số điện thoại',
            ];
        }

        $classroom = $attendance->classroom;

        return $this->oneSmsService->sendAbsentNotification(
            $user->phone,
            $user->name,
            $user->student_code ?? 'N/A',
            $classroom->name,
            $attendance->date->format('d/m/Y'),
            'absent_'.$attendance->id
        );
    }

    /**
     * Gửi thông báo đi muộn
     */
    public function sendLateNotification($user, $attendance)
    {
        if (! $user->phone) {
            return [
                'success' => false,
                'message' => 'User không có số điện thoại',
            ];
        }

        $classroom = $attendance->classroom;

        return $this->oneSmsService->sendLateNotification(
            $user->phone,
            $user->name,
            $user->student_code ?? 'N/A',
            $classroom->name,
            $attendance->date->format('d/m/Y'),
            'late_'.$attendance->id
        );
    }

    /**
     * Gửi thông báo nhắc nộp bài tập
     */
    public function sendAssignmentDeadline($user, $assignment)
    {
        if (! $user->phone) {
            return [
                'success' => false,
                'message' => 'User không có số điện thoại',
            ];
        }

        $classroom = $assignment->classroom;

        return $this->oneSmsService->sendAssignmentDeadline(
            $user->phone,
            $user->name,
            $user->student_code ?? 'N/A',
            $classroom->name,
            $assignment->due_date->format('H:i'),
            $assignment->due_date->format('d/m/Y'),
            'deadline_'.$assignment->id.'_'.$user->id
        );
    }

    /**
     * Gửi thông báo thay đổi lịch học
     */
    public function sendScheduleChange($user, $schedule, $oldDateTime, $newDateTime, $teacherName)
    {
        if (! $user->phone) {
            return [
                'success' => false,
                'message' => 'User không có số điện thoại',
            ];
        }

        $classroom = $schedule->classroom;

        return $this->oneSmsService->sendScheduleChange(
            $user->phone,
            $user->name,
            $user->student_code ?? 'N/A',
            $classroom->name,
            $oldDateTime,
            $newDateTime,
            $teacherName,
            'change_'.$schedule->id.'_'.$user->id
        );
    }

    /**
     * Gửi thông báo lịch kiểm tra online
     */
    public function sendOnlineExam($user, $schedule)
    {
        if (! $user->phone) {
            return [
                'success' => false,
                'message' => 'User không có số điện thoại',
            ];
        }

        $classroom = $schedule->classroom;

        return $this->oneSmsService->sendOnlineExam(
            $user->phone,
            $user->name,
            $user->student_code ?? 'N/A',
            $classroom->name,
            $schedule->start_time->format('H:i'),
            $schedule->date->format('d/m/Y'),
            'exam_'.$schedule->id.'_'.$user->id
        );
    }

    /**
     * Gửi nhắc lịch học hàng loạt (cho cron job)
     */
    public function sendBulkScheduleReminders()
    {
        try {
            // Lấy lịch học trong 30 phút tới
            $schedules = Schedule::with(['classroom', 'classroom.students'])
                ->where('date', now()->toDateString())
                ->where('start_time', '>=', now()->format('H:i:s'))
                ->where('start_time', '<=', now()->addMinutes(30)->format('H:i:s'))
                ->get();

            $totalSent = 0;
            $totalSuccess = 0;
            $totalFailed = 0;

            foreach ($schedules as $schedule) {
                foreach ($schedule->classroom->students as $student) {
                    if ($student->phone) {
                        $result = $this->sendScheduleReminder($student, $schedule);
                        $totalSent++;

                        if ($result['success']) {
                            $totalSuccess++;
                        } else {
                            $totalFailed++;
                        }
                    }
                }
            }

            Log::info('NotificationService: Bulk schedule reminders sent', [
                'total_schedules' => $schedules->count(),
                'total_sent' => $totalSent,
                'total_success' => $totalSuccess,
                'total_failed' => $totalFailed,
            ]);

            return [
                'success' => $totalSuccess > 0,
                'total_sent' => $totalSent,
                'total_success' => $totalSuccess,
                'total_failed' => $totalFailed,
            ];
        } catch (Exception $e) {
            Log::error('NotificationService: Error sending bulk schedule reminders', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
