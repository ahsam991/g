<?php

namespace App\Imports;

use App\Models\Candidate;
use App\Models\User;
use App\Services\CandidateAssigner;
use App\Services\DuplicateFinder;
use App\Services\ProfileCompleteness;
use App\Services\Uid;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Reads the legacy spreadsheets. Their headers vary between files, so each
 * field accepts several spellings rather than demanding one exact layout.
 * Rows that cannot be read are collected and reported, never silently dropped.
 */
class CandidateImport implements ToCollection, WithHeadingRow
{
    /** @var array<string, array<int, string>> field => accepted headings */
    private const ALIASES = [
        'first_name' => ['first_name', 'firstname', 'first', 'given_name', 'name', 'student_name', 'full_name'],
        'last_name' => ['last_name', 'lastname', 'surname', 'family_name', 'last'],
        'email' => ['email', 'email_address', 'e_mail', 'mail'],
        'phone' => ['phone', 'mobile', 'contact', 'contact_number', 'phone_number', 'cell'],
        'date_of_birth' => ['date_of_birth', 'dob', 'birth_date', 'birthday'],
        'gender' => ['gender', 'sex'],
        'nationality' => ['nationality', 'citizenship'],
        'country_of_residence' => ['country_of_residence', 'country', 'residence'],
        'passport_number' => ['passport_number', 'passport', 'passport_no', 'passport_num'],
        'preferred_destination' => ['preferred_destination', 'destination', 'country_preference', 'study_country'],
        'preferred_level' => ['preferred_level', 'level', 'study_level', 'course_level'],
        'preferred_subject' => ['preferred_subject', 'subject', 'course', 'programme', 'program'],
        'preferred_intake' => ['preferred_intake', 'intake', 'session', 'semester'],
        'source' => ['source', 'lead_source', 'referred_by', 'how_did_you_hear'],
        'notes' => ['notes', 'note', 'remarks', 'comment', 'comments'],
    ];

    /** @var array<int, array{row: int, reason: string}> */
    public array $errors = [];

    public int $imported = 0;

    public int $skipped = 0;

    public function __construct(
        private readonly User $actor,
        private readonly Uid $uid,
        private readonly DuplicateFinder $duplicates,
        private readonly ProfileCompleteness $completeness,
        private readonly CandidateAssigner $assigner,
        private readonly bool $skipDuplicates = true,
    ) {}

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            // Heading row is row 1, so data starts at 2.
            $rowNumber = $index + 2;

            try {
                $this->importRow($row->toArray(), $rowNumber);
            } catch (\Throwable $e) {
                $this->errors[] = ['row' => $rowNumber, 'reason' => $e->getMessage()];
            }
        }
    }

    private function importRow(array $row, int $rowNumber): void
    {
        $data = [];

        foreach (self::ALIASES as $field => $aliases) {
            $data[$field] = $this->pick($row, $aliases);
        }

        // A single "name" column is split on the last space.
        if (blank($data['last_name']) && filled($data['first_name']) && str_contains(trim($data['first_name']), ' ')) {
            $parts = explode(' ', trim($data['first_name']));
            $data['last_name'] = array_pop($parts);
            $data['first_name'] = implode(' ', $parts);
        }

        if (blank($data['first_name']) || blank($data['email'])) {
            $this->errors[] = ['row' => $rowNumber, 'reason' => 'Name or email missing'];
            $this->skipped++;

            return;
        }

        if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = ['row' => $rowNumber, 'reason' => "Invalid email: {$data['email']}"];
            $this->skipped++;

            return;
        }

        $data['email'] = Str::lower(trim($data['email']));
        $data['last_name'] = $data['last_name'] ?: '-';
        $data['passport_number'] = $data['passport_number'] ? Str::upper(trim($data['passport_number'])) : null;
        $data['date_of_birth'] = $this->parseDate($data['date_of_birth']);
        $data['gender'] = $this->parseGender($data['gender']);

        if ($this->skipDuplicates && $this->duplicates->for($data)->isNotEmpty()) {
            $this->errors[] = ['row' => $rowNumber, 'reason' => "Already on file: {$data['email']}"];
            $this->skipped++;

            return;
        }

        $data['uid'] = $this->uid->next('candidate');
        $data['source'] = $data['source'] ?: 'Import';

        $candidate = Candidate::create(array_filter($data, fn ($v) => $v !== null && $v !== ''));

        if ($this->actor->isConsultant()) {
            $this->assigner->assign($candidate, $this->actor, $this->actor);
        }

        $this->completeness->refresh($candidate);
        $this->imported++;
    }

    /** @param  array<int, string>  $aliases */
    private function pick(array $row, array $aliases): ?string
    {
        foreach ($aliases as $alias) {
            if (isset($row[$alias]) && trim((string) $row[$alias]) !== '') {
                return trim((string) $row[$alias]);
            }
        }

        return null;
    }

    private function parseDate(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        // Excel serial dates arrive as plain numbers.
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp(((int) $value - 25569) * 86400)->toDateString();
        }

        foreach (['Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y', 'd M Y', 'j F Y'] as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->toDateString();
            } catch (\Throwable) {
                continue;
            }
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function parseGender(?string $value): ?string
    {
        return match (Str::lower((string) $value)) {
            'm', 'male' => 'male',
            'f', 'female' => 'female',
            '' => null,
            default => 'other',
        };
    }
}
