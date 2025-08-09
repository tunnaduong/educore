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
            // Xóa foreign key hiện tại (đang set null)
            $table->dropForeign(['evaluation_round_id']);
            // Tạo lại foreign key với RESTRICT/NO ACTION khi xóa round
            $table->foreign('evaluation_round_id')
                ->references('id')->on('evaluation_rounds')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            // Trả về trạng thái cũ: set null khi xóa round
            $table->dropForeign(['evaluation_round_id']);
            $table->foreign('evaluation_round_id')
                ->references('id')->on('evaluation_rounds')
                ->onDelete('set null');
        });
    }
};
