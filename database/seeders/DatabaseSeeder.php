<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the root admin
        User::factory()->create([
            'id' => Str::uuid(),
            'name' => 'Root Admin',
            'email' => 'admin@shaghal.com',
            'password' => bcrypt('123123'),
            'role' => 'admin'
        ]);
    }
}
