<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Schema;

/**
 * Scores how complete a student profile is, using the weighting on the
 * Candidate model. The figure drives the portal progress ring and the CRM
 * list, so it is stored on the record rather than recomputed per row.
 */
class ProfileCompleteness
{
    /** @return array<string, int> section => points earned */
    public function breakdown(Candidate $candidate): array
    {
        $weights = Candidate::COMPLETION_WEIGHTS;

        return [
            'personal' => $this->ratio([
                $candidate->first_name,
                $candidate->last_name,
                $candidate->date_of_birth,
                $candidate->nationality,
                $candidate->passport_number,
            ]) * $weights['personal'],

            'contact' => $this->ratio([
                $candidate->email,
                $candidate->phone,
                $candidate->addresses()->exists() ?: null,
                $candidate->emergencyContacts()->exists() ?: null,
            ]) * $weights['contact'],

            'academic' => ($candidate->qualifications()->exists() ? 1 : 0) * $weights['academic'],

            'english' => ($candidate->englishTests()->exists() ? 1 : 0) * $weights['english'],

            // Filled in by the documents slice; a profile without documents
            // can still reach 65%, which matches the spec's wizard behaviour.
            'documents' => $this->documentScore($candidate) * $weights['documents'],
        ];
    }

    public function percent(Candidate $candidate): int
    {
        return (int) round(array_sum($this->breakdown($candidate)));
    }

    /** Recalculates and stores the figure without firing an audit entry storm. */
    public function refresh(Candidate $candidate): int
    {
        $percent = $this->percent($candidate);

        if ($candidate->completion_percent !== $percent) {
            $candidate->forceFill(['completion_percent' => $percent])->saveQuietly();
        }

        return $percent;
    }

    /** @param  array<int, mixed>  $values */
    private function ratio(array $values): float
    {
        $filled = count(array_filter($values, fn ($v) => ! blank($v)));

        return count($values) === 0 ? 0.0 : $filled / count($values);
    }

    /**
     * Share of required document types that have been verified. Returns 0
     * until the documents slice exists, so the table is checked defensively.
     */
    private function documentScore(Candidate $candidate): float
    {
        if (! Schema::hasTable('candidate_documents')) {
            return 0.0;
        }

        $required = DocumentType::where('is_required', true)->count();

        if ($required === 0) {
            return 0.0;
        }

        $verified = $candidate->documents()
            ->where('status', 'verified')
            ->whereRelation('documentType', 'is_required', true)
            ->distinct('document_type_id')
            ->count('document_type_id');

        return min(1.0, $verified / $required);
    }
}
