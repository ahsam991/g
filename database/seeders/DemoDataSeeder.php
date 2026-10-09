<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Candidate;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Local-only bulk data for load checking. Never run in production.
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->error('Refusing to seed demo data in production.');

            return;
        }

        $consultantRole = Role::where('slug', Role::CONSULTANT)->value('id');
        $branches = Branch::pluck('id');

        // Re-runnable: reuse the demo consultants if they already exist.
        $consultants = collect(range(1, 5))->map(fn ($n) => User::firstOrCreate(
            ['email' => "consultant{$n}@gconsultancy.co.uk"],
            [
                'name' => "Consultant {$n}",
                'password' => 'password',
                'role_id' => $consultantRole,
                'branch_id' => $branches->random(),
                'email_verified_at' => now(),
            ],
        ));

        $count = (int) env('DEMO_CANDIDATES', 1000);

        Candidate::factory()
            ->count($count)
            ->create([
                'assigned_to' => fn () => $consultants->random()->id,
                'branch_id' => fn () => $branches->random(),
            ]);

        $this->command?->info("Seeded {$count} students across {$consultants->count()} consultants.");
    }
}
