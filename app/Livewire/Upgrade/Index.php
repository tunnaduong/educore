<?php

namespace App\Livewire\Upgrade;

use App\Services\LicenseService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
  public $currentLicense;

  protected $licenseService;

  public function boot(LicenseService $licenseService)
  {
    $this->licenseService = $licenseService;
  }

  public function mount()
  {
    $user = Auth::user();
    $this->currentLicense = $user->getCurrentLicense();
  }

  public function render()
  {
    $user = Auth::user();
    $licenseStatus = $this->licenseService->checkLicenseStatus($user);

    // Thông tin tài khoản ngân hàng từ config
    $bankAccount = config('sepay.bank_account', []);

    // Tạo payment code cho user (có thể là user_id, phone, hoặc mã tùy chỉnh)
    $paymentCode = $this->generatePaymentCode($user);

    return view('livewire.upgrade.index', [
      'licenseStatus' => $licenseStatus,
      'bankAccount' => $bankAccount,
      'paymentCode' => $paymentCode,
    ]);
  }

  /**
   * Tạo mã thanh toán cho user
   * Sử dụng pattern từ config (mặc định là "SE")
   * Format: SE{user_id}MONTHLY hoặc SE{user_id}YEARLY
   *
   * @param  \App\Models\User  $user
   * @return string
   */
  private function generatePaymentCode($user): string
  {
    $pattern = config('sepay.pattern', 'SE');

    // Sử dụng pattern + user_id làm mã thanh toán
    return $pattern . $user->id;
  }
}
