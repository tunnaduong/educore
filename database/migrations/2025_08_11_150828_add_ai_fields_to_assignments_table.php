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
        Schema::table('assignments', function (Blueprint $table) {
            // AI grading criteria
            $table->json('grading_criteria')->nullable()->after('description');
            $table->decimal('max_score', 3, 1)->default(10.0)->after('grading_criteria');
            
            // AI analysis fields
            $table->boolean('ai_analysis_enabled')->default(false)->after('max_score');
            $table->json('ai_analysis_config')->nullable()->after('ai_analysis_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn([
                'grading_criteria',
                'max_score',
                'ai_analysis_enabled',
                'ai_analysis_config'
            ]);
        });
    }
};
