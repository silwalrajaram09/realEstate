<?php

namespace App\Livewire;

use App\Models\Property;
use App\Services\PropertyRecommendationService;
use Livewire\Component;
use Livewire\WithPagination;

class PropertyListing extends Component
{
    use WithPagination;

    // Filter properties
    public $q = '';
    public $type = '';
    public $purpose = '';
    public $category = '';
    public $min_price = '';
    public $max_price = '';
    public $bedrooms = '';
    public $bathrooms = '';
    public $sort = 'latest';
    public $per_page = 12;
    public $lat = '';
    public $lng = '';

    // Track if search is active
    public $hasTextSearch = false;

    public function mount($purpose = '')
    {
        $this->purpose = $purpose;
    }

    // Reset page on search/filter update
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['q', 'type', 'purpose', 'category', 'min_price', 'max_price', 'bedrooms', 'bathrooms', 'lat', 'lng'])) {
            $this->resetPage();
        }
        $this->hasTextSearch = !empty($this->q);
    }

    public function render(PropertyRecommendationService $recommendationService)
    {
        $filters = [
            'q' => $this->q,
            'type' => $this->type,
            'purpose' => $this->purpose,
            'category' => $this->category,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'sort' => $this->sort,
            'per_page' => $this->per_page,
            'lat' => $this->lat,
            'lng' => $this->lng,
        ];

        $properties = $recommendationService->search($filters, $this->per_page, $this->getPage());

        return view('livewire.property-listing', [
            'properties' => $properties,
            'hasFilters' => $this->hasActiveFilters(),
        ]);
    }

    public function clearFilters()
    {
        $this->reset(['q', 'type', 'purpose', 'category', 'min_price', 'max_price', 'bedrooms', 'bathrooms', 'lat', 'lng']);
        $this->resetPage();
    }

    public function setPriceRange($min, $max)
    {
        $this->min_price = $min;
        $this->max_price = $max;
        $this->resetPage();
    }

    public function togglePurpose($value)
    {
        $this->purpose = ($this->purpose === $value) ? '' : $value;
        $this->resetPage();
    }

    public function toggleBedrooms($value)
    {
        $this->bedrooms = ($this->bedrooms === (string)$value) ? '' : (string)$value;
        $this->resetPage();
    }

    protected function hasActiveFilters()
    {
        return !empty($this->q) || !empty($this->type) || !empty($this->purpose) || 
               !empty($this->category) || !empty($this->min_price) || 
               !empty($this->max_price) || !empty($this->bedrooms) || 
               !empty($this->lat);
    }
}
