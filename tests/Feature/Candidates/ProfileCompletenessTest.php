<?php

namespace Tests\Feature\Candidates;

use App\Models\Candidate;
use App\Services\ProfileCompleteness;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileCompletenessTest extends TestCase
{
    use RefreshDatabase;

    private function service(): ProfileCompleteness
    {
        return app(ProfileCompleteness::class);
    }

    public function test_the_weights_add_up_to_one_hundred(): void
    {
        $this->assertSame(100, array_sum(Candidate::COMPLETION_WEIGHTS));
    }

    public function test_a_bare_record_scores_low(): void
    {
        $candidate = Candidate::factory()->create([
            'date_of_birth' => null,
            'nationality' => null,
            'passport_number' => null,
            'phone' => null,
        ]);

        $percent = $this->service()->percent($candidate);

        $this->assertLessThan(20, $percent);
    }

    public function test_personal_details_earn_their_full_weight(): void
    {
        $candidate = Candidate::factory()->create();

        $breakdown = $this->service()->breakdown($candidate);

        $this->assertSame(
            (float) Candidate::COMPLETION_WEIGHTS['personal'],
            round($breakdown['personal'], 2)
        );
    }

    public function test_adding_a_qualification_raises_the_score(): void
    {
        $candidate = Candidate::factory()->create();
        $before = $this->service()->percent($candidate);

        $candidate->qualifications()->create([
            'level' => 'Bachelors',
            'institution' => 'Khulna University',
            'subject' => 'Business',
        ]);

        $this->assertSame(
            $before + Candidate::COMPLETION_WEIGHTS['academic'],
            $this->service()->percent($candidate->refresh())
        );
    }

    public function test_adding_an_english_test_raises_the_score(): void
    {
        $candidate = Candidate::factory()->create();
        $before = $this->service()->percent($candidate);

        $candidate->englishTests()->create(['type' => 'IELTS', 'overall' => 6.5]);

        $this->assertSame(
            $before + Candidate::COMPLETION_WEIGHTS['english'],
            $this->service()->percent($candidate->refresh())
        );
    }

    public function test_a_full_profile_without_documents_caps_at_sixty_five(): void
    {
        $candidate = Candidate::factory()->create();
        $candidate->addresses()->create(['line_1' => '1 Road', 'city' => 'Khulna', 'country' => 'Bangladesh']);
        $candidate->emergencyContacts()->create(['name' => 'Parent', 'phone' => '+8801700000000']);
        $candidate->qualifications()->create(['level' => 'Bachelors', 'institution' => 'KU']);
        $candidate->englishTests()->create(['type' => 'IELTS', 'overall' => 7.0]);

        // 100 minus the 35 points that documents carry.
        $this->assertSame(65, $this->service()->percent($candidate->refresh()));
    }

    public function test_refresh_stores_the_figure_on_the_record(): void
    {
        $candidate = Candidate::factory()->create(['completion_percent' => 0]);

        $percent = $this->service()->refresh($candidate);

        $this->assertSame($percent, $candidate->refresh()->completion_percent);
        $this->assertGreaterThan(0, $percent);
    }
}
