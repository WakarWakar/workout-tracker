<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Create a standard test user. Password is `password` (set by the factory).
        User::factory()->asUser()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create an admin user. Credentials: email `admin@example.com`, password `password`.
        User::factory()->asAdmin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
    }
}
