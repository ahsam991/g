<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();

        Route::middleware(['web', 'auth', 'permission:settings.manage'])
            ->get('/__test/settings', fn () => 'ok');

        Route::middleware(['web', 'auth', 'permission:documents.verify'])
            ->get('/__test/verify', fn () => 'ok');

        Route::middleware(['web', 'auth', 'role:admin'])
            ->get('/__test/admin-only', fn () => 'ok');
    }

    public function test_admin_holds_every_permission(): void
    {
        $admin = User::factory()->admin()->create();

        $this->assertTrue($admin->hasPermission('settings.manage'));
        $this->assertTrue($admin->hasPermission('finance.manage'));
        $this->assertTrue($admin->hasPermission('gdpr.manage'));
    }

    public function test_consultant_can_verify_documents_but_not_reach_settings(): void
    {
        $consultant = User::factory()->consultant()->create();

        $this->assertTrue($consultant->hasPermission('documents.verify'));
        $this->assertFalse($consultant->hasPermission('settings.manage'));
        $this->assertFalse($consultant->hasPermission('finance.manage'));
        $this->assertFalse($consultant->hasPermission('documents.reverse'));
    }

    public function test_student_holds_almost_nothing(): void
    {
        $student = User::factory()->student()->create();

        $this->assertFalse($student->hasPermission('candidates.view'));
        $this->assertFalse($student->hasPermission('documents.verify'));
        $this->assertFalse($student->hasPermission('reports.view'));
        $this->assertTrue($student->hasPermission('documents.upload'));
    }

    public function test_consultant_hitting_an_admin_route_gets_403(): void
    {
        $this->assertForbiddenFor(User::factory()->consultant()->create(), '/__test/settings');
        $this->assertForbiddenFor(User::factory()->consultant()->create(), '/__test/admin-only');
    }

    public function test_student_hitting_a_crm_route_gets_403(): void
    {
        $this->assertForbiddenFor(User::factory()->student()->create(), '/__test/verify');
        $this->assertForbiddenFor(User::factory()->student()->create(), '/__test/settings');
    }

    public function test_permitted_users_pass_the_middleware(): void
    {
        $this->assertAllowedFor(User::factory()->admin()->create(), '/__test/settings');
        $this->assertAllowedFor(User::factory()->consultant()->create(), '/__test/verify');
    }

    public function test_permission_slugs_double_as_gates(): void
    {
        $consultant = User::factory()->consultant()->create();

        $this->assertTrue($consultant->can('documents.verify'));
        $this->assertFalse($consultant->can('settings.manage'));
    }

    public function test_a_user_without_a_role_holds_nothing(): void
    {
        $orphan = User::factory()->create(['role_id' => null]);

        $this->assertFalse($orphan->hasPermission('candidates.view'));
        $this->assertFalse($orphan->isAdmin());
    }
}
