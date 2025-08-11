<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Message;
use App\Models\User;
use App\Models\Classroom;

class CheckChatSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:check {--fix : Fix issues automatically}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and fix chat system issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking Chat System...');

        $issues = [];

        // Check database tables
        $this->checkDatabaseTables($issues);

        // Check storage
        $this->checkStorage($issues);

        // Check models
        $this->checkModels($issues);

        // Check broadcasting
        $this->checkBroadcasting($issues);

        // Display results
        $this->displayResults($issues);

        // Fix issues if requested
        if ($this->option('fix')) {
            $this->fixIssues($issues);
        }

        return 0;
    }

    private function checkDatabaseTables(&$issues)
    {
        $this->info('📊 Checking database tables...');

        // Check messages table
        if (!DB::getSchemaBuilder()->hasTable('messages')) {
            $issues[] = '❌ Messages table does not exist';
        } else {
            $this->info('✅ Messages table exists');

            // Check columns
            $columns = DB::getSchemaBuilder()->getColumnListing('messages');
            $requiredColumns = ['id', 'sender_id', 'receiver_id', 'class_id', 'message', 'attachment', 'read_at', 'created_at', 'updated_at'];

            foreach ($requiredColumns as $column) {
                if (!in_array($column, $columns)) {
                    $issues[] = "❌ Messages table missing column: {$column}";
                }
            }
        }

        // Check classroom_message_reads table
        if (!DB::getSchemaBuilder()->hasTable('classroom_message_reads')) {
            $issues[] = '❌ Classroom message reads table does not exist';
        } else {
            $this->info('✅ Classroom message reads table exists');
        }
    }

    private function checkStorage(&$issues)
    {
        $this->info('💾 Checking storage...');

        // Check if storage link exists
        if (!file_exists(public_path('storage'))) {
            $issues[] = '❌ Storage link does not exist. Run: php artisan storage:link';
        } else {
            $this->info('✅ Storage link exists');
        }

        // Check chat-attachments directory
        $chatDir = storage_path('app/public/chat-attachments');
        if (!is_dir($chatDir)) {
            $issues[] = '❌ Chat attachments directory does not exist';
        } else {
            $this->info('✅ Chat attachments directory exists');

            // Check permissions
            if (!is_writable($chatDir)) {
                $issues[] = '❌ Chat attachments directory is not writable';
            }
        }
    }

    private function checkModels(&$issues)
    {
        $this->info('🏗️ Checking models...');

        // Check Message model
        try {
            $message = new Message();
            $this->info('✅ Message model works');
        } catch (\Exception $e) {
            $issues[] = '❌ Message model error: ' . $e->getMessage();
        }

        // Check User model
        try {
            $user = User::first();
            $this->info('✅ User model works');
        } catch (\Exception $e) {
            $issues[] = '❌ User model error: ' . $e->getMessage();
        }

        // Check Classroom model
        try {
            $classroom = Classroom::first();
            $this->info('✅ Classroom model works');
        } catch (\Exception $e) {
            $issues[] = '❌ Classroom model error: ' . $e->getMessage();
        }
    }

    private function checkBroadcasting(&$issues)
    {
        $this->info('📡 Checking broadcasting...');

        $driver = config('broadcasting.default');
        if (!$driver) {
            $issues[] = '❌ No broadcast driver configured';
        } else {
            $this->info("✅ Broadcast driver: {$driver}");
        }

        if ($driver === 'pusher') {
            $key = config('broadcasting.connections.pusher.key');
            $secret = config('broadcasting.connections.pusher.secret');
            $appId = config('broadcasting.connections.pusher.app_id');

            if (!$key || !$secret || !$appId) {
                $issues[] = '❌ Pusher configuration incomplete';
            } else {
                $this->info('✅ Pusher configuration complete');
            }
        }
    }

    private function displayResults($issues)
    {
        $this->newLine();

        if (empty($issues)) {
            $this->info('🎉 All checks passed! Chat system is working correctly.');
        } else {
            $this->error('⚠️ Found ' . count($issues) . ' issue(s):');
            foreach ($issues as $issue) {
                $this->line($issue);
            }

            $this->newLine();
            $this->info('To fix issues automatically, run: php artisan chat:check --fix');
        }
    }

    private function fixIssues($issues)
    {
        $this->info('🔧 Fixing issues...');

        foreach ($issues as $issue) {
            if (str_contains($issue, 'Storage link does not exist')) {
                $this->call('storage:link');
                $this->info('✅ Created storage link');
            }

            if (str_contains($issue, 'Chat attachments directory does not exist')) {
                $chatDir = storage_path('app/public/chat-attachments');
                if (!is_dir($chatDir)) {
                    mkdir($chatDir, 0755, true);
                    $this->info('✅ Created chat attachments directory');
                }
            }

            if (str_contains($issue, 'Chat attachments directory is not writable')) {
                $chatDir = storage_path('app/public/chat-attachments');
                chmod($chatDir, 0755);
                $this->info('✅ Fixed chat attachments directory permissions');
            }
        }

        $this->info('✅ Fix attempts completed');
    }
}
