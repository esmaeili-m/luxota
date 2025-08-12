<?php

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'phone' => fake()->unique()->phoneNumber(),
            'description' => fake()->paragraph(),
            'avatar' => null,
            'website' => [
                'website' => fake()->url(),
                'instagram' => fake()->userName(),
                'telegram' => fake()->userName(),
            ],
            'address' => fake()->address(),
            'luxota_website' => fake()->url(),
            'status' => true,
            'country_code' => fake()->countryCode(),
            'whatsapp_number' => fake()->phoneNumber(),
            'role_id' => 1,
            'zone_id' => 1,
            'city_id' => 1,
            'rank_id' => 2,
            'referrer_id' => 1,
            'branch_id' => 1,
            'parent_id' => null,
            'remember_token' => Str::random(10),
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

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
} 