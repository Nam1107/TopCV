<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_company' => fake()->name(),
            'phone_company' => fake()->phoneNumber(),
            'address_company' => fake()->address(),
            'city_company' => fake()->city(),
            'email_company' => fake()->unique()->safeEmail(),

        ];
    }
}