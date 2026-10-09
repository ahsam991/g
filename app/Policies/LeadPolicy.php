<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('leads.view');
    }

    public function view(User $user, Lead $lead): bool
    {
        if (! $user->hasPermission('leads.view')) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        // Consultants can only see their own leads
        return $lead->assigned_to === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('leads.manage');
    }

    public function update(User $user, Lead $lead): bool
    {
        if (! $user->hasPermission('leads.manage')) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $lead->assigned_to === $user->id;
    }

    public function convert(User $user, Lead $lead): bool
    {
        if (! $user->hasPermission('leads.convert')) {
            return false;
        }

        if ($lead->isConverted()) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $lead->assigned_to === $user->id;
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $user->isAdmin();
    }
}
