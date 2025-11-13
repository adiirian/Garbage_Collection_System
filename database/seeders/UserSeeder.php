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
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Default Admin',
                'password' => Hash::make('admin47'),
                'role_id' => $adminRole->id,
            ]
        );

        // Create default collector user
        User::updateOrCreate(
            ['email' => 'collector@collector.com'],
            [
                'name' => 'Default Collector',
                'password' => Hash::make('collector'),
                'role_id' => $collectorRole->id,
                'address' => '123 Collector Street',
                'age' => 30,
                'daily_salary' => 50.00,
                'assigned_area' => 'Downtown',
                'status' => 'off_duty',
            ]
        );

        // Create default public user
        User::updateOrCreate(
            ['email' => 'resident@resident.com'],
            [
                'name' => 'Default Resident User',
                'password' => Hash::make('resident'),
                'role_id' => $publicRole->id,
            ]
        );

        $this->command->info('Default users created successfully.');
    }
}
