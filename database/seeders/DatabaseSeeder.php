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
            AdminUserSeeder::class,
            GradeSeeder::class,
            QuestionSeeder::class,
            ManualSeeder::class,
            RolesAndPermissionsSeeder::class,
            CleanupRolesSeeder::class,
            UsersSeeder::class,
            ProductsSeeder::class,
        ]);
    }
}
