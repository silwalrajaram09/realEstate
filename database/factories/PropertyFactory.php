<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement([
            'flat',
            'house',
            'land',
            'commercial',
            'office',
            'warehouse'
        ]);

        $category = match ($type) {
            'flat', 'house', 'land' => 'residential',
            'commercial', 'office' => 'commercial',
            'warehouse' => 'industrial',
            default => 'residential'
        };

        $price = $this->faker->numberBetween(3000000, 25000000);
        $area = $this->faker->numberBetween(500, 5000);

        return [

            // Basic Info
            'title' => ucfirst($type) . ' in ' . $this->faker->randomElement(['Kathmandu', 'Lalitpur', 'Bhaktapur']),
            'description' => $this->faker->paragraph(3),
            'purpose' => $this->faker->randomElement(['buy', 'rent']),
            'type' => $type,
            'category' => $category,

            // Pricing & Location
            'price' => $price,
            'min_lease_months' => $this->faker->optional()->numberBetween(3, 12),
            'location' => $this->faker->randomElement(['Kathmandu', 'Lalitpur', 'Bhaktapur']),
            'latitude' => $this->faker->latitude(27.65, 27.75),
            'longitude' => $this->faker->longitude(85.25, 85.40),

            // Size & Image
            'area' => $area,
            'image' => null,

            // Residential Fields
            'bedrooms' => in_array($type, ['flat', 'house']) ? $this->faker->numberBetween(1, 5) : null,
            'bathrooms' => in_array($type, ['flat', 'house']) ? $this->faker->numberBetween(1, 3) : null,
            'floor_no' => $this->faker->optional()->numberBetween(1, 10),
            'year_built' => $this->faker->optional()->numberBetween(1995, 2024),

            // Commercial Fields
            'total_floors' => $this->faker->optional()->numberBetween(1, 15),
            'parking_spaces' => $this->faker->optional()->numberBetween(1, 10),

            // Land Fields
            'road_access' => $this->faker->optional()->numberBetween(10, 30),
            'facing' => $this->faker->optional()->randomElement(['East', 'West', 'North', 'South']),
            'land_shape' => $this->faker->optional()->randomElement(['Rectangle', 'Square', 'Irregular']),
            'plot_number' => $this->faker->optional()->bothify('PLOT-###'),

            // Industrial Fields
            'clear_height' => $this->faker->optional()->randomFloat(2, 10, 40),
            'loading_docks' => $this->faker->optional()->numberBetween(1, 5),
            'power_supply' => $this->faker->optional()->numberBetween(50, 500),

            // Amenities
            'parking' => $this->faker->boolean(),
            'water' => true,
            'electricity' => $this->faker->boolean(),
            'security' => $this->faker->boolean(),
            'garden' => $this->faker->boolean(),
            'balcony' => $this->faker->boolean(),
            'gym' => $this->faker->boolean(),
            'lift' => $this->faker->boolean(),
            'ac' => $this->faker->boolean(),
            'fire_safety' => $this->faker->boolean(),
            'internet' => $this->faker->boolean(),
            'loading_area' => $this->faker->boolean(),

            // Availability & Status
            'available_from' => $this->faker->optional()->date(),
            'status' => 'approved',

            // Ownership
            'ownership_type' => $this->faker->randomElement(['Freehold', 'Leasehold']),
            'contact_number' => $this->faker->numerify('98########'),

            // User
            'user_id' => User::factory(),
        ];
    }
}
