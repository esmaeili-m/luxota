<?php

namespace Modules\Category\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Category\App\Models\Category::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $title = $this->faker->words(2, true);
        $subtitle = $this->faker->sentence();

        return [
            'title' => [
                'en' => $title,
            ],
            'subtitle' => [
                'en' => $subtitle,
            ],
            'slug' => Str::slug($title . '-' . Str::random(5)),
            'image' => $this->faker->imageUrl(640, 480, 'category', true),
            'status' => true,
            'parent_id' => null,
        ];
    }
}

