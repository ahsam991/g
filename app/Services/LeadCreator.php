<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadSource;
use App\Models\User;
use App\Models\WebsiteEnquiry;
use Illuminate\Support\Facades\DB;

/**
 * Creates a lead from an enquiry or manual entry, detects duplicates,
 * and auto-assigns via round-robin within the branch.
 */
class LeadCreator
{
    public function __construct(
        private readonly Uid $uid,
        private readonly CandidateAssigner $assigner,
    ) {}

    /**
     * Create a lead from a website enquiry.
     */
    public function fromEnquiry(WebsiteEnquiry $enquiry): Lead
    {
        $source = LeadSource::firstOrCreate(['name' => 'Website']);

        return $this->create([
            'first_name' => $this->extractFirstName($enquiry->name),
            'last_name' => $this->extractLastName($enquiry->name),
            'email' => strtolower(trim($enquiry->email)),
            'phone' => $enquiry->phone,
            'country' => $enquiry->country,
            'preferred_subject' => $enquiry->subject,
            'preferred_level' => $enquiry->level,
            'notes' => $enquiry->message,
            'lead_source_id' => $source->id,
            'enquiry_id' => $enquiry->id,
            'ip_address' => $enquiry->ip_address,
        ]);
    }

    /**
     * Create a lead from manual entry or any data array.
     */
    public function create(array $data, ?User $actor = null): Lead
    {
        return DB::transaction(function () use ($data, $actor) {
            $data['uid'] = $this->uid->next('lead');
            $data['status'] = $data['status'] ?? 'new';

            // Duplicate detection
            $data['is_duplicate'] = $this->isDuplicate($data['email'] ?? null, $data['phone'] ?? null);

            $lead = Lead::create($data);

            // Auto-assign by round-robin if no consultant specified
            if (! $lead->assigned_to) {
                $consultant = $this->leastLoadedConsultant($lead->branch_id);
                if ($consultant) {
                    $lead->update(['assigned_to' => $consultant->id]);
                }
            }

            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $actor?->id,
                'type' => 'created',
                'description' => $lead->enquiry_id
                    ? 'Lead created from website enquiry'
                    : 'Lead created manually',
            ]);

            return $lead;
        });
    }

    private function isDuplicate(?string $email, ?string $phone): bool
    {
        if (! $email && ! $phone) {
            return false;
        }

        return Lead::query()
            ->where(function ($q) use ($email, $phone) {
                if ($email) {
                    $q->orWhere('email', strtolower(trim($email)));
                }
                if ($phone) {
                    $q->orWhere('phone', $phone);
                }
            })
            ->exists();
    }

    /**
     * Round-robin: find the consultant with the fewest open leads.
     */
    private function leastLoadedConsultant(?int $branchId): ?User
    {
        $query = User::where('status', 'active')
            ->whereHas('role', fn ($q) => $q->where('slug', 'consultant'));

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->withCount(['assignedLeads as open_leads_count' => function ($q) {
            $q->whereNotIn('status', ['converted', 'lost']);
        }])
            ->orderBy('open_leads_count')
            ->first();
    }

    private function extractFirstName(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name), 2);

        return $parts[0];
    }

    private function extractLastName(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name), 2);

        return $parts[1] ?? '';
    }
}
