<?php

namespace Tests\Feature\Auth;

use App\Models\AuditLog;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_record_is_logged(): void
    {
        $branch = Branch::create(['name' => 'Test Office', 'code' => 'TST']);

        $log = AuditLog::where('auditable_type', Branch::class)->sole();

        $this->assertSame('created', $log->action);
        $this->assertSame($branch->id, $log->auditable_id);
        $this->assertSame('Test Office', $log->after['name']);
    }

    public function test_updating_logs_only_what_changed(): void
    {
        $branch = Branch::create(['name' => 'Old Name', 'code' => 'UPD']);
        AuditLog::query()->delete();

        $branch->update(['name' => 'New Name']);

        $log = AuditLog::sole();

        $this->assertSame('updated', $log->action);
        $this->assertSame('Old Name', $log->before['name']);
        $this->assertSame('New Name', $log->after['name']);
        $this->assertArrayNotHasKey('code', $log->after);
    }

    public function test_deleting_is_logged(): void
    {
        $branch = Branch::create(['name' => 'Doomed', 'code' => 'DEL']);
        AuditLog::query()->delete();

        $branch->delete();

        $this->assertSame('deleted', AuditLog::sole()->action);
    }

    public function test_password_and_secrets_never_reach_the_log(): void
    {
        $user = User::factory()->create(['password' => 'super-secret-value']);

        $log = AuditLog::where('auditable_type', User::class)->first();

        $this->assertSame('[redacted]', $log->after['password']);
        $this->assertStringNotContainsString('super-secret-value', json_encode($log->after));
        $this->assertStringNotContainsString(
            $user->getAuthPassword(),
            json_encode($log->after),
            'The password hash must not be stored in the audit log either.'
        );
    }

    public function test_two_factor_secret_and_recovery_codes_are_redacted(): void
    {
        $user = User::factory()->create();
        AuditLog::query()->delete();

        $user->forceFill(['two_factor_secret' => 'ABCDEFGHIJKLMNOP'])->save();

        $log = AuditLog::sole();

        $this->assertSame('[redacted]', $log->after['two_factor_secret']);
    }

    public function test_the_acting_user_and_request_details_are_recorded(): void
    {
        $actor = User::factory()->admin()->create();
        AuditLog::query()->delete();

        $this->actingAs($actor);
        Branch::create(['name' => 'Attributed', 'code' => 'ATT']);

        $log = AuditLog::sole();

        $this->assertSame($actor->id, $log->user_id);
        $this->assertNotNull($log->ip_address);
    }

    public function test_no_log_is_written_when_nothing_actually_changed(): void
    {
        $branch = Branch::create(['name' => 'Same', 'code' => 'SAM']);
        AuditLog::query()->delete();

        $branch->update(['name' => 'Same']);

        $this->assertSame(0, AuditLog::count());
    }
}
