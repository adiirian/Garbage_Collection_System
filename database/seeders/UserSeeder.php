<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        $adminRole = Role::where('name', 'Admin')->first();
        $collectorRole = Role::where('name', 'Collector')->first();
        $publicRole = Role::where('name', 'Public')->first();

        if (!$adminRole || !$collectorRole || !$publicRole) {
            $this->command->error('Roles not found. Please run RoleSeeder first.');
            return;
        }

        // Create default admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Default Admin',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        // Create default collector user
        User::updateOrCreate(
            ['email' => 'collector@example.com'],
            [
                'name' => 'Default Collector',
                'password' => Hash::make('password'),
                'role_id' => $collectorRole->id,
            ]
        );

        // Create default public user
        User::updateOrCreate(
            ['email' => 'public@example.com'],
            [
                'name' => 'Default Public User',
                'password' => Hash::make('password'),
                'role_id' => $publicRole->id,
            ]
        );

        $this->command->info('Default users created successfully.');
    }
}
