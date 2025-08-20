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
        Schema::table('quizzes', function (Blueprint $table) {
            // AI validation fields
            $table->json('ai_validation_errors')->nullable()->after('questions');
            $table->json('ai_suggestions')->nullable()->after('ai_validation_errors');
            $table->timestamp('ai_validated_at')->nullable()->after('ai_suggestions');

            // AI generation fields
            $table->boolean('ai_generated')->default(false)->after('ai_validated_at');
            $table->string('ai_generation_source')->nullable()->after('ai_generated'); // lesson_id, topic, etc.
            $table->json('ai_generation_params')->nullable()->after('ai_generation_source'); // difficulty, question_count, etc.
            $table->timestamp('ai_generated_at')->nullable()->after('ai_generation_params');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'ai_validation_errors',
                'ai_suggestions',
                'ai_validated_at',
                'ai_generated',
                'ai_generation_source',
                'ai_generation_params',
                'ai_generated_at',
            ]);
        });
    }
};
