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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề bài học
            $table->text('description')->nullable(); // Mô tả ngắn
            $table->longText('content')->nullable(); // Nội dung chi tiết
            $table->string('attachment')->nullable(); // Tệp đính kèm
            $table->string('video')->nullable(); // Link video
            $table->unsignedBigInteger('classroom_id')->nullable(); // Liên kết lớp học
            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
