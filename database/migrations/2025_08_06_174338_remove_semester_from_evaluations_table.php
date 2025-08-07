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

            // Xóa unique constraint cũ
            $table->dropUnique(['student_id', 'semester']);

            // Xóa cột semester
            $table->dropColumn('semester');

            // Thêm lại foreign key constraint
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            // Thêm unique constraint mới chỉ cho student_id
            $table->unique('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            // Xóa unique constraint mới
            $table->dropUnique(['student_id']);

            // Xóa foreign key constraint
            $table->dropForeign(['student_id']);

            // Thêm lại cột semester
            $table->string('semester')->after('student_id');

            // Thêm lại foreign key constraint
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            // Thêm lại unique constraint cũ
            $table->unique(['student_id', 'semester']);
        });
    }
};
