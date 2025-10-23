<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
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
        $this->call(RoleSeeder::class);
        $this->call(BinSeeder::class);
        $this->call(UserSeeder::class);

        // Create alerts for bins that are not empty
        $bins = \App\Models\Bin::where('level', '!=', 'empty')->get();
        foreach ($bins as $bin) {
            \App\Models\Alert::firstOrCreate(
                [
                    'bin_id' => $bin->id,
                    'type' => 'level_change',
                    'level' => $bin->level,
                ],
                [
                    'message' => "Bin {$bin->name} is {$bin->level}",
                    'status' => 'open',
                ]
            );
        }
    }
}
