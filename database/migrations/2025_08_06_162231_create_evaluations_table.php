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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('semester'); // Học kỳ (VD: 2024-1, 2024-2)
            $table->json('teacher_ratings')->nullable(); // Đánh giá về giáo viên (1-5 điểm)
            $table->json('course_ratings')->nullable(); // Đánh giá về khóa học (1-5 điểm)
            $table->integer('personal_satisfaction')->nullable(); // Mức độ hài lòng cá nhân (1-5 điểm)
            $table->text('suggestions')->nullable(); // Đề xuất cải thiện
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // Đảm bảo mỗi học viên chỉ đánh giá 1 lần mỗi học kỳ
            $table->unique(['student_id', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
