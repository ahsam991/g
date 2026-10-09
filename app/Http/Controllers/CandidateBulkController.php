<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\User;
use App\Services\CandidateAssigner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CandidateBulkController extends Controller
{
    public function __construct(private readonly CandidateAssigner $assigner) {}

    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:assign,status,archive,restore'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:candidates,id'],
            'assigned_to' => ['required_if:action,assign', 'nullable', 'exists:users,id'],
            'status' => ['required_if:action,status', 'nullable', 'string', 'max:40'],
        ]);

        // Only rows this user may already see and edit can be touched.
        $candidates = Candidate::query()
            ->visibleTo($request->user())
            ->whereIn('id', $validated['ids'])
            ->get()
            ->filter(fn (Candidate $c) => $request->user()->can('update', $c));

        abort_if($candidates->isEmpty(), 403);

        $count = match ($validated['action']) {
            'assign' => $this->bulkAssign($request, $candidates, $validated['assigned_to']),
            'status' => $this->bulkUpdate($candidates, ['status' => $validated['status']]),
            'archive' => $this->bulkUpdate($candidates, ['archived_at' => now()]),
            'restore' => $this->bulkUpdate($candidates, ['archived_at' => null]),
        };

        return back()->with('status', "{$count} ".str('student')->plural($count).' updated.');
    }

    private function bulkAssign(Request $request, $candidates, ?int $consultantId): int
    {
        abort_unless($request->user()->can('assign', Candidate::class), 403);

        $consultant = $consultantId ? User::find($consultantId) : null;

        foreach ($candidates as $candidate) {
            $this->assigner->assign($candidate, $consultant, $request->user());
        }

        return $candidates->count();
    }

    private function bulkUpdate($candidates, array $attributes): int
    {
        foreach ($candidates as $candidate) {
            $candidate->update($attributes);
        }

        return $candidates->count();
    }
}
