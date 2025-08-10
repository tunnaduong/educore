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
        Schema::table('evaluations', function (Blueprint $table) {
            // Xóa foreign key constraint trước
            $table->dropForeign(['student_id']);

            // Xóa unique constraint cũ chỉ trên student_id
            $table->dropUnique(['student_id']);

            // Thêm lại foreign key constraint
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            // Thêm unique constraint mới trên student_id và evaluation_round_id
            // Đảm bảo mỗi học viên chỉ đánh giá 1 lần mỗi đợt
            $table->unique(['student_id', 'evaluation_round_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            // Xóa unique constraint mới
            $table->dropUnique(['student_id', 'evaluation_round_id']);

            // Xóa foreign key constraint
            $table->dropForeign(['student_id']);

            // Khôi phục unique constraint cũ
            $table->unique(['student_id']);

            // Thêm lại foreign key constraint
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }
};
