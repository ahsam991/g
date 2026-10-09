<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionScreenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
    }

    public function test_admin_can_change_what_a_consultant_may_do(): void
    {
        $admin = User::factory()->admin()->create();
        $consultantRole = Role::where('slug', Role::CONSULTANT)->sole();

        $this->actingAs($admin)->put("/admin/roles/{$consultantRole->id}", [
            'permissions' => ['candidates.view', 'documents.view'],
        ])->assertRedirect(route('admin.roles.index'));

        $consultant = User::factory()->consultant()->create();

        $this->assertTrue($consultant->hasPermission('candidates.view'));
        $this->assertFalse($consultant->hasPermission('documents.verify'));
    }

    public function test_the_admin_role_cannot_have_permissions_stripped(): void
    {
        $admin = User::factory()->admin()->create();
        $adminRole = Role::where('slug', Role::ADMIN)->sole();
        $before = $adminRole->permissions()->count();

        $this->actingAs($admin)->put("/admin/roles/{$adminRole->id}", ['permissions' => []])
            ->assertSessionHasErrors('permissions');

        $this->assertSame($before, $adminRole->refresh()->permissions()->count());
    }

    public function test_an_unknown_permission_slug_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();
        $role = Role::where('slug', Role::CONSULTANT)->sole();

        $this->actingAs($admin)->put("/admin/roles/{$role->id}", [
            'permissions' => ['made.up.permission'],
        ])->assertSessionHasErrors('permissions.0');
    }
}
