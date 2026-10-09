<?php

namespace App\Http\Controllers;

use App\Exports\CandidateExport;
use App\Imports\CandidateImport;
use App\Models\Candidate;
use App\Services\CandidateAssigner;
use App\Services\DuplicateFinder;
use App\Services\ProfileCompleteness;
use App\Services\Uid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CandidateImportController extends Controller
{
    public function show(): View
    {
        $this->authorize('import', Candidate::class);

        return view('candidates.import');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('import', Candidate::class);

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:10240'],
            'skip_duplicates' => ['boolean'],
        ]);

        $import = new CandidateImport(
            $request->user(),
            app(Uid::class),
            app(DuplicateFinder::class),
            app(ProfileCompleteness::class),
            app(CandidateAssigner::class),
            $request->boolean('skip_duplicates', true),
        );

        Excel::import($import, $request->file('file'));

        return redirect()->route('candidates.index')
            ->with('status', "{$import->imported} imported, {$import->skipped} skipped.")
            ->with('import_errors', array_slice($import->errors, 0, 50));
    }

    public function export(Request $request): BinaryFileResponse
    {
        $this->authorize('import', Candidate::class);

        $query = Candidate::query()
            ->visibleTo($request->user())
            ->search($request->input('q'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->boolean('archived'), fn ($q) => $q->archived(), fn ($q) => $q->active())
            ->orderBy('uid');

        $filename = 'students-'.now()->format('Y-m-d').'.xlsx';

        return Excel::download(new CandidateExport($query), $filename);
    }
}
