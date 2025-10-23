<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bin;

class BinSeeder extends Seeder
{
    public function run()
    {
        Bin::firstOrCreate(
            ['name' => 'Trinidad Municipal College Campus'],
            ['latitude' => 10.0754, 'longitude' => 124.3379, 'level' => 'overflowing']
        );
        Bin::firstOrCreate(
            ['name' => 'Trinidad Elementary School'],
            ['latitude' => 10.0813, 'longitude' => 124.3438, 'level' => 'partial']
        );
        Bin::firstOrCreate(
            ['name' => 'Trinidad Cemetery'],
            ['latitude' => 10.0853, 'longitude' => 124.34307, 'level' => 'full']
        );
        Bin::firstOrCreate(
            ['name' => 'Trinidad Market'],
            ['latitude' => 10.0790, 'longitude' => 124.3439, 'level' => 'overflowing']
        );
        Bin::firstOrCreate(
            ['name' => 'Trinidad Riverside'],
            ['latitude' => 9.77091, 'longitude' => 24.49714, 'level' => 'full']
        );
    }
}
