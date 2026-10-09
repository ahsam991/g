<?php

namespace Database\Seeders;

use App\Models\LeadSource;
use Illuminate\Database\Seeder;

class LeadSourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = ['Facebook', 'Google', 'Instagram', 'Referral', 'Website', 'Walk-in', 'YouTube', 'LinkedIn'];

        foreach ($sources as $name) {
            LeadSource::firstOrCreate(['name' => $name]);
        }
    }
}
