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
            ['area_name' => 'Trinidad Poblacion', 'collected' => false, 'type' => 'plastic']
        );
        Bin::firstOrCreate(
            ['name' => 'Trinidad 1 Elementary School'],
            ['area_name' => 'Trinidad Poblacion', 'collected' => true, 'type' => 'paper']
        );
        Bin::firstOrCreate(
            ['name' => 'Trinidad Market'],
            ['area_name' => 'Trinidad Poblacion', 'collected' => false, 'type' => 'metal']
        );
        Bin::firstOrCreate(
            ['name' => 'Trinidad Riverside'],
            ['area_name' => 'Trinidad Poblacion', 'collected' => true, 'type' => 'glass']
        );
        Bin::firstOrCreate(
            ['name' => 'Trinidad Public Plaza'],
            ['area_name' => 'Trinidad Poblacion', 'collected' => false, 'type' => 'paper']
        );
        Bin::firstOrCreate(
            ['name' => 'Trinidad Church'],
            ['area_name' => 'Trinidad Poblacion', 'collected' => true, 'type' => 'plastic']
        );
    }
}
