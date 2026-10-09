<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\CandidateAssignment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Moves a student to a consultant and keeps the ownership history, which the
 * commission and staff-performance reports depend on.
 */
class CandidateAssigner
{
    public function assign(Candidate $candidate, ?User $consultant, ?User $actor = null): void
    {
        if ($candidate->assigned_to === $consultant?->id) {
            return;
        }

        DB::transaction(function () use ($candidate, $consultant, $actor) {
            CandidateAssignment::where('candidate_id', $candidate->id)
                ->whereNull('unassigned_at')
                ->update(['unassigned_at' => now()]);

            $candidate->update(['assigned_to' => $consultant?->id]);

            if ($consultant) {
                CandidateAssignment::create([
                    'candidate_id' => $candidate->id,
                    'user_id' => $consultant->id,
                    'assigned_by' => $actor?->id,
                    'assigned_at' => now(),
                ]);
            }
        });
    }

    /** Spreads a batch across consultants, lightest workload first. */
    public function assignMany(array $candidateIds, User $consultant, ?User $actor = null): int
    {
        $candidates = Candidate::whereIn('id', $candidateIds)->get();

        foreach ($candidates as $candidate) {
            $this->assign($candidate, $consultant, $actor);
        }

        return $candidates->count();
    }

    /** The consultant currently holding the fewest open students. */
    public function leastLoadedConsultant(?int $branchId = null): ?User
    {
        return User::query()
            ->whereRelation('role', 'slug', Role::CONSULTANT)
            ->where('status', 'active')
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->withCount(['assignedCandidates' => fn ($q) => $q->whereNull('archived_at')])
            ->orderBy('assigned_candidates_count')
            ->first();
    }
}
