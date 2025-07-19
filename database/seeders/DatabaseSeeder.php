<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
            $this->call([
            UserSeeder::class, // Seeder utama untuk data awal
            MultiRoleTestSeeder::class, // Seeder untuk testing multi-role
        ]);
    }
}
