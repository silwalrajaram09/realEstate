<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'purpose' => $this->faker->randomElement(['buy', 'sell']),
            'type' => $this->faker->randomElement(['flat', 'house', 'land']),
            'category' => $this->faker->randomElement(['residential', 'commercial']),
            'price' => $this->faker->numberBetween(3000000, 15000000),
            'location' => $this->faker->randomElement(['Kathmandu', 'Lalitpur', 'Bhaktapur']),
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'area' => $this->faker->numberBetween(600, 3000),
            'parking' => $this->faker->boolean(),
            'water' => true,
        ];
    }
}
