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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id'); // nhân viên/giáo viên
            $table->unsignedBigInteger('class_id')->nullable(); // lớp/khóa học (nếu có)
            $table->decimal('amount', 15, 2); // số tiền
            $table->string('type')->default('salary'); // loại khoản chi (lương, vận hành...)
            $table->text('note')->nullable(); // ghi chú
            $table->timestamp('spent_at')->nullable(); // ngày chi
            $table->timestamps();

            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classrooms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
