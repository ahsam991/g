<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** Seeds roles and permissions once per test that needs them. */
    protected function seedRoles(): void
    {
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * Asserts the user is refused access to the URL. This is the IDOR guard
     * used across the suite: a 403 or 404 is a pass, a 200 is a failure.
     */
    protected function assertForbiddenFor(User $user, string $url, string $method = 'get'): void
    {
        $response = $this->actingAs($user)->{$method}($url);

        $this->assertContains(
            $response->getStatusCode(),
            [403, 404],
            "Expected {$user->email} to be refused {$method} {$url}, got {$response->getStatusCode()}."
        );
    }

    /** Asserts the user may reach the URL. */
    protected function assertAllowedFor(User $user, string $url, string $method = 'get'): void
    {
        $this->actingAs($user)->{$method}($url)->assertSuccessful();
    }
}
