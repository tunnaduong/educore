<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(ClassUserSeeder::class);
        $this->call(TeacherClassroomSeeder::class);
        $this->call(AssignmentSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(ChatSeeder::class);
    }
}
