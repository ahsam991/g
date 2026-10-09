<?php

namespace Tests\Feature;

use Tests\TestCase;

class BrandTest extends TestCase
{
    public function test_home_page_loads(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_brand_tokens_are_configured(): void
    {
        $config = file_get_contents(base_path('tailwind.config.js'));

        $this->assertStringContainsString('#0B2C5C', $config);
        $this->assertStringContainsString('#FF8C00', $config);
        $this->assertStringContainsString('Instrument Serif', $config);
    }

    public function test_production_env_template_matches_hostinger_constraints(): void
    {
        $env = file_get_contents(base_path('.env.example'));

        // Hostinger has no Redis, so queue and session must live in MySQL.
        $this->assertStringContainsString('QUEUE_CONNECTION=database', $env);
        $this->assertStringContainsString('SESSION_DRIVER=database', $env);
        $this->assertStringContainsString('CACHE_STORE=file', $env);
        $this->assertStringContainsString('DB_CONNECTION=mysql', $env);
        $this->assertStringContainsString('APP_DEBUG=false', $env);
        $this->assertStringNotContainsString('MAIL_PASSWORD=sec', $env);
    }
}
