<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class TestFileUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:file-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test file upload functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing file upload functionality...');

        // Test 1: Kiểm tra thư mục storage
        $this->testStorageDirectories();

        // Test 2: Kiểm tra quyền ghi
        $this->testWritePermissions();

        // Test 3: Kiểm tra cấu hình PHP
        $this->testPhpConfiguration();

        // Test 4: Kiểm tra symbolic link
        $this->testSymbolicLink();

        $this->info('✅ File upload test completed!');
    }

    private function testStorageDirectories()
    {
        $this->info('📁 Testing storage directories...');
        
        $directories = [
            'storage/app/public',
            'storage/app/public/lessons',
            'storage/app/public/lessons/attachments',
        ];

        foreach ($directories as $dir) {
            $fullPath = base_path($dir);
            
            if (!is_dir($fullPath)) {
                $this->error("❌ Directory {$dir} does not exist!");
            } else {
                $this->info("✅ Directory {$dir} exists");
                
                if (is_writable($fullPath)) {
                    $this->info("✅ Directory {$dir} is writable");
                } else {
                    $this->error("❌ Directory {$dir} is not writable!");
                }
            }
        }
    }

    private function testWritePermissions()
    {
        $this->info('📝 Testing write permissions...');
        
        $testFile = storage_path('app/public/test-upload.txt');
        $testContent = 'Test upload content - ' . now();
        
        try {
            // Test ghi file
            file_put_contents($testFile, $testContent);
            $this->info('✅ Can write to storage/app/public');
            
            // Test đọc file
            $content = file_get_contents($testFile);
            if ($content === $testContent) {
                $this->info('✅ Can read from storage/app/public');
            } else {
                $this->error('❌ Cannot read from storage/app/public');
            }
            
            // Xóa file test
            unlink($testFile);
            $this->info('✅ Can delete from storage/app/public');
            
        } catch (\Exception $e) {
            $this->error('❌ Write permission test failed: ' . $e->getMessage());
        }
    }

    private function testPhpConfiguration()
    {
        $this->info('⚙️  Testing PHP configuration...');
        
        $configs = [
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
        ];
        
        foreach ($configs as $key => $value) {
            $this->info("   {$key}: {$value}");
        }
        
        // Kiểm tra extensions
        $extensions = ['fileinfo', 'gd', 'zip'];
        foreach ($extensions as $ext) {
            if (extension_loaded($ext)) {
                $this->info("✅ Extension {$ext} is loaded");
            } else {
                $this->warn("⚠️  Extension {$ext} is not loaded");
            }
        }
    }

    private function testSymbolicLink()
    {
        $this->info('🔗 Testing symbolic link...');
        
        $publicPath = public_path('storage');
        $storagePath = storage_path('app/public');
        
        if (file_exists($publicPath)) {
            $this->info('✅ Symbolic link exists');
            
            if (is_link($publicPath)) {
                $this->info('✅ Public/storage is a symbolic link');
                
                $target = readlink($publicPath);
                if ($target === $storagePath) {
                    $this->info('✅ Symbolic link points to correct location');
                } else {
                    $this->error('❌ Symbolic link points to wrong location: ' . $target);
                }
            } else {
                $this->error('❌ Public/storage is not a symbolic link');
            }
        } else {
            $this->error('❌ Symbolic link does not exist');
            $this->info('💡 Run: php artisan storage:link');
        }
    }
} 