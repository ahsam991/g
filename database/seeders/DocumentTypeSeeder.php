<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Passport', 'slug' => 'passport', 'description' => 'Valid passport with at least 6 months validity', 'is_required' => true, 'sort_order' => 1],
            ['name' => 'Academic Certificate', 'slug' => 'academic-certificate', 'description' => 'Highest academic qualification certificate', 'is_required' => true, 'sort_order' => 2],
            ['name' => 'Academic Transcript', 'slug' => 'academic-transcript', 'description' => 'Official transcript from your institution', 'is_required' => true, 'sort_order' => 3],
            ['name' => 'IELTS / TOEFL Score', 'slug' => 'english-test', 'description' => 'English language proficiency test result', 'is_required' => true, 'sort_order' => 4],
            ['name' => 'CV / Resume', 'slug' => 'cv', 'description' => 'Up-to-date curriculum vitae', 'is_required' => true, 'sort_order' => 5],
            ['name' => 'Statement of Purpose', 'slug' => 'sop', 'description' => 'Personal statement explaining your motivation', 'is_required' => true, 'sort_order' => 6],
            ['name' => 'Letter of Recommendation', 'slug' => 'lor', 'description' => 'Academic or professional reference letter', 'is_required' => false, 'sort_order' => 7],
            ['name' => 'Bank Statement', 'slug' => 'bank-statement', 'description' => 'Financial evidence showing sufficient funds', 'is_required' => true, 'sort_order' => 8],
            ['name' => 'Visa Application Form', 'slug' => 'visa-application', 'description' => 'Completed visa application form', 'is_required' => false, 'sort_order' => 9],
            ['name' => 'Offer Letter', 'slug' => 'offer-letter', 'description' => 'University offer or CAS letter', 'is_required' => false, 'sort_order' => 10],
            ['name' => 'Medical Certificate', 'slug' => 'medical-certificate', 'description' => 'Health check or TB test result', 'is_required' => false, 'sort_order' => 11],
            ['name' => 'Photograph', 'slug' => 'photograph', 'description' => 'Passport-size photograph meeting visa requirements', 'is_required' => false, 'sort_order' => 12, 'allowed_mimes' => 'jpg,jpeg,png'],
            ['name' => 'Other', 'slug' => 'other', 'description' => 'Any other supporting document', 'is_required' => false, 'sort_order' => 99],
        ];

        foreach ($types as $type) {
            DocumentType::updateOrCreate(
                ['slug' => $type['slug']],
                $type,
            );
        }
    }
}
