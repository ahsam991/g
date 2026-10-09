<?php

namespace App\Policies;

use App\Models\Candidate;
use App\Models\User;

/**
 * Admin is handled by Gate::before, so these methods decide what a consultant
 * or a student may do. Visibility is enforced twice: here, and again by the
 * visibleTo query scope, so a missing scope cannot leak a record.
 */
class CandidatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('candidates.view');
    }

    public function view(User $user, Candidate $candidate): bool
    {
        if ($user->isStudent()) {
            return $user->candidate_id === $candidate->id;
        }

        return $user->hasPermission('candidates.view')
            && $this->owns($user, $candidate);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('candidates.create');
    }

    public function update(User $user, Candidate $candidate): bool
    {
        if ($user->isStudent()) {
            return $user->candidate_id === $candidate->id;
        }

        return $user->hasPermission('candidates.update')
            && $this->owns($user, $candidate);
    }

    public function assign(User $user): bool
    {
        return $user->hasPermission('candidates.assign');
    }

    public function archive(User $user, Candidate $candidate): bool
    {
        return $user->hasPermission('candidates.delete')
            && $this->owns($user, $candidate);
    }

    public function import(User $user): bool
    {
        return $user->hasPermission('candidates.import');
    }

    /** A consultant may only touch what is assigned to them. */
    private function owns(User $user, Candidate $candidate): bool
    {
        if (! $user->isConsultant()) {
            return true;
        }

        return $candidate->assigned_to === $user->id;
    }
}
