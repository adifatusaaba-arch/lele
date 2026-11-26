<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'label' => fake()->randomElement(['Rumah', 'Warung Pecel Lele', 'Kolam', 'Kantor']),
            'recipient_name' => fake()->name(),
            'phone' => '08' . fake()->numerify('##########'),
            'province' => fake()->randomElement(['Jawa Barat', 'Jawa Tengah', 'DKI Jakarta', 'Banten']),
            'city' => fake()->city(),
            'district' => 'Kecamatan ' . fake()->lastName(),
            'postal_code' => fake()->numerify('#####'),
            'full_address' => fake()->streetAddress(),
            'notes' => fake()->optional()->sentence(),
            'is_default' => false,
        ];
    }
}
