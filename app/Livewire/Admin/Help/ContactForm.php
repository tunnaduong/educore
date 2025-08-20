<?php

namespace App\Livewire\Admin\Help;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ContactForm extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $description;
    public $attachment;
    public $tickets = [];

    protected $rules = [
        'description' => 'required|min:10',
        'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,mp4'
    ];

    protected $messages = [
        'description.required' => 'Vui lòng mô tả vấn đề của bạn',
        'description.min' => 'Mô tả phải có ít nhất 10 ký tự',
        'attachment.file' => 'File không hợp lệ',
        'attachment.max' => 'File không được vượt quá 10MB',
        'attachment.mimes' => 'Chỉ chấp nhận file .jpg, .png, .mp4'
    ];

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user ? $user->name : '';
        $this->email = $user ? $user->email : '';
        $this->loadTickets();
    }

    public function loadTickets()
    {
        // TODO: Load tickets from database
        $this->tickets = [
            [
                'id' => 1,
                'subject' => 'Vấn đề với tạo bài kiểm tra',
                'status' => 'completed',
                'created_at' => '2024-01-15 10:30:00',
                'updated_at' => '2024-01-16 14:20:00'
            ],
            [
                'id' => 2,
                'subject' => 'Không thể xuất báo cáo Excel',
                'status' => 'processing',
                'created_at' => '2024-01-20 09:15:00',
                'updated_at' => '2024-01-20 16:45:00'
            ]
        ];
    }

    public function submitTicket()
    {
        $this->validate();

        $user = auth()->user();
        $ticketData = [
            'name' => $this->name,
            'email' => $this->email,
            'description' => $this->description,
            'user_id' => $user ? $user->id : null,
            'status' => 'pending'
        ];

        // Save attachment if uploaded
        if ($this->attachment) {
            $path = $this->attachment->store('tickets', 'public');
            $ticketData['attachment'] = $path;
        }

        // TODO: Save to database
        // $ticket = Ticket::create($ticketData);

        // Send email to dev team
        $this->sendEmailToDevTeam($ticketData);

        // Reset form
        $this->description = '';
        $this->attachment = null;

        // Reload tickets
        $this->loadTickets();

        session()->flash('message', 'Yêu cầu trợ giúp đã được gửi thành công!');
    }

    private function sendEmailToDevTeam($ticketData)
    {
        // TODO: Send email to dev team
        // Mail::to('dev@educore.com')->send(new SupportTicketMail($ticketData));
    }

    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'pending' => 'badge-warning',
            'processing' => 'badge-info',
            'completed' => 'badge-success',
            default => 'badge-secondary'
        };
    }

    public function getStatusText($status)
    {
        return match($status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            default => 'Không xác định'
        };
    }

    public function render()
    {
        return view('livewire.admin.help.contact-form');
    }
}
