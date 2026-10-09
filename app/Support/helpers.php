<?php

use App\Services\Settings;

if (! function_exists('settings')) {
    /** Reads a setting, falling back to the default when it is not set. */
    function settings(?string $key = null, mixed $default = null): mixed
    {
        $settings = app(Settings::class);

        return $key === null ? $settings->all() : $settings->get($key, $default);
    }
}
