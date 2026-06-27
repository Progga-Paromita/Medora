<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'is_role' => 1, // Admin
            'status' => 1,  // Active
            'is_deleted' => 0
        ]);

        // Create Staff User
        User::create([
            'name' => 'Staff',
            'last_name' => 'User',
            'email' => 'staff@example.com',
            'password' => Hash::make('password123'),
            'is_role' => 2, // Staff
            'status' => 1,  // Active
            'is_deleted' => 0
        ]);
    }
}
