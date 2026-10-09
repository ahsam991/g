<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Hands out the human-facing reference numbers: GC-2026-0001 for students,
 * APP-2026-0001 for applications, INV-2026-0001 for invoices.
 *
 * The counter row is locked for the duration of the transaction, so two
 * requests arriving together cannot be given the same number.
 */
class Uid
{
    private const PREFIXES = [
        'candidate' => 'GC',
        'application' => 'APP',
        'invoice' => 'INV',
        'payment' => 'PAY',
        'commission' => 'COM',
        'appointment' => 'APT',
    ];

    public function next(string $key, ?int $year = null): string
    {
        $prefix = self::PREFIXES[$key] ?? strtoupper(substr($key, 0, 3));
        $year ??= (int) now()->format('Y');

        $number = DB::transaction(function () use ($key, $year) {
            $row = DB::table('uid_counters')->lockForUpdate()->where('key', $key)->first();

            if (! $row) {
                DB::table('uid_counters')->insert([
                    'key' => $key,
                    'year' => $year,
                    'value' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return 1;
            }

            // Numbering restarts each calendar year.
            $next = $row->year === $year ? $row->value + 1 : 1;

            DB::table('uid_counters')->where('key', $key)->update([
                'year' => $year,
                'value' => $next,
                'updated_at' => now(),
            ]);

            return $next;
        });

        return sprintf('%s-%d-%04d', $prefix, $year, $number);
    }
}
