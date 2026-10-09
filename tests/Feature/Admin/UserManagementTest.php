<?php

namespace Tests\Feature\Admin;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
    }

    public function test_admin_sees_the_user_list(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'The Admin']);
        User::factory()->consultant()->create(['name' => 'A Consultant']);

        $this->actingAs($admin)->get('/admin/users')
            ->assertOk()
            ->assertSee('The Admin')
            ->assertSee('A Consultant');
    }

    public function test_admin_can_create_a_consultant_who_can_then_sign_in(): void
    {
        $admin = User::factory()->admin()->create();
        $branch = Branch::create(['name' => 'London', 'code' => 'LDN']);

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'New Consultant',
            'email' => 'consultant@gconsultancy.co.uk',
            'role_id' => Role::where('slug', Role::CONSULTANT)->value('id'),
            'branch_id' => $branch->id,
            'status' => 'active',
            'password' => 'Str0ngPassphrase',
            'password_confirmation' => 'Str0ngPassphrase',
        ])->assertRedirect(route('admin.users.index'));

        $created = User::where('email', 'consultant@gconsultancy.co.uk')->sole();

        $this->assertTrue($created->isConsultant());

        // And the account actually works.
        $this->post('/logout');
        $this->post('/login', [
            'email' => 'consultant@gconsultancy.co.uk',
            'password' => 'Str0ngPassphrase',
        ]);

        $this->assertAuthenticatedAs($created);
    }

    public function test_a_weak_password_is_refused(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Weak',
            'email' => 'weak@example.com',
            'role_id' => Role::where('slug', Role::CONSULTANT)->value('id'),
            'status' => 'active',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'weak@example.com']);
    }

    public function test_editing_without_a_password_keeps_the_existing_one(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->consultant()->create(['password' => 'OriginalPass123']);

        $this->actingAs($admin)->put("/admin/users/{$target->id}", [
            'name' => 'Renamed Person',
            'email' => $target->email,
            'role_id' => $target->role_id,
            'status' => 'active',
            'password' => '',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertSame('Renamed Person', $target->refresh()->name);

        $this->post('/logout');
        $this->post('/login', ['email' => $target->email, 'password' => 'OriginalPass123']);
        $this->assertAuthenticatedAs($target);
    }

    public function test_suspending_a_user_blocks_their_next_request(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->consultant()->create();

        $this->actingAs($admin)->delete("/admin/users/{$target->id}");

        $this->assertSame('suspended', $target->refresh()->status);

        $this->actingAs($target)->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_an_admin_cannot_suspend_themselves(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->delete("/admin/users/{$admin->id}")
            ->assertSessionHasErrors('user');

        $this->assertSame('active', $admin->refresh()->status);
    }

    public function test_a_consultant_cannot_reach_user_management(): void
    {
        $consultant = User::factory()->consultant()->create();

        $this->assertForbiddenFor($consultant, '/admin/users');
        $this->assertForbiddenFor($consultant, '/admin/users/create');
        $this->assertForbiddenFor($consultant, '/admin/roles');
        $this->assertForbiddenFor($consultant, '/admin/branches');
    }

    public function test_a_consultant_cannot_create_a_user_by_posting_directly(): void
    {
        $consultant = User::factory()->consultant()->create();

        $this->actingAs($consultant)->post('/admin/users', [
            'name' => 'Smuggled Admin',
            'email' => 'smuggled@example.com',
            'role_id' => Role::where('slug', Role::ADMIN)->value('id'),
            'status' => 'active',
            'password' => 'Str0ngPassphrase',
            'password_confirmation' => 'Str0ngPassphrase',
        ])->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'smuggled@example.com']);
    }

    public function test_a_student_cannot_reach_any_admin_screen(): void
    {
        $student = User::factory()->student()->create();

        foreach (['/admin/users', '/admin/roles', '/admin/branches', '/admin/settings', '/admin/audit'] as $url) {
            $this->assertForbiddenFor($student, $url);
        }
    }
}
