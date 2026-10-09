<?php

namespace Tests\Unit;

use App\Services\Uid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UidTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_formats_each_kind_with_its_own_prefix(): void
    {
        $uid = app(Uid::class);
        $year = now()->format('Y');

        $this->assertSame("GC-{$year}-0001", $uid->next('candidate'));
        $this->assertSame("APP-{$year}-0001", $uid->next('application'));
        $this->assertSame("INV-{$year}-0001", $uid->next('invoice'));
    }

    public function test_numbers_increment_independently_per_kind(): void
    {
        $uid = app(Uid::class);
        $year = now()->format('Y');

        $uid->next('candidate');
        $uid->next('candidate');

        $this->assertSame("GC-{$year}-0003", $uid->next('candidate'));
        $this->assertSame("APP-{$year}-0001", $uid->next('application'));
    }

    public function test_numbering_restarts_in_a_new_year(): void
    {
        $uid = app(Uid::class);

        $uid->next('candidate', 2025);
        $uid->next('candidate', 2025);

        $this->assertSame('GC-2026-0001', $uid->next('candidate', 2026));
    }

    public function test_it_never_hands_out_the_same_number_twice(): void
    {
        $uid = app(Uid::class);

        $issued = collect(range(1, 50))->map(fn () => $uid->next('candidate'))->all();

        $this->assertCount(50, array_unique($issued));
    }
}
