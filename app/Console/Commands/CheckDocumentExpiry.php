<?php

namespace App\Console\Commands;

use App\Models\CandidateDocument;
use Illuminate\Console\Command;

class CheckDocumentExpiry extends Command
{
    protected $signature = 'documents:check-expiry';

    protected $description = 'Flag expiring and expired documents';

    public function handle(): int
    {
        $expired = CandidateDocument::expired()->update(['status' => 'expired']);
        $this->info("Marked {$expired} document(s) as expired.");

        $expiring30 = CandidateDocument::expiring(30)->count();
        $expiring7 = CandidateDocument::expiring(7)->count();

        if ($expiring30 > 0) {
            $this->info("{$expiring30} document(s) expiring within 30 days ({$expiring7} within 7 days).");
        }

        return self::SUCCESS;
    }
}
