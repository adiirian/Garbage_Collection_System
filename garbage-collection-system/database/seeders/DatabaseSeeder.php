<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed roles
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'collector'],
            ['name' => 'public_user'],
        ]);

        // Seed users
        DB::table('users')->insert([
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'role_id' => 1],
            ['name' => 'Collector User', 'email' => 'collector@example.com', 'role_id' => 2],
            ['name' => 'Public User', 'email' => 'public@example.com', 'role_id' => 3],
        ]);

        // Seed bins
        DB::table('bins')->insert([
            ['location' => 'Location 1', 'status' => 'empty'],
            ['location' => 'Location 2', 'status' => 'full'],
            ['location' => 'Location 3', 'status' => 'overflowing'],
        ]);
    }
}