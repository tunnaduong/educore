<?php

namespace App\Console\Commands;

use App\Models\License;
use App\Services\LicenseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredLicenses extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'licenses:check-expired';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Kiểm tra và đánh dấu các license đã hết hạn';

  protected $licenseService;

  public function __construct(LicenseService $licenseService)
  {
    parent::__construct();
    $this->licenseService = $licenseService;
  }

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->info('Đang kiểm tra license hết hạn...');

    // Lấy tất cả license đang active nhưng đã hết hạn
    $expiredLicenses = License::where('status', 'active')
      ->where('is_lifetime', false)
      ->whereNotNull('expires_at')
      ->where('expires_at', '<=', now())
      ->get();

    $count = 0;

    foreach ($expiredLicenses as $license) {
      $this->licenseService->expireLicense($license);
      $count++;

      $this->line("License #{$license->id} (User: {$license->user_id}) đã hết hạn");
    }

    if ($count > 0) {
      $this->info("Đã đánh dấu {$count} license hết hạn.");
      Log::info("CheckExpiredLicenses: Đã đánh dấu {$count} license hết hạn");
    } else {
      $this->info('Không có license nào hết hạn.');
    }

    return Command::SUCCESS;
  }
}
