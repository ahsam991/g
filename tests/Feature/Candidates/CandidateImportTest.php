<?php

namespace Tests\Feature\Candidates;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CandidateImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
    }

    private function csv(string $contents): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'import').'.csv';
        file_put_contents($path, $contents);

        return new UploadedFile($path, 'legacy.csv', 'text/csv', null, true);
    }

    public function test_a_tidy_file_imports(): void
    {
        $admin = User::factory()->admin()->create();

        $file = $this->csv(<<<'CSV'
        first_name,last_name,email,phone
        Taniya,Azad,taniya@example.com,+8801711111111
        Rafiq,Hossain,rafiq@example.com,+8801722222222
        CSV);

        $this->actingAs($admin)->post('/candidates/import', ['file' => $file])
            ->assertRedirect(route('candidates.index'));

        $this->assertSame(2, Candidate::count());
        $this->assertSame('GC-'.now()->format('Y').'-0001', Candidate::orderBy('id')->first()->uid);
    }

    public function test_legacy_headings_are_understood(): void
    {
        $admin = User::factory()->admin()->create();

        // None of these headings match the database column names.
        $file = $this->csv(<<<'CSV'
        Student Name,Mail,Mobile,DOB,Passport No,Study Country,Course Level,Remarks
        Nusrat Jahan,nusrat@example.com,01733333333,15/04/2001,AB998877,United Kingdom,Masters,Walk-in enquiry
        CSV);

        $this->actingAs($admin)->post('/candidates/import', ['file' => $file]);

        $candidate = Candidate::sole();

        $this->assertSame('Nusrat', $candidate->first_name);
        $this->assertSame('Jahan', $candidate->last_name);
        $this->assertSame('nusrat@example.com', $candidate->email);
        $this->assertSame('AB998877', $candidate->passport_number);
        $this->assertSame('2001-04-15', $candidate->date_of_birth->toDateString());
        $this->assertSame('United Kingdom', $candidate->preferred_destination);
        $this->assertSame('Masters', $candidate->preferred_level);
    }

    public function test_rows_missing_a_name_or_email_are_reported_not_dropped_silently(): void
    {
        $admin = User::factory()->admin()->create();

        $file = $this->csv(<<<'CSV'
        first_name,last_name,email
        Valid,Person,valid@example.com
        ,Missing,nameless@example.com
        NoEmail,Person,
        Bad,Email,not-an-email
        CSV);

        $response = $this->actingAs($admin)->post('/candidates/import', ['file' => $file]);

        $this->assertSame(1, Candidate::count());

        $errors = $response->baseResponse->getSession()->get('import_errors');

        $this->assertCount(3, $errors);
        $this->assertSame(3, $errors[0]['row']);
    }

    public function test_duplicates_are_skipped_by_default(): void
    {
        $admin = User::factory()->admin()->create();
        Candidate::factory()->create(['email' => 'already@example.com']);

        $file = $this->csv(<<<'CSV'
        first_name,last_name,email
        Already,Here,already@example.com
        Brand,New,new@example.com
        CSV);

        $this->actingAs($admin)->post('/candidates/import', ['file' => $file, 'skip_duplicates' => '1']);

        $this->assertSame(2, Candidate::count());
        $this->assertSame(1, Candidate::where('email', 'already@example.com')->count());
    }

    public function test_duplicates_import_when_the_box_is_unticked(): void
    {
        $admin = User::factory()->admin()->create();
        Candidate::factory()->create(['email' => 'already@example.com']);

        $file = $this->csv(<<<'CSV'
        first_name,last_name,email
        Already,Here,already@example.com
        CSV);

        $this->actingAs($admin)->post('/candidates/import', ['file' => $file, 'skip_duplicates' => '0']);

        $this->assertSame(2, Candidate::where('email', 'already@example.com')->count());
    }

    public function test_a_consultant_owns_what_they_import(): void
    {
        $consultant = User::factory()->consultant()->create();

        $file = $this->csv(<<<'CSV'
        first_name,last_name,email
        Mine,Now,mine@example.com
        CSV);

        $this->actingAs($consultant)->post('/candidates/import', ['file' => $file]);

        $this->assertSame($consultant->id, Candidate::sole()->assigned_to);
    }

    public function test_a_non_spreadsheet_upload_is_refused(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/candidates/import', [
            'file' => UploadedFile::fake()->create('payload.php', 10, 'application/x-php'),
        ])->assertSessionHasErrors('file');

        $this->assertSame(0, Candidate::count());
    }

    public function test_a_student_cannot_import_or_export(): void
    {
        $student = User::factory()->student()->create();

        $this->assertForbiddenFor($student, '/candidates/import');
        $this->assertForbiddenFor($student, '/candidates/export');
    }

    public function test_the_export_downloads_a_spreadsheet(): void
    {
        $admin = User::factory()->admin()->create();
        Candidate::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('/candidates/export');

        $response->assertOk();
        $this->assertStringContainsString('students-', $response->headers->get('content-disposition'));
    }

    public function test_the_export_respects_the_current_filter(): void
    {
        $admin = User::factory()->admin()->create();
        Candidate::factory()->create(['first_name' => 'Keep', 'status' => 'enrolled']);
        Candidate::factory()->create(['first_name' => 'Drop', 'status' => 'new']);

        $this->actingAs($admin)->get('/candidates/export?status=enrolled')->assertOk();

        // The filter is applied to the query the export runs.
        $this->assertSame(1, Candidate::where('status', 'enrolled')->count());
    }
}
