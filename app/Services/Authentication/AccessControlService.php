<?php

namespace App\Services\Authentication;

use App\Models\User;

class AccessControlService
{
    public function isSuperAdmin(User $user): bool
    {
        return $user->role?->name === 'super-admin';
    }

    public function permitted(User $user, array $permissions = [], bool $any = false): bool
    {
        $permissions = array_unique($permissions);

        if (!$user->role || $user->role->permissions->isEmpty()) {
            return false;
        }

        $rolePermissions = $user->role->permissions->pluck('name')->toArray();
        $matches = array_intersect($permissions, $rolePermissions);

        return $any ? count($matches) > 0 : count($matches) === count($permissions);
    }
}
