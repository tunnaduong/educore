<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  /**
   * Run the migrations.
   * Tạo bảng licenses để lưu thông tin license của user
   */
  public function up(): void
  {
    Schema::create('licenses', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id'); // User sở hữu license
      $table->enum('plan_type', ['free_trial', 'vip_monthly', 'vip_yearly']); // Loại gói
      $table->enum('status', ['active', 'expired', 'cancelled'])->default('active'); // Trạng thái
      $table->timestamp('started_at'); // Ngày bắt đầu license
      $table->timestamp('expires_at')->nullable(); // Ngày hết hạn (nullable cho lifetime)
      $table->boolean('is_lifetime')->default(false); // License trọn đời
      $table->unsignedBigInteger('payment_id')->nullable(); // Liên kết với payment
      $table->boolean('auto_renew')->default(false); // Tự động gia hạn
      $table->timestamps();

      // Foreign keys
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');

      // Indexes
      $table->index('user_id');
      $table->index('status');
      $table->index('expires_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('licenses');
  }
};
