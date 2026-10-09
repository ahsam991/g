<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/** Exports whatever the current filter selected, not the whole table. */
class CandidateExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private readonly Builder $query) {}

    public function query(): Builder
    {
        return $this->query->with(['consultant:id,name', 'branch:id,name']);
    }

    public function headings(): array
    {
        return [
            'Reference', 'First name', 'Last name', 'Email', 'Phone',
            'Date of birth', 'Nationality', 'Passport', 'Destination', 'Level',
            'Subject', 'Intake', 'Status', 'Source', 'Consultant', 'Office',
            'Profile %', 'Created',
        ];
    }

    public function map($candidate): array
    {
        return [
            $candidate->uid,
            $candidate->first_name,
            $candidate->last_name,
            $candidate->email,
            $candidate->phone,
            $candidate->date_of_birth?->format('Y-m-d'),
            $candidate->nationality,
            $candidate->passport_number,
            $candidate->preferred_destination,
            $candidate->preferred_level,
            $candidate->preferred_subject,
            $candidate->preferred_intake,
            $candidate->status,
            $candidate->source,
            $candidate->consultant?->name,
            $candidate->branch?->name,
            $candidate->completion_percent,
            $candidate->created_at?->format('Y-m-d'),
        ];
    }
}
