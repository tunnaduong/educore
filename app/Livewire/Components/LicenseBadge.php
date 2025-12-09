<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LicenseBadge extends Component
{
  public $license;
  public $daysRemaining;
  public $expiresAt;
  public $planTypeLabel;
  public $badgeColor;

  public function mount()
  {
    $this->loadLicense();
  }

  public function loadLicense()
  {
    $user = Auth::user();
    if (!$user) {
      return;
    }

    $this->license = $user->getCurrentLicense();

    if ($this->license) {
      $this->daysRemaining = $this->license->daysRemaining();
      $this->expiresAt = $this->license->expires_at;
      $this->planTypeLabel = $this->getPlanTypeLabel($this->license->plan_type);
      $this->badgeColor = $this->getBadgeColor($this->license->plan_type);
    }
  }

  /**
   * Lấy label cho plan type
   *
   * @param  string  $planType
   * @return string
   */
  private function getPlanTypeLabel(string $planType): string
  {
    return match ($planType) {
      'free_trial' => 'Dùng thử',
      'vip_monthly' => 'VIP Monthly',
      'vip_yearly' => 'VIP Yearly',
      default => 'Unknown',
    };
  }

  /**
   * Lấy màu badge cho plan type
   *
   * @param  string  $planType
   * @return string
   */
  private function getBadgeColor(string $planType): string
  {
    return match ($planType) {
      'free_trial' => 'success', // Xanh lá
      'vip_monthly' => 'warning', // Cam
      'vip_yearly' => 'warning', // Vàng/Cam
      default => 'secondary',
    };
  }

  public function render()
  {
    return view('livewire.components.license-badge');
  }
}
