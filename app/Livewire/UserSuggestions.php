<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\UserSuggestionService;
use Illuminate\Support\Facades\Auth;

class UserSuggestions extends Component
{
    public $query = '';
    public $strategyLabel = 'Recommended for you';

    // Results are fetched in render as they depend on strategy and query
    public function render(UserSuggestionService $suggestionService)
    {
        $user = Auth::user();
        
        $results = $suggestionService->getSuggestions($user, $this->query);
        
        $this->strategyLabel = $results['strategyLabel'];
        $properties = $results['properties'];

        return view('livewire.user-suggestions', [
            'properties' => $properties,
        ]);
    }

    public function updatedQuery()
    {
        // Resets or re-fetches results automatically
    }
}
