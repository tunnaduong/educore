<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classroom_message_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classrooms')->onDelete('cascade');
            $table->foreignId('last_read_message_id')->nullable()->constrained('messages')->onDelete('set null');
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_message_reads');
    }
};
