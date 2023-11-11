<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'start_date' => now(),
            'end_date' => now()->addDays(rand(1, 10)),
            'price' => fake()->randomFloat(2, 10, 1000),
        ];
    }
}
