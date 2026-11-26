<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = 'Lele ' . fake()->unique()->words(2, true);
        $unit = fake()->randomElement(['kg', 'ekor']);

        return [
            'category_id' => Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 999),
            'description' => fake()->paragraph(),
            'price' => $unit === 'kg' ? fake()->numberBetween(22, 40) * 1000 : fake()->numberBetween(3, 25) * 100,
            'unit' => $unit,
            'fish_size' => $unit === 'kg' ? '8-10 ekor/kg' : '7-9 cm',
            'min_order' => $unit === 'kg' ? 1 : 50,
            'stock' => fake()->numberBetween(20, 500),
            'is_active' => true,
            'is_featured' => fake()->boolean(30),
        ];
    }
}
