<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PropertySeeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        $testUser = \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        $this->call(PropertySeeder::class);

        // Seed views for collaborative recommendation testing
        $this->seedPropertyViews($testUser);
    }

    private function seedPropertyViews($testUser): void
    {
        $properties = \App\Models\Property::approved()->get();
        if ($properties->count() < 10) return;

        // 5 demo users
        $demoUsers = \App\Models\User::factory(5)->create();
        $demoUsers->push($testUser);

        // Realistic distribution: Users tend to view a similar cluster of properties
        $targetProperties = $properties->random(min(15, $properties->count())); 
        
        foreach ($demoUsers as $user) {
            // Each user views ~8 properties
            $viewedProperties = $targetProperties->random(rand(6, 10));
            
            foreach ($viewedProperties as $property) {
                \App\Models\PropertyView::create([
                    'user_id' => $user->id,
                    'property_id' => $property->id,
                    'created_at' => now()->subDays(rand(0, 5)),
                    'updated_at' => now(),
                ]);
                
                // Boost views_count for popularity score testing
                $property->increment('views_count');
            }
        }
    }
}
