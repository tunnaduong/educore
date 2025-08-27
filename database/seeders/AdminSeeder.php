<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@educore.me',
            'phone' => 'admin',
            'password' => bcrypt('Admin@12'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}
