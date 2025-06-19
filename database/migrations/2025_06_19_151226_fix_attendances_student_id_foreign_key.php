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
        Schema::table('attendances', function (Blueprint $table) {
            // Xóa khóa ngoại cũ nếu có
            $table->dropForeign(['student_id']);

            // Thêm khóa ngoại mới tham chiếu đến bảng students
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Xóa khóa ngoại mới
            $table->dropForeign(['student_id']);

            // Khôi phục khóa ngoại cũ (nếu cần)
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
