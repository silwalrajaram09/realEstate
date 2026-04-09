<?php

namespace App\Livewire;

use App\Models\Enquiry;
use App\Models\Property;
use App\Services\PropertyRecommendationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PropertyEnquiry extends Component
{
    public $property_id;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $message = '';
    
    public $showSuccess = false;
    public $loading = false;

    public function mount($propertyId)
    {
        $this->property_id = $propertyId;
        
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone ?? '';
        }

        // Default message
        $property = Property::find($propertyId);
        if ($property) {
            $this->message = "Hi, I'm interested in '{$property->title}'. Could you please provide more details?";
        }
    }

    public function setTemplate($type)
    {
        $property = Property::find($this->property_id);
        $title = $property ? $property->title : 'this property';

        $this->message = match($type) {
            'avail' => "Is '{$title}' still available for sale/rent?",
            'visit' => "I would like to schedule a visit for '{$title}'. When are you available?",
            'price' => "I have a question regarding the price of '{$title}'. Is it negotiable?",
            default => $this->message
        };
    }

    public function submit(PropertyRecommendationService $recommendationService)
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => ['required', 'string', 'regex:/^(98|97)\d{8}$/'],
            'message' => 'required|string|min:10|max:1000',
        ], [
            'phone.regex' => 'Please enter a valid  mobile number (starts with 98 or 97 and must be exactly 10 digits).',
            'message.min' => 'Please provide a bit more detail (at least 10 characters).',
        ]);

        $this->loading = true;

        $property = Property::findOrFail($this->property_id);
        
        // ── CALCULATE LEAD SCORE (The Smart Feature) ──
        $matchScore = 0;
        $matchDetails = [];

        if (Auth::check()) {
            // Calculate how well this property fits this specific user
            $user = Auth::user();
            $matchScore = $recommendationService->calculateMatchScore($user, $property);
            $matchDetails = [
                'type' => 'authenticated',
                'calculated_at' => now()->toDateTimeString(),
            ];
        }

        Enquiry::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
            'property_id' => $this->property_id,
            'buyer_id' => Auth::id(),
            'seller_id' => $property->user_id, // assuming user_id is the seller
            'status' => 'new',
            'match_score' => $matchScore,
            'match_details' => $matchDetails,
        ]);

        $this->loading = false;
        $this->showSuccess = true;
        
        // Reset message but keep user info
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.property-enquiry');
    }
}
