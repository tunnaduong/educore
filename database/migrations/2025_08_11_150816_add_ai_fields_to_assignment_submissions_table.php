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
        Schema::table('assignment_submissions', function (Blueprint $table) {
            // AI correction fields
            $table->text('ai_corrected_content')->nullable()->after('feedback');
            $table->json('ai_errors_found')->nullable()->after('ai_corrected_content');
            $table->json('ai_suggestions')->nullable()->after('ai_errors_found');
            
            // AI grading fields
            $table->decimal('ai_score', 3, 1)->nullable()->after('ai_suggestions');
            $table->text('ai_feedback')->nullable()->after('ai_score');
            $table->json('ai_criteria_scores')->nullable()->after('ai_feedback');
            $table->json('ai_strengths')->nullable()->after('ai_criteria_scores');
            $table->json('ai_weaknesses')->nullable()->after('ai_strengths');
            $table->timestamp('ai_graded_at')->nullable()->after('ai_weaknesses');
            
            // AI analysis fields
            $table->json('ai_analysis')->nullable()->after('ai_graded_at');
            $table->json('ai_score_breakdown')->nullable()->after('ai_analysis');
            $table->json('ai_improvement_suggestions')->nullable()->after('ai_score_breakdown');
            $table->json('ai_learning_resources')->nullable()->after('ai_improvement_suggestions');
            $table->timestamp('ai_analyzed_at')->nullable()->after('ai_learning_resources');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'ai_corrected_content',
                'ai_errors_found',
                'ai_suggestions',
                'ai_score',
                'ai_feedback',
                'ai_criteria_scores',
                'ai_strengths',
                'ai_weaknesses',
                'ai_graded_at',
                'ai_analysis',
                'ai_score_breakdown',
                'ai_improvement_suggestions',
                'ai_learning_resources',
                'ai_analyzed_at'
            ]);
        });
    }
};
