<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        return [
            'name' => ucwords($name),
            'slug' => str()->slug($name),
            'description' => $this->faker->optional()->sentence(12),
            'is_active' => $this->faker->boolean(90),
            'parent_id' => null,
            'position' => $this->faker->numberBetween(0, 100),
        ];
    }
}
