<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentUploadRequest;
use App\Models\Candidate;
use App\Models\CandidateDocument;
use App\Models\DocumentRequest;
use App\Models\DocumentType;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function __construct(private DocumentService $service) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', CandidateDocument::class);

        $query = CandidateDocument::with(['candidate', 'documentType', 'creator', 'verifier'])
            ->latest();

        $user = $request->user();
        if ($user->isConsultant()) {
            $query->whereHas('candidate', fn ($q) => $q->where('assigned_to', $user->id));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('type')) {
            $query->where('document_type_id', $request->input('type'));
        }
        if ($request->filled('candidate')) {
            $query->where('candidate_id', $request->input('candidate'));
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                    ->orWhereHas('candidate', fn ($c) => $c->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('uid', 'like', "%{$search}%"));
            });
        }

        $documents = $query->paginate(20)->withQueryString();
        $types = DocumentType::active()->orderBy('sort_order')->get();
        $statuses = ['pending', 'under_review', 'verified', 'rejected', 'expired'];

        return view('admin.documents.index', compact('documents', 'types', 'statuses'));
    }

    public function upload(Candidate $candidate): View
    {
        $this->authorize('upload', [CandidateDocument::class, $candidate]);

        $types = DocumentType::active()->orderBy('sort_order')->get();
        $existingDocs = CandidateDocument::where('candidate_id', $candidate->id)
            ->with('documentType')
            ->get()
            ->keyBy('document_type_id');

        return view('admin.documents.upload', compact('candidate', 'types', 'existingDocs'));
    }

    public function store(DocumentUploadRequest $request, Candidate $candidate): RedirectResponse
    {
        $uploaded = 0;
        foreach ($request->file('files') as $file) {
            $this->service->upload(
                $candidate,
                $request->input('document_type_id'),
                $file,
                $request->user()->id,
                $request->input('expiry_date'),
            );
            $uploaded++;
        }

        return redirect()->route('admin.documents.candidate', $candidate)
            ->with('status', "{$uploaded} document(s) uploaded.");
    }

    public function candidateDocuments(Candidate $candidate): View
    {
        $this->authorize('upload', [CandidateDocument::class, $candidate]);

        $documents = CandidateDocument::where('candidate_id', $candidate->id)
            ->with(['documentType', 'verifier', 'creator'])
            ->orderBy('document_type_id')
            ->get();

        $types = DocumentType::active()->orderBy('sort_order')->get();
        $requests = DocumentRequest::where('candidate_id', $candidate->id)
            ->pending()
            ->with('documentType')
            ->get();

        $requiredTypes = DocumentType::active()->required()->pluck('id');
        $uploadedTypeIds = $documents->pluck('document_type_id')->unique();
        $verifiedTypeIds = $documents->where('status', 'verified')->pluck('document_type_id')->unique();

        $progress = $requiredTypes->count() > 0
            ? round($verifiedTypeIds->intersect($requiredTypes)->count() / $requiredTypes->count() * 100)
            : 0;

        return view('admin.documents.candidate', compact(
            'candidate', 'documents', 'types', 'requests', 'progress',
            'requiredTypes', 'uploadedTypeIds', 'verifiedTypeIds',
        ));
    }

    public function download(CandidateDocument $document): StreamedResponse
    {
        $this->authorize('download', $document);

        $this->service->logAccess(
            $document,
            auth()->id(),
            'download',
            request()->ip(),
            request()->userAgent(),
        );

        return Storage::disk('private')->download(
            $document->file_path,
            $document->file_name,
        );
    }

    public function preview(CandidateDocument $document)
    {
        $this->authorize('view', $document);

        $this->service->logAccess(
            $document,
            auth()->id(),
            'view',
            request()->ip(),
            request()->userAgent(),
        );

        $disk = Storage::disk('private');
        if (! $disk->exists($document->file_path)) {
            abort(404);
        }

        return response($disk->get($document->file_path))
            ->header('Content-Type', $document->mime_type)
            ->header('Content-Disposition', 'inline; filename="'.$document->file_name.'"');
    }

    public function verify(CandidateDocument $document): RedirectResponse
    {
        $this->authorize('verify', $document);

        $this->service->verify($document, auth()->id());

        return back()->with('status', 'Document verified.');
    }

    public function reject(Request $request, CandidateDocument $document): RedirectResponse
    {
        $this->authorize('reject', $document);

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $this->service->reject($document, $request->input('rejection_reason'), auth()->id());

        return back()->with('status', 'Document rejected.');
    }

    public function reverseRejection(CandidateDocument $document): RedirectResponse
    {
        $this->authorize('reverseRejection', $document);

        $this->service->reverseRejection($document);

        return back()->with('status', 'Rejection reversed. Document is now pending.');
    }

    public function requestDocument(Request $request, Candidate $candidate): RedirectResponse
    {
        $this->authorize('request', CandidateDocument::class);
        $this->authorize('upload', [CandidateDocument::class, $candidate]);

        $request->validate([
            'document_type_id' => ['required', 'exists:document_types,id'],
            'message' => ['nullable', 'string', 'max:1000'],
            'due_date' => ['nullable', 'date', 'after:today'],
        ]);

        DocumentRequest::create([
            'candidate_id' => $candidate->id,
            'document_type_id' => $request->input('document_type_id'),
            'requested_by' => auth()->id(),
            'message' => $request->input('message'),
            'due_date' => $request->input('due_date'),
        ]);

        return back()->with('status', 'Document requested from student.');
    }

    public function accessLog(CandidateDocument $document): View
    {
        $this->authorize('view', $document);

        $logs = $document->accessLogs()
            ->with('user')
            ->latest('created_at')
            ->paginate(20);

        return view('admin.documents.access-log', compact('document', 'logs'));
    }

    public function versions(CandidateDocument $document): View
    {
        $this->authorize('view', $document);

        $versions = $document->versions()
            ->with('creator')
            ->orderByDesc('version')
            ->get();

        return view('admin.documents.versions', compact('document', 'versions'));
    }
}
