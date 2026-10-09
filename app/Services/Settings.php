<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

/**
 * Key/value settings, read on nearly every request, so the whole set is
 * cached and the cache is dropped whenever a value is written.
 */
class Settings
{
    private const CACHE_KEY = 'settings.all';

    /** @return array<string, mixed> */
    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::all()
                ->mapWithKeys(fn (Setting $s) => [$s->key => $this->cast($s->value, $s->type)])
                ->all();
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function set(string $key, mixed $value, string $type = 'string', string $group = 'general'): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : (string) $value, 'type' => $type, 'group' => $group],
        );

        $this->flush();
    }

    /** @param  array<string, mixed>  $values */
    public function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            $existing = Setting::where('key', $key)->first();

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => is_array($value) ? json_encode($value) : (string) $value,
                    'type' => $existing?->type ?? 'string',
                    'group' => $existing?->group ?? 'general',
                ],
            );
        }

        $this->flush();
    }

    /** @return array<string, array<string, mixed>> */
    public function grouped(): array
    {
        return Setting::orderBy('group')->orderBy('key')->get()->groupBy('group')->all();
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    private function cast(?string $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'bool', 'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'int', 'integer' => (int) $value,
            'float', 'decimal' => (float) $value,
            'array', 'json' => json_decode($value, true) ?? [],
            default => $value,
        };
    }
}
