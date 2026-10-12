<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\CandidateDocument;
use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CandidateDocumentFactory extends Factory
{
    protected $model = CandidateDocument::class;

    public function definition(): array
    {
        $uuid = Str::uuid()->toString();

        return [
            'uuid' => $uuid,
            'candidate_id' => Candidate::factory(),
            'document_type_id' => DocumentType::factory(),
            'file_name' => 'test-document.pdf',
            'file_path' => "candidates/1/{$uuid}.pdf",
            'file_size' => fake()->numberBetween(50000, 5000000),
            'mime_type' => 'application/pdf',
            'status' => 'pending',
            'version' => 1,
        ];
    }
}
