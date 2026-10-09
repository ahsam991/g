<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Records create / update / delete against the model, with the values before
 * and after. Secrets are never written to the log.
 */
trait Auditable
{
    /** Keys that must never reach the audit log, matched case-insensitively. */
    protected static array $auditRedacted = [
        'password', 'password_confirmation', 'remember_token', 'api_token',
        'two_factor_secret', 'two_factor_recovery_codes', 'secret', 'token',
    ];

    public static function bootAuditable(): void
    {
        static::created(fn (Model $m) => $m->writeAuditLog('created', null, $m->getAttributes()));

        static::updated(function (Model $m) {
            $changes = $m->getChanges();
            unset($changes['updated_at']);

            if ($changes === []) {
                return;
            }

            $before = array_intersect_key($m->getOriginal(), $changes);

            $m->writeAuditLog('updated', $before, $changes);
        });

        static::deleted(fn (Model $m) => $m->writeAuditLog('deleted', $m->getOriginal(), null));
    }

    public function writeAuditLog(string $action, ?array $before, ?array $after): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => $this->getMorphClass(),
            'auditable_id' => $this->getKey(),
            'before' => $before ? static::redactForAudit($before) : null,
            'after' => $after ? static::redactForAudit($after) : null,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 512),
        ]);
    }

    protected static function redactForAudit(array $values): array
    {
        foreach (array_keys($values) as $key) {
            foreach (static::$auditRedacted as $secret) {
                if (str_contains(strtolower((string) $key), $secret)) {
                    $values[$key] = '[redacted]';

                    continue 2;
                }
            }
        }

        return $values;
    }
}
