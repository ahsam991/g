<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Services\Settings;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
        $this->seed(SettingSeeder::class);
    }

    public function test_real_company_data_is_seeded(): void
    {
        $this->assertSame('Global Consultancy Education', settings('company.name'));
        $this->assertSame('info@gconsultancy.co.uk', settings('company.email'));
        $this->assertStringContainsString('Whitechapel Road', settings('company.address_london'));
        $this->assertStringContainsString('Nirala', settings('company.address_khulna'));
    }

    public function test_values_are_cast_to_their_declared_type(): void
    {
        $this->assertSame(72, settings('gdpr.retention_months'));
        $this->assertSame(6, settings('finance.clawback_months'));
        $this->assertTrue(settings('gdpr.consent_required'));
        $this->assertIsArray(settings('finance.currencies'));
        $this->assertContains('GBP', settings('finance.currencies'));
    }

    public function test_a_missing_key_falls_back_to_the_default(): void
    {
        $this->assertSame('fallback', settings('nothing.here', 'fallback'));
    }

    public function test_saving_a_value_clears_the_cache_immediately(): void
    {
        $settings = app(Settings::class);

        $this->assertSame(20, settings('crm.per_page'));

        $settings->set('crm.per_page', 50, 'int', 'crm');

        $this->assertSame(50, settings('crm.per_page'));
    }

    public function test_admin_can_update_settings_through_the_screen(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/settings')->assertOk();

        $this->actingAs($admin)->put('/admin/settings', [
            'settings' => ['company.name' => 'Renamed Consultancy'],
        ])->assertRedirect(route('admin.settings.edit'));

        $this->assertSame('Renamed Consultancy', settings('company.name'));
    }

    public function test_a_consultant_cannot_reach_or_change_settings(): void
    {
        $consultant = User::factory()->consultant()->create();

        $this->assertForbiddenFor($consultant, '/admin/settings');

        $this->actingAs($consultant)
            ->put('/admin/settings', ['settings' => ['company.name' => 'Hijacked']])
            ->assertForbidden();

        $this->assertSame('Global Consultancy Education', settings('company.name'));
    }
}
