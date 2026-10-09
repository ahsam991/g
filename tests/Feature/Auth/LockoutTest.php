<?php

namespace Tests\Feature\Auth;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LockoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_locks_after_five_failed_attempts(): void
    {
        $user = User::factory()->create(['email' => 'lock@example.com']);

        for ($attempt = 1; $attempt <= User::MAX_FAILED_LOGINS; $attempt++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);
        }

        $user->refresh();

        $this->assertTrue($user->isLocked());
        $this->assertTrue($user->locked_until->isFuture());
    }

    public function test_locked_account_is_refused_even_with_the_right_password(): void
    {
        $user = User::factory()->create(['password' => 'correct-password']);
        $user->forceFill(['locked_until' => now()->addMinutes(10)])->save();

        $this->post('/login', ['email' => $user->email, 'password' => 'correct-password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_lock_clears_once_it_expires(): void
    {
        $user = User::factory()->create(['password' => 'correct-password']);
        $user->forceFill(['locked_until' => now()->subMinute()])->save();

        $this->post('/login', ['email' => $user->email, 'password' => 'correct-password']);

        $this->assertAuthenticatedAs($user);
    }

    public function test_successful_login_resets_the_failure_count(): void
    {
        $user = User::factory()->create(['password' => 'correct-password']);
        $user->forceFill(['failed_logins' => 3])->save();

        $this->post('/login', ['email' => $user->email, 'password' => 'correct-password']);

        $this->assertSame(0, $user->refresh()->failed_logins);
        $this->assertNotNull($user->last_login_at);
    }

    public function test_suspended_account_cannot_sign_in(): void
    {
        $user = User::factory()->suspended()->create(['password' => 'correct-password']);

        $this->post('/login', ['email' => $user->email, 'password' => 'correct-password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_every_attempt_is_recorded_with_ip_and_agent(): void
    {
        $user = User::factory()->create(['password' => 'correct-password']);

        $this->post('/login', ['email' => $user->email, 'password' => 'nope']);
        $this->post('/login', ['email' => $user->email, 'password' => 'correct-password']);

        $this->assertSame(2, LoginHistory::count());
        $this->assertSame(1, LoginHistory::where('successful', true)->count());

        $failure = LoginHistory::where('successful', false)->first();
        $this->assertSame('bad-credentials', $failure->reason);
        $this->assertNotNull($failure->ip_address);
    }

    public function test_history_records_attempts_for_unknown_email_without_a_user(): void
    {
        $this->post('/login', ['email' => 'nobody@example.com', 'password' => 'whatever']);

        $row = LoginHistory::sole();

        $this->assertNull($row->user_id);
        $this->assertSame('nobody@example.com', $row->email);
    }
}
