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
        Schema::create('evaluation_rounds', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên đợt đánh giá
            $table->text('description')->nullable(); // Mô tả đợt đánh giá
            $table->date('start_date'); // Ngày bắt đầu
            $table->date('end_date'); // Ngày kết thúc
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->timestamps();
        });

        // Thêm cột evaluation_round_id vào bảng evaluations
        Schema::table('evaluations', function (Blueprint $table) {
            $table->foreignId('evaluation_round_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropForeign(['evaluation_round_id']);
            $table->dropColumn('evaluation_round_id');
        });

        Schema::dropIfExists('evaluation_rounds');
    }
};
