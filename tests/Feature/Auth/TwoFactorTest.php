<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorTest extends TestCase
{
    use RefreshDatabase;

    private function service(): TwoFactorService
    {
        return app(TwoFactorService::class);
    }

    private function userWithTwoFactor(string $password = 'correct-password'): array
    {
        $service = $this->service();
        $secret = $service->generateSecret();

        $user = User::factory()->create(['password' => $password]);
        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        return [$user, $secret];
    }

    public function test_enrolment_requires_a_valid_code(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/two-factor')->assertOk();

        $this->actingAs($user)
            ->post('/two-factor', ['code' => '000000'])
            ->assertSessionHasErrors('code');

        $this->assertFalse($user->refresh()->hasTwoFactorEnabled());
    }

    public function test_enrolment_succeeds_and_issues_recovery_codes(): void
    {
        $user = User::factory()->create();
        $service = $this->service();

        $this->actingAs($user)->get('/two-factor');
        $secret = session('two_factor_pending_secret');

        $this->actingAs($user)
            ->post('/two-factor', ['code' => app('pragmarx.google2fa')->getCurrentOtp($secret)])
            ->assertRedirect(route('two-factor.show'))
            ->assertSessionHas('recovery_codes');

        $user->refresh();

        $this->assertTrue($user->hasTwoFactorEnabled());
        $this->assertCount(8, $user->two_factor_recovery_codes);
    }

    public function test_password_alone_does_not_create_a_session(): void
    {
        [$user] = $this->userWithTwoFactor();

        $this->post('/login', ['email' => $user->email, 'password' => 'correct-password'])
            ->assertRedirect(route('two-factor.challenge'));

        $this->assertGuest();
    }

    public function test_correct_code_completes_sign_in(): void
    {
        [$user, $secret] = $this->userWithTwoFactor();

        $this->post('/login', ['email' => $user->email, 'password' => 'correct-password']);

        $this->post('/two-factor/challenge', [
            'code' => app('pragmarx.google2fa')->getCurrentOtp($secret),
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_code_is_refused(): void
    {
        [$user] = $this->userWithTwoFactor();

        $this->post('/login', ['email' => $user->email, 'password' => 'correct-password']);

        $this->post('/two-factor/challenge', ['code' => '111111'])
            ->assertSessionHasErrors('code');

        $this->assertGuest();
    }

    public function test_a_recovery_code_works_once(): void
    {
        [$user] = $this->userWithTwoFactor();
        $codes = $user->generateRecoveryCodes();

        $this->post('/login', ['email' => $user->email, 'password' => 'correct-password']);
        $this->post('/two-factor/challenge', ['code' => $codes[0]]);
        $this->assertAuthenticatedAs($user);

        $this->assertCount(7, $user->refresh()->two_factor_recovery_codes);

        // The same code must not work a second time.
        $this->post('/logout');
        $this->post('/login', ['email' => $user->email, 'password' => 'correct-password']);
        $this->post('/two-factor/challenge', ['code' => $codes[0]])
            ->assertSessionHasErrors('code');

        $this->assertGuest();
    }

    public function test_recovery_codes_are_stored_hashed(): void
    {
        [$user] = $this->userWithTwoFactor();
        $codes = $user->generateRecoveryCodes();

        $stored = $user->refresh()->two_factor_recovery_codes;

        $this->assertNotContains($codes[0], $stored);
    }

    public function test_the_challenge_page_is_unreachable_without_a_pending_login(): void
    {
        $this->get('/two-factor/challenge')->assertRedirect(route('login'));
    }
}
