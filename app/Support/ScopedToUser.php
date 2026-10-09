<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Record-level visibility. Admin sees everything, a consultant sees only what
 * is assigned to them, a student sees only their own record.
 *
 * A model using this trait must define how it links to a consultant and to a
 * student by overriding the two column methods when the defaults do not fit.
 */
trait ScopedToUser
{
    /** Column holding the consultant this record is assigned to. */
    public function consultantColumn(): string
    {
        return 'assigned_to';
    }

    /** Column holding the candidate (student) this record belongs to. */
    public function candidateColumn(): ?string
    {
        return 'candidate_id';
    }

    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isConsultant()) {
            return $query->where($this->qualifyColumn($this->consultantColumn()), $user->id);
        }

        $candidateColumn = $this->candidateColumn();

        if ($user->isStudent() && $candidateColumn && $user->candidate_id) {
            return $query->where($this->qualifyColumn($candidateColumn), $user->candidate_id);
        }

        return $query->whereRaw('1 = 0');
    }
}
