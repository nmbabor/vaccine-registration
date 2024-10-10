<?php

namespace Database\Factories;

use App\Models\VaccineCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VaccineCenter>
 */
class VaccineCenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    //protected $model = VaccineCenter::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Vaccination Center',
            'address' => $this->faker->address,
            'daily_limit' => $this->faker->numberBetween(20, 50), // Random capacity between 50 and 200
        ];
    }
}
