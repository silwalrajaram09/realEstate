<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    /**
     * Anyone logged in can view property lists (optional)
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Anyone logged in can view a property
     */
    public function view(User $user, Property $property): bool
    {
        return true;
    }

    /**
     * Only sellers (owners) and admins can create properties
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['owner', 'admin']);
    }

    /**
     * Seller can update own property
     * Admin can update any property
     */
    public function update(User $user, Property $property): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'owner'
            && $property->user_id === $user->id;
    }

    /**
     * Seller can delete own property
     * Admin can delete any property
     */
    public function delete(User $user, Property $property): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'owner'
            && $property->user_id === $user->id;
    }

    /**
     * Disable restore (optional)
     */
    public function restore(User $user, Property $property): bool
    {
        return false;
    }

    /**
     * Disable force delete (optional)
     */
    public function forceDelete(User $user, Property $property): bool
    {
        return false;
    }
}
