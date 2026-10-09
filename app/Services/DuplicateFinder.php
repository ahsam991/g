<?php

namespace App\Services;

use App\Models\Candidate;
use Illuminate\Support\Collection;

/**
 * Warns about likely duplicates before a student is created. It never blocks
 * the save: real families share phone numbers and addresses, so the decision
 * stays with the consultant.
 */
class DuplicateFinder
{
    /** @return Collection<int, array{candidate: Candidate, reason: string, confidence: string}> */
    public function for(array $attributes, ?int $ignoreId = null): Collection
    {
        $matches = collect();

        $email = strtolower(trim($attributes['email'] ?? ''));
        $phone = $this->normalisePhone($attributes['phone'] ?? '');
        $passport = strtoupper(trim($attributes['passport_number'] ?? ''));
        $firstName = trim($attributes['first_name'] ?? '');
        $lastName = trim($attributes['last_name'] ?? '');
        $dob = $attributes['date_of_birth'] ?? null;

        $query = Candidate::query()
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->withTrashed();

        if ($passport !== '') {
            foreach ((clone $query)->where('passport_number', $passport)->get() as $candidate) {
                $matches->push(['candidate' => $candidate, 'reason' => 'Same passport number', 'confidence' => 'high']);
            }
        }

        if ($email !== '') {
            foreach ((clone $query)->whereRaw('LOWER(email) = ?', [$email])->get() as $candidate) {
                $matches->push(['candidate' => $candidate, 'reason' => 'Same email address', 'confidence' => 'high']);
            }
        }

        if ($phone !== '' && strlen($phone) >= 7) {
            // Compare the last 9 digits so country-code formatting does not hide a match.
            $tail = substr($phone, -9);

            foreach ((clone $query)->whereRaw("REPLACE(REPLACE(REPLACE(phone, ' ', ''), '-', ''), '+', '') LIKE ?", ["%{$tail}"])->get() as $candidate) {
                $matches->push(['candidate' => $candidate, 'reason' => 'Same phone number', 'confidence' => 'medium']);
            }
        }

        if ($firstName !== '' && $lastName !== '' && $dob) {
            foreach ((clone $query)->where('last_name', $lastName)->whereDate('date_of_birth', $dob)->get() as $candidate) {
                similar_text(strtolower($firstName), strtolower($candidate->first_name), $percent);

                if ($percent >= 70) {
                    $matches->push([
                        'candidate' => $candidate,
                        'reason' => 'Similar name and the same date of birth',
                        'confidence' => 'medium',
                    ]);
                }
            }
        }

        return $matches->unique(fn (array $match) => $match['candidate']->id)->values();
    }

    private function normalisePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }
}
