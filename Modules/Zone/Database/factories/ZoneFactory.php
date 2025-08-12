<?php

namespace Modules\Zone\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Zone\App\Models\Zone;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Zone>
 */
class ZoneFactory extends Factory
{
    protected $model = Zone::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->city() . ' Zone',
            'description' => fake()->paragraph(),
            'status' => fake()->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the zone is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the zone is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
} 