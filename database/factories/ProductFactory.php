<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id,
            'name' => $this->faker->productName() ?? $this->faker->words(3, true),
            'sku' => strtoupper($this->faker->bothify('PRD-#####')),
            'description' => $this->faker->paragraph(),
            'quantity' => $this->faker->numberBetween(0, 500),
            'price' => $this->faker->randomFloat(2, 1, 9999),
            'is_active' => $this->faker->boolean(90),
            'meta' => [
                'brand' => $this->faker->company(),
                'tags' => $this->faker->words(3),
            ],
            'published_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
