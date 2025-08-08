<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // học viên
            $table->unsignedBigInteger('class_id'); // lớp/khóa học
            $table->decimal('amount', 15, 2); // số tiền
            $table->string('type')->default('tuition'); // loại khoản thu (học phí, tài liệu...)
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid'); // trạng thái
            $table->text('note')->nullable(); // ghi chú
            $table->timestamp('paid_at')->nullable(); // ngày thanh toán
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classrooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
