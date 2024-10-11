<?php

namespace Database\Factories;

use App\Models\VaccinationRegistration;
use App\Models\VaccineCenter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VaccinationRegistration>
 */
class VaccinationRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = VaccinationRegistration::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'nid' => $this->faker->unique()->numerify('#############'), // 13 digit unique NID
            'mobile_number' => $this->faker->unique()->numerify('01#########'), // Valid 11 digit phone number starting with 01
            'vaccine_center_id' => VaccineCenter::factory(),
            'scheduled_date' => $this->faker->optional()->dateTimeBetween('+1 days', '+1 month'), // Optional scheduled date
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
