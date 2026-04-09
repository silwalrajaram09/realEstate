<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Property;

class AdminPropertyManager extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all';
    public $selected = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setStatus($status)
    {
        $this->status = $status;
        $this->resetPage();
    }

    public function approve($id)
    {
        Property::where('id', $id)->update(['status' => 'approved']);
        session()->flash('success', 'Property approved successfully.');
    }

    public function reject($id)
    {
        Property::where('id', $id)->update(['status' => 'rejected']);
        session()->flash('error', 'Property rejected.');
    }

    public function deleteProperty($id)
    {
        Property::where('id', $id)->delete();
        session()->flash('error', 'Property permanently deleted.');
    }

    public function bulkApprove()
    {
        Property::whereIn('id', $this->selected)->update(['status' => 'approved']);
        $this->selected = [];
        session()->flash('success', 'Selected properties approved.');
    }

    public function bulkDelete()
    {
        Property::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        session()->flash('error', 'Selected properties deleted permanently.');
    }

    public function render()
    {
        $query = Property::with('seller')
            ->when($this->status !== 'all', fn($q) => $q->where('status', $this->status))
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%")
                                            ->orWhere('location', 'like', "%{$this->search}%"));

        $hasPending = false;
        if (!empty($this->selected)) {
            $hasPending = Property::whereIn('id', $this->selected)
                ->where('status', '!=', 'approved')
                ->exists();
        }

        return view('livewire.admin-property-manager', [
            'properties' => $query->latest()->paginate(10),
            'totalCount' => Property::count(),
            'pendingCount' => Property::where('status', 'pending')->count(),
            'hasPendingSelected' => $hasPending,
        ]);
    }
}
