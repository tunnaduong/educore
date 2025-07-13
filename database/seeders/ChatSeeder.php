<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $classrooms = Classroom::all();

        if ($users->count() < 2) {
            $this->command->info('Cần ít nhất 2 người dùng để tạo tin nhắn mẫu.');
            return;
        }

        // Tạo tin nhắn giữa các người dùng
        foreach ($users->take(3) as $user) {
            $otherUsers = $users->where('id', '!=', $user->id)->take(2);

            foreach ($otherUsers as $otherUser) {
                Message::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $otherUser->id,
                    'message' => "Xin chào {$otherUser->name}! Đây là tin nhắn mẫu từ {$user->name}.",
                    'created_at' => now()->subHours(rand(1, 24)),
                ]);

                Message::create([
                    'sender_id' => $otherUser->id,
                    'receiver_id' => $user->id,
                    'message' => "Chào {$user->name}! Cảm ơn bạn đã liên lạc.",
                    'created_at' => now()->subHours(rand(1, 12)),
                ]);
            }
        }

        // Tạo tin nhắn cho lớp học
        if ($classrooms->count() > 0) {
            foreach ($classrooms->take(2) as $classroom) {
                $teacher = $users->where('role', 'teacher')->first();
                if ($teacher) {
                    Message::create([
                        'sender_id' => $teacher->id,
                        'class_id' => $classroom->id,
                        'message' => "Chào các bạn! Đây là thông báo từ giáo viên cho lớp {$classroom->name}.",
                        'created_at' => now()->subHours(rand(1, 6)),
                    ]);

                    Message::create([
                        'sender_id' => $teacher->id,
                        'class_id' => $classroom->id,
                        'message' => "Nhớ hoàn thành bài tập trước buổi học tiếp theo nhé!",
                        'created_at' => now()->subHours(rand(1, 3)),
                    ]);
                }
            }
        }

        $this->command->info('Đã tạo tin nhắn mẫu thành công!');
    }
}
