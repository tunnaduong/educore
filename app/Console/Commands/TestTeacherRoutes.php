<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Models\Lesson;
use App\Models\User;

class TestTeacherRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:teacher-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test teacher routes functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing teacher routes...');

        // Test 1: Kiểm tra routes
        $this->testRoutes();

        // Test 2: Kiểm tra lessons
        $this->testLessons();

        // Test 3: Kiểm tra teacher permissions
        $this->testTeacherPermissions();

        $this->info('✅ Teacher routes test completed!');
    }

    private function testRoutes()
    {
        $this->info('🔗 Testing routes...');
        
        $routes = [
            'teacher.lessons.index' => '/teacher/lessons',
            'teacher.lessons.create' => '/teacher/lessons/create',
            'teacher.lessons.show' => '/teacher/lessons/{lesson}',
            'teacher.lessons.edit' => '/teacher/lessons/{lesson}/edit',
        ];

        foreach ($routes as $name => $path) {
            $route = Route::getRoutes()->getByName($name);
            if ($route) {
                $this->info("✅ Route '{$name}' exists");
            } else {
                $this->error("❌ Route '{$name}' not found!");
            }
        }
    }

    private function testLessons()
    {
        $this->info('📚 Testing lessons...');
        
        $lessons = Lesson::all();
        $this->info("Found {$lessons->count()} lessons");
        
        if ($lessons->isNotEmpty()) {
            $lesson = $lessons->first();
            $this->info("Sample lesson: ID={$lesson->id}, Title='{$lesson->title}'");
            
            // Test route generation
            try {
                $showUrl = route('teacher.lessons.show', $lesson);
                $editUrl = route('teacher.lessons.edit', $lesson);
                $this->info("✅ Show URL: {$showUrl}");
                $this->info("✅ Edit URL: {$editUrl}");
            } catch (\Exception $e) {
                $this->error("❌ Route generation failed: " . $e->getMessage());
            }
        } else {
            $this->warn("⚠️  No lessons found in database");
        }
    }

    private function testTeacherPermissions()
    {
        $this->info('👨‍🏫 Testing teacher permissions...');
        
        $teachers = User::where('role', 'teacher')->get();
        $this->info("Found {$teachers->count()} teachers");
        
        if ($teachers->isNotEmpty()) {
            $teacher = $teachers->first();
            $this->info("Sample teacher: ID={$teacher->id}, Name='{$teacher->name}'");
            
            $teachingClassrooms = $teacher->teachingClassrooms;
            $this->info("Teacher has {$teachingClassrooms->count()} teaching classrooms");
            
            if ($teachingClassrooms->isNotEmpty()) {
                foreach ($teachingClassrooms as $classroom) {
                    $this->info("   - {$classroom->name} (ID: {$classroom->id})");
                }
            } else {
                $this->warn("⚠️  Teacher has no teaching classrooms assigned");
            }
        } else {
            $this->warn("⚠️  No teachers found in database");
        }
    }
} 