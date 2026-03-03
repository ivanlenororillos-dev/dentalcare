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
        $this->call(TeethMasterSeeder::class);

        User::firstOrCreate(
        ['email' => 'admin@dentalcare.com'],
        [
            'name' => 'Admin User',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]
        );
    }
}
