<?php

namespace App\Policies;

use App\Models\Slider;
use App\Models\User;

class SliderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('settings.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('settings.manage');
    }

    public function update(User $user, Slider $slider): bool
    {
        return $user->can('settings.manage');
    }

    public function delete(User $user, Slider $slider): bool
    {
        return $user->can('settings.manage');
    }
}
