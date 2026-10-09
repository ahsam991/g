<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Converts a qualified lead into a candidate (student) record,
 * copying the relevant data and linking both records.
 */
class LeadConverter
{
    public function __construct(
        private readonly Uid $uid,
        private readonly CandidateAssigner $assigner,
    ) {}

    public function convert(Lead $lead, User $actor): Candidate
    {
        return DB::transaction(function () use ($lead, $actor) {
            $candidate = Candidate::create([
                'uid' => $this->uid->next('candidate'),
                'first_name' => $lead->first_name,
                'last_name' => $lead->last_name ?? '',
                'email' => $lead->email,
                'phone' => $lead->phone,
                'country_of_residence' => $lead->country,
                'preferred_destination' => $lead->preferred_destination,
                'preferred_level' => $lead->preferred_level,
                'preferred_subject' => $lead->preferred_subject,
                'notes' => $lead->notes,
                'source' => $lead->source?->name ?? 'Website',
                'branch_id' => $lead->branch_id,
                'status' => 'active',
            ]);

            // Assign to the same consultant
            if ($lead->assigned_to) {
                $this->assigner->assign($candidate, User::find($lead->assigned_to), $actor);
            }

            // Mark lead as converted
            $lead->update([
                'status' => 'converted',
                'candidate_id' => $candidate->id,
                'converted_at' => now(),
            ]);

            // Link enquiry to lead if exists
            if ($lead->enquiry_id && $lead->enquiry) {
                $lead->enquiry->update(['lead_id' => $lead->id]);
            }

            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $actor->id,
                'type' => 'conversion',
                'description' => "Converted to student {$candidate->uid}",
                'meta' => ['candidate_id' => $candidate->id],
            ]);

            return $candidate;
        });
    }
}
