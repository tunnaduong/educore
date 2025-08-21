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
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('title')->after('class_id');
            $table->enum('type', ['info', 'warning', 'success', 'danger', 'reminder'])->default('info')->after('message');
            $table->timestamp('scheduled_at')->nullable()->after('is_read');
            $table->timestamp('expires_at')->nullable()->after('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['title', 'type', 'scheduled_at', 'expires_at']);
        });
    }
};
