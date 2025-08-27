<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function uploadAttachment(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:102400', // 100MB
                'message_text' => 'nullable|string|max:1000',
                'receiver_id' => 'nullable|integer|exists:users,id',
                'class_id' => 'nullable|integer|exists:classrooms,id',
            ]);

            $file = $request->file('file');

            // Kiểm tra file
            if (! $file->isValid()) {
                return response()->json(['error' => 'File không hợp lệ'], 400);
            }

            // Kiểm tra kích thước
            if ($file->getSize() > 102400 * 1024) {
                return response()->json(['error' => 'File quá lớn. Kích thước tối đa là 100MB.'], 400);
            }

            // Kiểm tra MIME type
            $allowedMimes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar', '7z', 'mp3', 'm4a', 'wav', 'ogg', 'oga', 'flac', 'amr', 'webm', 'mp4'];
            $fileExtension = strtolower($file->getClientOriginalExtension());
            if (! in_array($fileExtension, $allowedMimes)) {
                return response()->json(['error' => 'Định dạng file không được hỗ trợ.'], 400);
            }

            // Lưu file
            $path = $file->store('chat-attachments', 'public');

            // Tạo tin nhắn
            $messageData = [
                'sender_id' => Auth::id(),
                'message' => $request->input('message_text', ''),
                'attachment' => $path,
            ];

            if ($request->input('receiver_id')) {
                $messageData['receiver_id'] = $request->input('receiver_id');
            } elseif ($request->input('class_id')) {
                $messageData['class_id'] = $request->input('class_id');
            }

            $message = Message::create($messageData);

            // Broadcast tin nhắn
            \App\Events\MessageSent::dispatch($message);

            Log::info('File uploaded successfully', [
                'path' => $path,
                'message_id' => $message->id,
                'file_name' => $file->getClientOriginalName(),
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'file_path' => $path,
                'file_url' => Storage::url($path),
            ]);

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'file' => $request->file('file') ? $request->file('file')->getClientOriginalName() : 'unknown',
            ]);

            return response()->json(['error' => 'Không thể tải lên file: '.$e->getMessage()], 500);
        }
    }
}
