<?php

namespace App\Policies;

use App\Models\User;

class CmsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('catalogue.view');
    }

    public function view(User $user, $model): bool
    {
        return $user->can('catalogue.view');
    }

    public function create(User $user): bool
    {
        return $user->can('catalogue.manage');
    }

    public function update(User $user, $model): bool
    {
        return $user->can('catalogue.manage');
    }

    public function delete(User $user, $model): bool
    {
        return $user->can('catalogue.manage');
    }
}
