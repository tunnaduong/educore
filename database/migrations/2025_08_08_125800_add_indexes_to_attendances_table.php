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
            // Thêm index cho việc tìm kiếm nhanh theo class_id, date
            $table->index(['class_id', 'date']);
            // Thêm index cho việc tìm kiếm theo student_id, date
            $table->index(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['class_id', 'date']);
            $table->dropIndex(['student_id', 'date']);
        });
    }
};
