<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrmShellTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
        $this->seed(SettingSeeder::class);
    }

    public function test_crm_pages_are_hidden_from_search_engines(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/dashboard')->assertOk();

        $this->assertSame('noindex, nofollow', $response->headers->get('X-Robots-Tag'));
        $response->assertSee('noindex, nofollow', false);
    }

    public function test_every_admin_screen_renders(): void
    {
        $admin = User::factory()->admin()->create();

        foreach ([
            '/dashboard',
            '/admin/users',
            '/admin/users/create',
            '/admin/roles',
            '/admin/branches',
            '/admin/branches/create',
            '/admin/settings',
            '/admin/audit',
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_the_sidebar_hides_what_the_user_cannot_reach(): void
    {
        $consultant = User::factory()->consultant()->create();

        $response = $this->actingAs($consultant)->get('/dashboard')->assertOk();

        // Consultants have no settings or user administration.
        $response->assertDontSee(route('admin.settings.edit'));
        $response->assertDontSee(route('admin.users.index'));
    }

    public function test_the_shell_shows_company_details_from_settings(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/dashboard')
            ->assertOk()
            ->assertSee('Global Consultancy Education');
    }
}
