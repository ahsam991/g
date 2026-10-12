<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Candidate;
use App\Models\CandidateDocument;
use App\Models\DocumentType;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BranchSeeder;
use Database\Seeders\DocumentTypeSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $consultant;

    private User $otherConsultant;

    private User $student;

    private Candidate $candidate;

    private DocumentType $docType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(BranchSeeder::class);
        $this->seed(DocumentTypeSeeder::class);

        $branch = Branch::first();

        $this->admin = User::factory()->create([
            'role_id' => Role::where('slug', 'admin')->first()->id,
            'branch_id' => $branch->id,
        ]);

        $this->consultant = User::factory()->create([
            'role_id' => Role::where('slug', 'consultant')->first()->id,
            'branch_id' => $branch->id,
        ]);

        $this->otherConsultant = User::factory()->create([
            'role_id' => Role::where('slug', 'consultant')->first()->id,
            'branch_id' => $branch->id,
        ]);

        $this->student = User::factory()->create([
            'role_id' => Role::where('slug', 'student')->first()->id,
            'branch_id' => $branch->id,
        ]);

        $this->candidate = Candidate::factory()->create([
            'assigned_to' => $this->consultant->id,
            'user_id' => $this->student->id,
            'branch_id' => $branch->id,
        ]);

        $this->docType = DocumentType::where('slug', 'passport')->first();
    }

    public function test_admin_can_view_documents_index(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.documents.index'))
            ->assertOk();
    }

    public function test_consultant_can_view_documents_index(): void
    {
        $this->actingAs($this->consultant)
            ->get(route('admin.documents.index'))
            ->assertOk();
    }

    public function test_admin_can_upload_document(): void
    {
        Storage::fake('private');

        $this->actingAs($this->admin)
            ->post(route('admin.documents.store', $this->candidate), [
                'document_type_id' => $this->docType->id,
                'files' => [UploadedFile::fake()->create('passport.pdf', 500, 'application/pdf')],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('candidate_documents', [
            'candidate_id' => $this->candidate->id,
            'document_type_id' => $this->docType->id,
            'status' => 'pending',
        ]);
    }

    public function test_consultant_can_upload_for_own_candidate(): void
    {
        Storage::fake('private');

        $this->actingAs($this->consultant)
            ->post(route('admin.documents.store', $this->candidate), [
                'document_type_id' => $this->docType->id,
                'files' => [UploadedFile::fake()->create('passport.pdf', 500, 'application/pdf')],
            ])
            ->assertRedirect();
    }

    public function test_consultant_cannot_upload_for_other_candidates(): void
    {
        Storage::fake('private');

        $this->actingAs($this->otherConsultant)
            ->post(route('admin.documents.store', $this->candidate), [
                'document_type_id' => $this->docType->id,
                'files' => [UploadedFile::fake()->create('passport.pdf', 500, 'application/pdf')],
            ])
            ->assertForbidden();
    }

    public function test_admin_can_verify_document(): void
    {
        $doc = CandidateDocument::factory()->create([
            'candidate_id' => $this->candidate->id,
            'document_type_id' => $this->docType->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.documents.verify', $doc))
            ->assertRedirect();

        $this->assertEquals('verified', $doc->fresh()->status);
    }

    public function test_admin_can_reject_document_with_reason(): void
    {
        $doc = CandidateDocument::factory()->create([
            'candidate_id' => $this->candidate->id,
            'document_type_id' => $this->docType->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.documents.reject', $doc), [
                'rejection_reason' => 'Passport is expired',
            ])
            ->assertRedirect();

        $doc->refresh();
        $this->assertEquals('rejected', $doc->status);
        $this->assertEquals('Passport is expired', $doc->rejection_reason);
    }

    public function test_reject_requires_reason(): void
    {
        $doc = CandidateDocument::factory()->create([
            'candidate_id' => $this->candidate->id,
            'document_type_id' => $this->docType->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.documents.reject', $doc), [])
            ->assertSessionHasErrors('rejection_reason');
    }

    public function test_admin_can_download_document(): void
    {
        Storage::fake('private');
        Storage::disk('private')->put('candidates/1/test.pdf', 'fake-content');

        $doc = CandidateDocument::factory()->create([
            'candidate_id' => $this->candidate->id,
            'document_type_id' => $this->docType->id,
            'file_path' => 'candidates/1/test.pdf',
            'file_name' => 'test.pdf',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.documents.download', $doc))
            ->assertOk();

        $this->assertDatabaseHas('document_access_logs', [
            'candidate_document_id' => $doc->id,
            'user_id' => $this->admin->id,
            'action' => 'download',
        ]);
    }

    // --- IDOR Tests ---

    public function test_consultant_cannot_download_other_consultant_candidate_doc(): void
    {
        Storage::fake('private');
        Storage::disk('private')->put('candidates/1/test.pdf', 'fake-content');

        $doc = CandidateDocument::factory()->create([
            'candidate_id' => $this->candidate->id,
            'document_type_id' => $this->docType->id,
            'file_path' => 'candidates/1/test.pdf',
        ]);

        $this->actingAs($this->otherConsultant)
            ->get(route('admin.documents.download', $doc))
            ->assertForbidden();
    }

    public function test_student_cannot_access_other_student_documents(): void
    {
        $otherStudent = User::factory()->create([
            'role_id' => Role::where('slug', 'student')->first()->id,
            'branch_id' => Branch::first()->id,
        ]);

        $otherCandidate = Candidate::factory()->create([
            'assigned_to' => $this->consultant->id,
            'user_id' => $otherStudent->id,
            'branch_id' => Branch::first()->id,
        ]);

        Storage::fake('private');
        Storage::disk('private')->put('candidates/2/secret.pdf', 'secret-content');

        $doc = CandidateDocument::factory()->create([
            'candidate_id' => $otherCandidate->id,
            'document_type_id' => $this->docType->id,
            'file_path' => 'candidates/2/secret.pdf',
        ]);

        $this->actingAs($this->student)
            ->get(route('admin.documents.download', $doc))
            ->assertForbidden();
    }

    public function test_double_extension_file_is_rejected(): void
    {
        Storage::fake('private');

        $this->actingAs($this->admin)
            ->post(route('admin.documents.store', $this->candidate), [
                'document_type_id' => $this->docType->id,
                'files' => [UploadedFile::fake()->create('passport.pdf.php', 500, 'application/pdf')],
            ])
            ->assertSessionHasErrors('files.0');
    }

    public function test_upload_new_version_preserves_history(): void
    {
        Storage::fake('private');

        // Upload v1
        $this->actingAs($this->admin)
            ->post(route('admin.documents.store', $this->candidate), [
                'document_type_id' => $this->docType->id,
                'files' => [UploadedFile::fake()->create('passport-v1.pdf', 300, 'application/pdf')],
            ]);

        $doc = CandidateDocument::where('candidate_id', $this->candidate->id)
            ->where('document_type_id', $this->docType->id)
            ->first();
        $this->assertEquals(1, $doc->version);

        // Upload v2
        $this->actingAs($this->admin)
            ->post(route('admin.documents.store', $this->candidate), [
                'document_type_id' => $this->docType->id,
                'files' => [UploadedFile::fake()->create('passport-v2.pdf', 400, 'application/pdf')],
            ]);

        $doc->refresh();
        $this->assertEquals(2, $doc->version);
        $this->assertEquals(1, $doc->versions()->count());
    }

    public function test_student_cannot_verify_documents(): void
    {
        $doc = CandidateDocument::factory()->create([
            'candidate_id' => $this->candidate->id,
            'document_type_id' => $this->docType->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->student)
            ->post(route('admin.documents.verify', $doc))
            ->assertForbidden();
    }

    public function test_only_admin_can_reverse_rejection(): void
    {
        $doc = CandidateDocument::factory()->create([
            'candidate_id' => $this->candidate->id,
            'document_type_id' => $this->docType->id,
            'status' => 'rejected',
            'rejection_reason' => 'Bad quality',
        ]);

        $this->actingAs($this->consultant)
            ->post(route('admin.documents.reverse', $doc))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->post(route('admin.documents.reverse', $doc))
            ->assertRedirect();

        $this->assertEquals('pending', $doc->fresh()->status);
    }
}
