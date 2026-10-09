<?php

namespace Tests\Feature\Candidates;

use App\Models\Candidate;
use App\Models\User;
use App\Services\DuplicateFinder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuplicateDetectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
    }

    private function finder(): DuplicateFinder
    {
        return app(DuplicateFinder::class);
    }

    public function test_the_same_email_is_flagged_regardless_of_case(): void
    {
        Candidate::factory()->create(['email' => 'repeat@example.com']);

        $matches = $this->finder()->for(['email' => 'REPEAT@Example.com']);

        $this->assertCount(1, $matches);
        $this->assertSame('Same email address', $matches->first()['reason']);
        $this->assertSame('high', $matches->first()['confidence']);
    }

    public function test_the_same_passport_is_flagged(): void
    {
        Candidate::factory()->create(['passport_number' => 'AB123456']);

        $matches = $this->finder()->for(['passport_number' => 'ab123456']);

        $this->assertCount(1, $matches);
        $this->assertSame('Same passport number', $matches->first()['reason']);
    }

    public function test_a_phone_number_matches_despite_different_formatting(): void
    {
        Candidate::factory()->create(['phone' => '+8801711223344']);

        foreach (['01711223344', '880 1711 223344', '+88-01711223344'] as $variation) {
            $matches = $this->finder()->for(['phone' => $variation]);

            $this->assertCount(1, $matches, "Expected {$variation} to match.");
        }
    }

    public function test_a_similar_name_with_the_same_birthday_is_flagged(): void
    {
        Candidate::factory()->create([
            'first_name' => 'Mohammad',
            'last_name' => 'Rahman',
            'date_of_birth' => '2000-05-10',
            'email' => 'one@example.com',
        ]);

        $matches = $this->finder()->for([
            'first_name' => 'Mohammed',
            'last_name' => 'Rahman',
            'date_of_birth' => '2000-05-10',
            'email' => 'different@example.com',
        ]);

        $this->assertCount(1, $matches);
        $this->assertSame('medium', $matches->first()['confidence']);
    }

    public function test_a_different_person_is_not_flagged(): void
    {
        Candidate::factory()->create([
            'first_name' => 'Aisha',
            'last_name' => 'Khan',
            'email' => 'aisha@example.com',
            'phone' => '+8801700000001',
            'passport_number' => 'ZZ111111',
        ]);

        $matches = $this->finder()->for([
            'first_name' => 'Bilal',
            'last_name' => 'Ahmed',
            'email' => 'bilal@example.com',
            'phone' => '+8801700000002',
            'passport_number' => 'YY222222',
        ]);

        $this->assertCount(0, $matches);
    }

    public function test_the_record_being_edited_is_never_its_own_duplicate(): void
    {
        $candidate = Candidate::factory()->create(['email' => 'self@example.com']);

        $matches = $this->finder()->for(['email' => 'self@example.com'], ignoreId: $candidate->id);

        $this->assertCount(0, $matches);
    }

    public function test_one_record_matching_twice_is_reported_once(): void
    {
        Candidate::factory()->create([
            'email' => 'both@example.com',
            'passport_number' => 'DD444444',
        ]);

        $matches = $this->finder()->for([
            'email' => 'both@example.com',
            'passport_number' => 'DD444444',
        ]);

        $this->assertCount(1, $matches);
    }

    public function test_creating_a_duplicate_warns_first_then_allows_it(): void
    {
        $admin = User::factory()->admin()->create();
        Candidate::factory()->create(['email' => 'clash@example.com']);

        $payload = [
            'first_name' => 'Second',
            'last_name' => 'Person',
            'email' => 'clash@example.com',
        ];

        // First attempt: warned, nothing saved.
        $this->actingAs($admin)->post('/candidates', $payload)
            ->assertSessionHasErrors('email')
            ->assertSessionHas('duplicates');

        $this->assertSame(1, Candidate::count());

        // Confirmed attempt: saved.
        $this->actingAs($admin)->post('/candidates', $payload + ['confirm_duplicate' => '1'])
            ->assertRedirect();

        $this->assertSame(2, Candidate::count());
    }

    public function test_an_archived_or_deleted_record_still_counts_as_a_duplicate(): void
    {
        $candidate = Candidate::factory()->create(['email' => 'gone@example.com']);
        $candidate->delete();

        $matches = $this->finder()->for(['email' => 'gone@example.com']);

        $this->assertCount(1, $matches);
    }
}
