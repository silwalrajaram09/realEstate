<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class AdminUserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $role = 'all';

    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user && $user->role !== 'admin') {
            $user->delete();
            session()->flash('error', 'User deleted.');
        }
    }

    public function render()
    {
        $query = User::query()
            ->when($this->role !== 'all', fn($q) => $q->where('role', $this->role))
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                                            ->orWhere('email', 'like', "%{$this->search}%"));

        return view('livewire.admin-user-manager', [
            'users' => $query->latest()->paginate(15),
        ]);
    }
}
