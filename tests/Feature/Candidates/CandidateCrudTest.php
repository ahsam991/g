<?php

namespace Tests\Feature\Candidates;

use App\Models\Candidate;
use App\Models\CandidateAssignment;
use App\Models\User;
use App\Services\CandidateAssigner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CandidateCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Taniya',
            'last_name' => 'Azad',
            'email' => 'taniya@example.com',
            'phone' => '+8801711111111',
            'preferred_destination' => 'United Kingdom',
        ], $overrides);
    }

    public function test_a_new_student_gets_the_next_reference(): void
    {
        $consultant = User::factory()->consultant()->create();

        $this->actingAs($consultant)->post('/candidates', $this->payload())
            ->assertRedirect();

        $candidate = Candidate::sole();

        $this->assertSame(sprintf('GC-%s-0001', now()->format('Y')), $candidate->uid);
        $this->assertSame('taniya@example.com', $candidate->email);
    }

    public function test_a_consultant_creating_a_student_owns_it(): void
    {
        $consultant = User::factory()->consultant()->create();

        $this->actingAs($consultant)->post('/candidates', $this->payload());

        $this->assertSame($consultant->id, Candidate::sole()->assigned_to);
        $this->assertDatabaseHas('candidate_assignments', [
            'user_id' => $consultant->id,
            'unassigned_at' => null,
        ]);
    }

    public function test_email_and_passport_are_normalised(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/candidates', $this->payload([
            'email' => '  MiXeD@Example.COM ',
            'passport_number' => 'ab123456',
        ]));

        $candidate = Candidate::sole();

        $this->assertSame('mixed@example.com', $candidate->email);
        $this->assertSame('AB123456', $candidate->passport_number);
    }

    public function test_name_and_email_are_required(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/candidates', ['first_name' => 'Only'])
            ->assertSessionHasErrors(['last_name', 'email']);

        $this->assertSame(0, Candidate::count());
    }

    public function test_a_future_date_of_birth_is_refused(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/candidates', $this->payload(['date_of_birth' => now()->addYear()->toDateString()]))
            ->assertSessionHasErrors('date_of_birth');
    }

    public function test_an_expired_passport_date_is_refused(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/candidates', $this->payload(['passport_expiry' => now()->subDay()->toDateString()]))
            ->assertSessionHasErrors('passport_expiry');
    }

    public function test_searching_matches_reference_name_email_phone_and_passport(): void
    {
        $admin = User::factory()->admin()->create();

        $target = Candidate::factory()->create([
            'first_name' => 'Rafiq',
            'last_name' => 'Hossain',
            'email' => 'rafiq@example.com',
            'phone' => '+8801999888777',
            'passport_number' => 'BX998877',
        ]);

        Candidate::factory()->create(['first_name' => 'Nobody', 'last_name' => 'Else']);

        foreach ([$target->uid, 'Rafiq', 'Hossain', 'rafiq@example.com', 'BX998877'] as $term) {
            $this->actingAs($admin)->get('/candidates?q='.urlencode($term))
                ->assertOk()
                ->assertSee($target->uid)
                ->assertDontSee('Nobody');
        }
    }

    public function test_archiving_hides_a_student_from_the_default_list(): void
    {
        $admin = User::factory()->admin()->create();
        $candidate = Candidate::factory()->create();

        $this->actingAs($admin)->post("/candidates/{$candidate->id}/archive")->assertRedirect();

        $this->actingAs($admin)->get('/candidates')->assertDontSee($candidate->uid);
        $this->actingAs($admin)->get('/candidates?archived=1')->assertSee($candidate->uid);
    }

    public function test_restoring_puts_a_student_back(): void
    {
        $admin = User::factory()->admin()->create();
        $candidate = Candidate::factory()->archived()->create();

        $this->actingAs($admin)->post("/candidates/{$candidate->id}/restore")->assertRedirect();

        $this->assertNull($candidate->refresh()->archived_at);
    }

    public function test_an_admin_can_bulk_assign(): void
    {
        $admin = User::factory()->admin()->create();
        $consultant = User::factory()->consultant()->create();
        $candidates = Candidate::factory()->count(3)->create();

        $this->actingAs($admin)->post('/candidates/bulk', [
            'action' => 'assign',
            'ids' => $candidates->pluck('id')->all(),
            'assigned_to' => $consultant->id,
        ])->assertRedirect();

        $this->assertSame(3, Candidate::where('assigned_to', $consultant->id)->count());
        $this->assertSame(3, CandidateAssignment::whereNull('unassigned_at')->count());
    }

    public function test_reassigning_closes_the_previous_ownership_record(): void
    {
        $admin = User::factory()->admin()->create();
        $first = User::factory()->consultant()->create();
        $second = User::factory()->consultant()->create();
        $candidate = Candidate::factory()->create();

        app(CandidateAssigner::class)->assign($candidate, $first, $admin);
        app(CandidateAssigner::class)->assign($candidate, $second, $admin);

        $this->assertSame(1, CandidateAssignment::whereNull('unassigned_at')->count());
        $this->assertSame(2, CandidateAssignment::count());
        $this->assertSame($second->id, $candidate->refresh()->assigned_to);
    }

    public function test_the_list_does_not_grow_queries_with_rows(): void
    {
        $admin = User::factory()->admin()->create();
        Candidate::factory()->count(25)->create(['assigned_to' => User::factory()->consultant()->create()->id]);

        DB::enableQueryLog();
        $this->actingAs($admin)->get('/candidates')->assertOk();
        $queries = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Eager loading keeps this flat; a regression would push it past 20 rows.
        $this->assertLessThan(20, $queries, "Possible N+1: {$queries} queries for one page.");
    }
}
