<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadSource;
use App\Models\User;
use App\Services\LeadConverter;
use App\Services\LeadCreator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Lead::class);

        $leads = Lead::query()
            ->visibleTo($request->user())
            ->with(['consultant:id,name', 'source:id,name', 'branch:id,name'])
            ->search($request->input('search'))
            ->status($request->input('status'))
            ->when($request->input('source'), fn ($q, $v) => $q->where('lead_source_id', $v))
            ->when($request->input('consultant'), fn ($q, $v) => $q->where('assigned_to', $v))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $sources = LeadSource::where('active', true)->orderBy('name')->get();
        $consultants = User::whereHas('role', fn ($q) => $q->where('slug', 'consultant'))
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('leads.index', compact('leads', 'sources', 'consultants'));
    }

    public function create(): View
    {
        $this->authorize('create', Lead::class);

        $sources = LeadSource::where('active', true)->orderBy('name')->get();

        return view('leads.form', [
            'lead' => new Lead,
            'sources' => $sources,
        ]);
    }

    public function store(Request $request, LeadCreator $creator): RedirectResponse
    {
        $this->authorize('create', Lead::class);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'country' => 'nullable|string|max:100',
            'preferred_destination' => 'nullable|string|max:100',
            'preferred_level' => 'nullable|string|max:50',
            'preferred_subject' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:2000',
            'lead_source_id' => 'nullable|exists:lead_sources,id',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // Consultants auto-assign to themselves
        if ($request->user()->isConsultant()) {
            $validated['assigned_to'] = $request->user()->id;
            $validated['branch_id'] = $request->user()->branch_id;
        }

        $lead = $creator->create($validated, $request->user());

        return redirect()->route('leads.show', $lead)
            ->with('status', "Lead {$lead->uid} created.");
    }

    public function show(Lead $lead): View
    {
        $this->authorize('view', $lead);

        $lead->load(['consultant:id,name', 'source:id,name', 'branch:id,name', 'enquiry', 'candidate:id,uid,first_name,last_name']);
        $activities = $lead->activities()->with('user:id,name')->get();

        return view('leads.show', compact('lead', 'activities'));
    }

    public function edit(Lead $lead): View
    {
        $this->authorize('update', $lead);

        $sources = LeadSource::where('active', true)->orderBy('name')->get();

        return view('leads.form', [
            'lead' => $lead,
            'sources' => $sources,
        ]);
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $this->authorize('update', $lead);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'country' => 'nullable|string|max:100',
            'preferred_destination' => 'nullable|string|max:100',
            'preferred_level' => 'nullable|string|max:50',
            'preferred_subject' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:2000',
            'lead_source_id' => 'nullable|exists:lead_sources,id',
            'status' => 'nullable|in:'.implode(',', Lead::STATUSES),
        ]);

        $oldStatus = $lead->status;
        $lead->update($validated);

        if ($oldStatus !== $lead->status) {
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $request->user()->id,
                'type' => 'status_change',
                'description' => "Status changed from {$oldStatus} to {$lead->status}",
                'meta' => ['from' => $oldStatus, 'to' => $lead->status],
            ]);

            if ($lead->status === 'contacted' && ! $lead->contacted_at) {
                $lead->update(['contacted_at' => now()]);
            }
        }

        return redirect()->route('leads.show', $lead)
            ->with('status', 'Lead updated.');
    }

    public function addActivity(Request $request, Lead $lead): RedirectResponse
    {
        $this->authorize('update', $lead);

        $validated = $request->validate([
            'type' => 'required|in:note,call,email',
            'description' => 'required|string|max:2000',
        ]);

        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => $request->user()->id,
            ...$validated,
        ]);

        return back()->with('status', 'Activity logged.');
    }

    public function convert(Lead $lead, LeadConverter $converter): RedirectResponse
    {
        $this->authorize('convert', $lead);

        $candidate = $converter->convert($lead, request()->user());

        return redirect()->route('candidates.show', $candidate)
            ->with('status', "Lead converted to student {$candidate->uid}.");
    }

    public function assign(Request $request, Lead $lead): RedirectResponse
    {
        $this->authorize('update', $lead);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $oldConsultant = $lead->consultant?->name ?? 'unassigned';
        $lead->update(['assigned_to' => $validated['assigned_to']]);
        $newConsultant = $lead->fresh()->consultant?->name ?? 'unassigned';

        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => $request->user()->id,
            'type' => 'assignment',
            'description' => "Reassigned from {$oldConsultant} to {$newConsultant}",
        ]);

        return back()->with('status', 'Lead reassigned.');
    }
}
