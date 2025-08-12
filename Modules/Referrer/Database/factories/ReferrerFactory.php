<?php

namespace Modules\Referrer\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Referrer\App\Models\Referrer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Referrer>
 */
class ReferrerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Referrer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(2, true),
            'status' => $this->faker->boolean(80), // 80% chance of being true
        ];
    }
} 