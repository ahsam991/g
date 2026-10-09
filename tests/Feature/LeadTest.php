<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\User;
use Database\Seeders\BranchSeeder;
use Database\Seeders\LeadSourceSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(BranchSeeder::class);
        $this->seed(LeadSourceSeeder::class);
    }

    // -- Public enquiry creates a lead ----------------------------------------

    public function test_public_enquiry_creates_lead(): void
    {
        $response = $this->post(route('enquiry.store'), [
            'name' => 'Tanvir Hasan',
            'email' => 'tanvir@example.com',
            'phone' => '+880 1700 000000',
            'country' => 'United Kingdom',
            'message' => 'I want to study in the UK.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('enquiry_sent');

        $this->assertDatabaseHas('website_enquiries', ['email' => 'tanvir@example.com']);
        $this->assertDatabaseHas('leads', ['email' => 'tanvir@example.com']);

        $lead = Lead::where('email', 'tanvir@example.com')->first();
        $this->assertNotNull($lead);
        $this->assertEquals('Tanvir', $lead->first_name);
        $this->assertEquals('Hasan', $lead->last_name);
        $this->assertStringStartsWith('LD-', $lead->uid);
        $this->assertEquals('new', $lead->status);
    }

    public function test_honeypot_blocks_bot_submissions(): void
    {
        $response = $this->post(route('enquiry.store'), [
            'name' => 'Bot User',
            'email' => 'bot@spam.com',
            'website' => 'http://spam.com', // honeypot filled
            'message' => 'Buy stuff',
        ]);

        // The EnquiryRequest should reject this
        $response->assertRedirect();
        $this->assertDatabaseMissing('leads', ['email' => 'bot@spam.com']);
    }

    // -- Lead CRUD for admin --------------------------------------------------

    public function test_admin_can_list_all_leads(): void
    {
        $admin = User::factory()->admin()->create();
        Lead::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('leads.index'));
        $response->assertOk();
        $response->assertSeeText('Leads');
    }

    public function test_admin_can_create_a_lead(): void
    {
        $admin = User::factory()->admin()->create();
        $source = LeadSource::where('name', 'Walk-in')->first();

        $response = $this->actingAs($admin)->post(route('leads.store'), [
            'first_name' => 'Test',
            'last_name' => 'Lead',
            'email' => 'test.lead@example.com',
            'phone' => '+44 7777 000000',
            'country' => 'Bangladesh',
            'preferred_destination' => 'United Kingdom',
            'lead_source_id' => $source->id,
        ]);

        $lead = Lead::where('email', 'test.lead@example.com')->first();
        $this->assertNotNull($lead);
        $response->assertRedirect(route('leads.show', $lead));
    }

    public function test_admin_can_view_any_lead(): void
    {
        $admin = User::factory()->admin()->create();
        $lead = Lead::factory()->create();

        $this->actingAs($admin)->get(route('leads.show', $lead))->assertOk();
    }

    public function test_admin_can_edit_a_lead(): void
    {
        $admin = User::factory()->admin()->create();
        $lead = Lead::factory()->create();

        $response = $this->actingAs($admin)->put(route('leads.update', $lead), [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => $lead->email,
            'status' => 'contacted',
        ]);

        $response->assertRedirect(route('leads.show', $lead));
        $this->assertDatabaseHas('leads', ['id' => $lead->id, 'first_name' => 'Updated', 'status' => 'contacted']);

        // Activity logged
        $this->assertDatabaseHas('lead_activities', [
            'lead_id' => $lead->id,
            'type' => 'status_change',
        ]);
    }

    // -- Lead conversion ------------------------------------------------------

    public function test_consultant_can_convert_own_lead_to_student(): void
    {
        $consultant = User::factory()->consultant()->create();
        $lead = Lead::factory()->create(['assigned_to' => $consultant->id]);

        $response = $this->actingAs($consultant)->post(route('leads.convert', $lead));

        $lead->refresh();
        $this->assertEquals('converted', $lead->status);
        $this->assertNotNull($lead->candidate_id);
        $this->assertNotNull($lead->converted_at);

        $response->assertRedirect(route('candidates.show', $lead->candidate));
    }

    public function test_converted_lead_cannot_be_converted_again(): void
    {
        $consultant = User::factory()->consultant()->create();
        $candidate = Candidate::factory()->create();
        $lead = Lead::factory()->converted()->create([
            'candidate_id' => $candidate->id,
            'assigned_to' => $consultant->id,
        ]);

        $response = $this->actingAs($consultant)->post(route('leads.convert', $lead));
        $response->assertForbidden();
    }

    // -- IDOR: consultant cannot see another consultant's lead ----------------

    public function test_consultant_cannot_see_another_consultants_lead(): void
    {
        $consultantA = User::factory()->consultant()->create();
        $consultantB = User::factory()->consultant()->create();
        $lead = Lead::factory()->create(['assigned_to' => $consultantA->id]);

        $this->actingAs($consultantB)->get(route('leads.show', $lead))->assertForbidden();
        $this->actingAs($consultantB)->put(route('leads.update', $lead), [
            'first_name' => 'Hack',
            'email' => 'hack@test.com',
        ])->assertForbidden();
        $this->actingAs($consultantB)->post(route('leads.convert', $lead))->assertForbidden();
    }

    public function test_consultant_only_sees_own_leads_in_list(): void
    {
        $consultantA = User::factory()->consultant()->create();
        $consultantB = User::factory()->consultant()->create();
        Lead::factory()->create(['assigned_to' => $consultantA->id, 'first_name' => 'MyLead']);
        Lead::factory()->create(['assigned_to' => $consultantB->id, 'first_name' => 'OtherLead']);

        $response = $this->actingAs($consultantA)->get(route('leads.index'));
        $response->assertOk();
        $response->assertSeeText('MyLead');
        $response->assertDontSeeText('OtherLead');
    }

    // -- Student cannot access leads ------------------------------------------

    public function test_student_cannot_access_leads(): void
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student)->get(route('leads.index'))->assertForbidden();
    }

    // -- Activity logging -----------------------------------------------------

    public function test_consultant_can_add_activity_to_own_lead(): void
    {
        $consultant = User::factory()->consultant()->create();
        $lead = Lead::factory()->create(['assigned_to' => $consultant->id]);

        $response = $this->actingAs($consultant)->post(route('leads.activity', $lead), [
            'type' => 'call',
            'description' => 'Discussed UK universities',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('lead_activities', [
            'lead_id' => $lead->id,
            'type' => 'call',
            'description' => 'Discussed UK universities',
        ]);
    }

    // -- Duplicate detection --------------------------------------------------

    public function test_duplicate_email_is_flagged(): void
    {
        Lead::factory()->create(['email' => 'dupe@example.com']);

        $this->post(route('enquiry.store'), [
            'name' => 'Dupe Test',
            'email' => 'dupe@example.com',
            'message' => 'Another enquiry',
        ]);

        $leads = Lead::where('email', 'dupe@example.com')->get();
        $this->assertCount(2, $leads);
        $this->assertTrue($leads->last()->is_duplicate);
    }

    // -- Assignment -----------------------------------------------------------

    public function test_admin_can_reassign_a_lead(): void
    {
        $admin = User::factory()->admin()->create();
        $consultantA = User::factory()->consultant()->create();
        $consultantB = User::factory()->consultant()->create();
        $lead = Lead::factory()->create(['assigned_to' => $consultantA->id]);

        $response = $this->actingAs($admin)->post(route('leads.assign', $lead), [
            'assigned_to' => $consultantB->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', ['id' => $lead->id, 'assigned_to' => $consultantB->id]);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'assignment']);
    }
}
