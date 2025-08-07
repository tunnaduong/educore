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

    protected $rules = [
        'proof' => 'image|max:2048',
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
        $this->validate();
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

    public function render()
    {
        return view('admin.finance.show-payment');
    }
}
