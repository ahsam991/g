<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailTemplate extends Model
{
    use Auditable;

    protected $fillable = ['slug', 'name', 'subject', 'body', 'variables', 'is_active'];

    protected $casts = ['variables' => 'array', 'is_active' => 'boolean'];

    /**
     * Replaces {{ placeholders }} with the values given. Anything not supplied
     * is left in place so a missing value is visible rather than silent.
     *
     * @param  array<string, string|int|null>  $values
     */
    public function render(array $values): array
    {
        $replace = [];

        foreach ($values as $key => $value) {
            $replace['{{'.$key.'}}'] = (string) $value;
            $replace['{{ '.$key.' }}'] = (string) $value;
        }

        return [
            'subject' => Str::replace(array_keys($replace), array_values($replace), $this->subject),
            'body' => Str::replace(array_keys($replace), array_values($replace), $this->body),
        ];
    }
}
