<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Alert;
use App\Models\Bin;

class AlertFactory extends Factory
{
    protected $model = Alert::class;

    public function definition()
    {
        return [
            'bin_id' => Bin::factory(),
            'type' => 'level_change',
            'level' => null,
            'status' => 'open',
            'reported_by' => null,
            'message' => $this->faker->sentence(),
        ];
    }
}
