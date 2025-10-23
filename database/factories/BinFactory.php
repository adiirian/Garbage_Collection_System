<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bin;

class BinFactory extends Factory
{
    protected $model = Bin::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word . ' Bin',
            'latitude' => $this->faker->latitude(9.72, 9.74),
            'longitude' => $this->faker->longitude(124.15, 124.18),
            'level' => 'empty',
            'notes' => null,
        ];
    }
}
