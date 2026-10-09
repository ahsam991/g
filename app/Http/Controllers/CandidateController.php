<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidateRequest;
use App\Models\Branch;
use App\Models\Candidate;
use App\Models\Role;
use App\Models\User;
use App\Services\CandidateAssigner;
use App\Services\DuplicateFinder;
use App\Services\ProfileCompleteness;
use App\Services\Uid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function __construct(
        private readonly Uid $uid,
        private readonly DuplicateFinder $duplicates,
        private readonly ProfileCompleteness $completeness,
        private readonly CandidateAssigner $assigner,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Candidate::class);

        $candidates = Candidate::query()
            ->visibleTo($request->user())
            ->with(['consultant:id,name', 'branch:id,name'])
            ->search($request->input('q'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('destination'), fn ($q) => $q->where('preferred_destination', $request->string('destination')))
            ->when($request->filled('assigned_to'), fn ($q) => $q->where('assigned_to', $request->integer('assigned_to')))
            ->when($request->boolean('archived'), fn ($q) => $q->archived(), fn ($q) => $q->active())
            ->latest('id')
            ->paginate(settings('crm.per_page', 20));

        return view('candidates.index', [
            'candidates' => $candidates,
            'consultants' => $this->consultants(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Candidate::class);

        return view('candidates.form', [
            'candidate' => new Candidate,
            'consultants' => $this->consultants(),
            'branches' => Branch::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(CandidateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Warn once, then let a confirmed submission through.
        $duplicates = $this->duplicates->for($data);

        if ($duplicates->isNotEmpty() && ! $request->boolean('confirm_duplicate')) {
            return back()
                ->withInput()
                ->with('duplicates', $duplicates)
                ->withErrors(['email' => 'This looks like an existing student. Review the matches below, then save again to confirm.']);
        }

        $data['uid'] = $this->uid->next('candidate');

        // A consultant always owns what they create. Ownership is applied by the
        // assigner rather than on the insert, so the history row gets written.
        $consultantId = $data['assigned_to'] ?? ($request->user()->isConsultant() ? $request->user()->id : null);
        unset($data['assigned_to']);

        $candidate = Candidate::create($data);

        if ($consultantId) {
            $this->assigner->assign($candidate, User::find($consultantId), $request->user());
        }

        $this->completeness->refresh($candidate);

        return redirect()->route('candidates.show', $candidate)
            ->with('status', "{$candidate->full_name} added as {$candidate->uid}.");
    }

    public function show(Request $request, Candidate $candidate): View
    {
        $this->authorize('view', $candidate);

        $candidate->load([
            'consultant:id,name',
            'branch:id,name',
            'addresses',
            'emergencyContacts',
            'qualifications',
            'englishTests',
            'sponsors',
            'counsellingSessions.user:id,name',
        ]);

        return view('candidates.show', [
            'candidate' => $candidate,
            'breakdown' => $this->completeness->breakdown($candidate),
        ]);
    }

    public function edit(Candidate $candidate): View
    {
        $this->authorize('update', $candidate);

        return view('candidates.form', [
            'candidate' => $candidate,
            'consultants' => $this->consultants(),
            'branches' => Branch::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(CandidateRequest $request, Candidate $candidate): RedirectResponse
    {
        $data = $request->validated();

        // Reassignment goes through the service so history is written.
        $newConsultant = $data['assigned_to'] ?? null;
        unset($data['assigned_to']);

        $candidate->update($data);

        if ($request->user()->can('assign', Candidate::class) && $newConsultant !== $candidate->assigned_to) {
            $this->assigner->assign($candidate, $newConsultant ? User::find($newConsultant) : null, $request->user());
        }

        $this->completeness->refresh($candidate);

        return redirect()->route('candidates.show', $candidate)->with('status', 'Student updated.');
    }

    /** Archives rather than deletes, keeping references intact. */
    public function archive(Request $request, Candidate $candidate): RedirectResponse
    {
        $this->authorize('archive', $candidate);

        $candidate->update(['archived_at' => now()]);

        return redirect()->route('candidates.index')->with('status', "{$candidate->full_name} archived.");
    }

    public function restore(Request $request, Candidate $candidate): RedirectResponse
    {
        $this->authorize('archive', $candidate);

        $candidate->update(['archived_at' => null]);

        return redirect()->route('candidates.show', $candidate)->with('status', 'Student restored.');
    }

    /** @return Collection<int, User> */
    private function consultants()
    {
        return User::query()
            ->whereRelation('role', 'slug', Role::CONSULTANT)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
