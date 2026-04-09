<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserRecommendation;
use App\Services\UserSuggestionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateRecommendations extends Command
{
    protected $signature = 'recommendations:calculate';
    protected $description = 'Pre-calculate hybrid recommendation scores for all active users';

    public function handle(UserSuggestionService $suggestionService): void
    {
        $this->info('Starting recommendation calculations...');

        // We only calculate for users who have been active recently
        // To keep it simple, let's take all users for now
        $users = User::all();

        $this->withProgressBar($users, function ($user) use ($suggestionService) {
            // Get real-time suggestions (which contains the calculated scores)
            $result = $suggestionService->calculateRealtimeSuggestions($user);
            $properties = $result['properties'];

            if ($properties->isNotEmpty()) {
                // Clear old recommendations for this user
                UserRecommendation::where('user_id', $user->id)->delete();

                // Prepare data for batch insert
                $data = $properties->map(function ($property) use ($user) {
                    return [
                        'user_id' => $user->id,
                        'property_id' => $property->id,
                        'score' => $property->hybrid_score ?? 0,
                        'reasons' => json_encode([
                            'content' => $property->hybrid_content_score ?? 0,
                            'collab' => $property->hybrid_collab_score ?? 0,
                            'pop' => $property->hybrid_pop_score ?? 0,
                        ]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();

                UserRecommendation::insert($data);
            }
        });

        $this->newLine();
        $this->info('Recommendations updated successfully.');
    }
}
