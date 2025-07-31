<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CheckStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:check {--fix : Tự động sửa lỗi storage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra và sửa lỗi storage cho file upload';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Kiểm tra cấu hình storage...');

        // Kiểm tra symbolic link
        $this->checkSymbolicLink();

        // Kiểm tra thư mục storage
        $this->checkStorageDirectories();

        // Kiểm tra quyền ghi
        $this->checkWritePermissions();

        $this->info('✅ Hoàn thành kiểm tra storage!');
    }

    private function checkSymbolicLink()
    {
        $this->info('🔗 Kiểm tra symbolic link...');
        
        $publicPath = public_path('storage');
        $storagePath = storage_path('app/public');

        if (!file_exists($publicPath)) {
            $this->warn('⚠️  Symbolic link chưa được tạo!');
            
            if ($this->option('fix')) {
                $this->info('🔧 Đang tạo symbolic link...');
                $this->call('storage:link');
                $this->info('✅ Đã tạo symbolic link!');
            } else {
                $this->info('💡 Chạy lệnh với --fix để tự động tạo symbolic link');
            }
        } else {
            $this->info('✅ Symbolic link đã tồn tại');
        }
    }

    private function checkStorageDirectories()
    {
        $this->info('📁 Kiểm tra thư mục storage...');
        
        $directories = [
            'storage/app/public',
            'storage/app/public/lessons',
            'storage/app/public/lessons/attachments',
            'storage/app/public/assignments',
            'storage/app/public/chat-attachments',
        ];

        foreach ($directories as $dir) {
            $fullPath = base_path($dir);
            
            if (!is_dir($fullPath)) {
                $this->warn("⚠️  Thư mục {$dir} chưa tồn tại!");
                
                if ($this->option('fix')) {
                    $this->info("🔧 Đang tạo thư mục {$dir}...");
                    File::makeDirectory($fullPath, 0755, true);
                    $this->info("✅ Đã tạo thư mục {$dir}!");
                }
            } else {
                $this->info("✅ Thư mục {$dir} đã tồn tại");
            }
        }
    }

    private function checkWritePermissions()
    {
        $this->info('📝 Kiểm tra quyền ghi...');
        
        $testFile = storage_path('app/public/test.txt');
        
        try {
            File::put($testFile, 'test');
            File::delete($testFile);
            $this->info('✅ Quyền ghi hoạt động bình thường');
        } catch (\Exception $e) {
            $this->error('❌ Không thể ghi file vào storage: ' . $e->getMessage());
            $this->info('💡 Kiểm tra quyền thư mục storage/app/public');
        }
    }
} 