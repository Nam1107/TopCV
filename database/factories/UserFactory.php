<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

    $sex = $this->faker->randomElement(['Nam', 'Nữ']);
    $role = $this->faker->randomElement([1,2,3,4]);
        return [
            'name' => fake()->name(),
            'sex' => $sex,
            'phone'=> fake()->phoneNumber(),
            'address'=>fake()->address(),
            'province'=>fake()->city(),
            'district'=>fake()->state(),
            'avatar'=>'https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.nj.com%2Fentertainment%2F2020%2F05%2Feveryones-posting-their-facebook-avatar-how-to-make-yours-even-if-it-looks-nothing-like-you.html&psig=AOvVaw0ORbck3Xz-oUKq6-Alu4ux&ust=1700070234042000&source=images&cd=vfe&opi=89978449&ved=0CBEQjRxqFwoTCKDIiKmFxIIDFQAAAAAdAAAAABAE',
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make(12345678),//'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            // 'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
