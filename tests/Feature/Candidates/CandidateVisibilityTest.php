<?php

namespace Tests\Feature\Candidates;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The record-level security suite. A failure here means one consultant can
 * read another's students, which the plan treats as a release blocker.
 */
class CandidateVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
    }

    public function test_a_consultant_sees_only_their_own_students(): void
    {
        $consultantA = User::factory()->consultant()->create();
        $consultantB = User::factory()->consultant()->create();

        $mine = Candidate::factory()->create(['assigned_to' => $consultantA->id, 'first_name' => 'Mine']);
        $theirs = Candidate::factory()->create(['assigned_to' => $consultantB->id, 'first_name' => 'Theirs']);

        $this->actingAs($consultantA)->get('/candidates')
            ->assertOk()
            ->assertSee($mine->uid)
            ->assertDontSee($theirs->uid);
    }

    public function test_consultant_a_cannot_open_consultant_b_s_student(): void
    {
        $consultantA = User::factory()->consultant()->create();
        $consultantB = User::factory()->consultant()->create();

        $theirs = Candidate::factory()->create(['assigned_to' => $consultantB->id]);

        $this->assertForbiddenFor($consultantA, "/candidates/{$theirs->id}");
        $this->assertForbiddenFor($consultantA, "/candidates/{$theirs->id}/edit");
    }

    public function test_consultant_a_cannot_edit_consultant_b_s_student_by_posting_directly(): void
    {
        $consultantA = User::factory()->consultant()->create();
        $consultantB = User::factory()->consultant()->create();

        $theirs = Candidate::factory()->create([
            'assigned_to' => $consultantB->id,
            'first_name' => 'Untouched',
        ]);

        $this->actingAs($consultantA)->put("/candidates/{$theirs->id}", [
            'first_name' => 'Hijacked',
            'last_name' => $theirs->last_name,
            'email' => $theirs->email,
        ])->assertForbidden();

        $this->assertSame('Untouched', $theirs->refresh()->first_name);
    }

    public function test_a_consultant_cannot_archive_someone_elses_student(): void
    {
        $consultantA = User::factory()->consultant()->create();
        $consultantB = User::factory()->consultant()->create();

        $theirs = Candidate::factory()->create(['assigned_to' => $consultantB->id]);

        $this->actingAs($consultantA)->post("/candidates/{$theirs->id}/archive")->assertForbidden();

        $this->assertNull($theirs->refresh()->archived_at);
    }

    public function test_a_consultant_cannot_reassign_a_student(): void
    {
        $consultantA = User::factory()->consultant()->create();
        $consultantB = User::factory()->consultant()->create();

        $mine = Candidate::factory()->create(['assigned_to' => $consultantA->id]);

        $this->actingAs($consultantA)->put("/candidates/{$mine->id}", [
            'first_name' => $mine->first_name,
            'last_name' => $mine->last_name,
            'email' => $mine->email,
            'assigned_to' => $consultantB->id,
        ]);

        // The edit succeeds, but ownership does not move.
        $this->assertSame($consultantA->id, $mine->refresh()->assigned_to);
    }

    public function test_bulk_actions_cannot_reach_other_consultants_students(): void
    {
        $consultantA = User::factory()->consultant()->create();
        $consultantB = User::factory()->consultant()->create();

        $theirs = Candidate::factory()->create(['assigned_to' => $consultantB->id]);

        $this->actingAs($consultantA)->post('/candidates/bulk', [
            'action' => 'archive',
            'ids' => [$theirs->id],
        ])->assertForbidden();

        $this->assertNull($theirs->refresh()->archived_at);
    }

    public function test_a_student_cannot_reach_the_crm_student_list(): void
    {
        $student = User::factory()->student()->create();

        $this->assertForbiddenFor($student, '/candidates');
        $this->assertForbiddenFor($student, '/candidates/create');
    }

    public function test_a_student_cannot_open_another_students_record(): void
    {
        $theirRecord = Candidate::factory()->create();
        $someoneElse = Candidate::factory()->create();

        $student = User::factory()->student()->create(['candidate_id' => $theirRecord->id]);

        $this->assertForbiddenFor($student, "/candidates/{$someoneElse->id}");
    }

    public function test_an_admin_sees_everything(): void
    {
        $admin = User::factory()->admin()->create();
        $a = Candidate::factory()->create();
        $b = Candidate::factory()->create();

        $this->actingAs($admin)->get('/candidates')
            ->assertOk()
            ->assertSee($a->uid)
            ->assertSee($b->uid);

        $this->actingAs($admin)->get("/candidates/{$b->id}")->assertOk();
    }

    public function test_the_query_scope_refuses_a_guest(): void
    {
        Candidate::factory()->count(3)->create();

        $this->assertSame(0, Candidate::visibleTo(null)->count());
    }
}
