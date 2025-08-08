<?php

namespace App\Livewire\Admin\Finance;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Payment;

class ShowPayment extends Component
{
    use WithFileUploads;

    public $user;
    public $payments;
    public $proof;
    public $selectedPaymentId;

    // Thêm các trường cho form tạo payment mới
    public $newAmount;
    public $newType = 'tuition';
    public $newStatus = 'unpaid';
    public $newPaidAt;
    public $newClassId;
    public $showCreateModal = false;

    protected $rules = [
        'proof' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        'newAmount' => 'nullable|numeric|min:0',
        'newType' => 'nullable|string',
        'newStatus' => 'nullable|string',
        'newPaidAt' => 'nullable|date',
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadPayments();
    }

    public function loadPayments()
    {
        $this->payments = Payment::where('user_id', $this->user->id)->orderByDesc('created_at')->get();
    }

    public function uploadProof($paymentId)
    {
        $this->validateOnly('proof');
        $payment = Payment::findOrFail($paymentId);
        $path = $this->proof->store('payment_proofs', 'public');
        $payment->proof_path = $path;
        $payment->save();
        $this->proof = null;
        $this->loadPayments();
        session()->flash('success', 'Tải lên minh chứng thành công!');
    }

    public function updateStatus($paymentId, $status)
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->status = $status;
        $payment->save();
        $this->loadPayments();
        session()->flash('success', 'Cập nhật trạng thái thành công!');
    }

    public function deletePayment($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->delete();
        $this->loadPayments();
        session()->flash('success', 'Xóa giao dịch học phí thành công!');
    }

    public function getClassroomsProperty()
    {
        return $this->user->enrolledClassrooms;
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
    }
    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['newAmount', 'newType', 'newStatus', 'newPaidAt', 'newClassId']);
    }

    public function createPayment()
    {
        $this->newAmount = preg_replace('/\D/', '', $this->newAmount);
        $this->validate([
            'newAmount' => 'required|numeric|min:1',
            'newType' => 'required|string|in:tuition,material,other',
            'newStatus' => 'required|string|in:unpaid,partial,paid',
            'newPaidAt' => 'nullable|date|before_or_equal:today',
            'newClassId' => 'required|exists:classrooms,id',
        ]);
        Payment::create([
            'user_id' => $this->user->id,
            'class_id' => $this->newClassId,
            'amount' => $this->newAmount,
            'type' => $this->newType,
            'status' => $this->newStatus,
            'paid_at' => $this->newPaidAt,
        ]);
        $this->reset(['newAmount', 'newType', 'newStatus', 'newPaidAt', 'newClassId', 'showCreateModal']);
        $this->loadPayments();
        session()->flash('success', 'Tạo giao dịch học phí mới thành công!');
    }

    public function render()
    {
        return view('admin.finance.show-payment');
    }
}
