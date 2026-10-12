<x-layouts.crm title="Version History">
    <x-page-header :title="'Versions — '.$document->file_name" :subtitle="$document->documentType->name.' · Currently v'.$document->version">
        <x-slot:actions>
            <a href="{{ route('admin.documents.candidate', $document->candidate) }}" class="rounded-xl border border-line px-4 py-2 text-sm font-medium text-ink hover:bg-canvas">Back</a>
        </x-slot:actions>
    </x-page-header>

    <div class="mx-auto max-w-2xl space-y-3">
        {{-- Current version --}}
        <div class="rounded-2xl border-2 border-navy bg-navy/5 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="rounded-full bg-navy px-2 py-0.5 text-xs font-medium text-white">Current — v{{ $document->version }}</span>
                    <p class="mt-1 text-sm font-medium text-ink">{{ $document->file_name }}</p>
                    <p class="text-xs text-muted">{{ number_format($document->file_size / 1024, 0) }} KB · {{ $document->created_at->format('d M Y H:i') }}</p>
                </div>
                <a href="{{ route('admin.documents.download', $document) }}" class="rounded-lg bg-navy px-3 py-1.5 text-xs font-medium text-white hover:bg-navy/90">Download</a>
            </div>
        </div>

        {{-- Previous versions --}}
        @foreach($versions as $ver)
            <div class="rounded-2xl border border-line bg-white p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="rounded-full bg-canvas px-2 py-0.5 text-xs font-medium text-muted">v{{ $ver->version }}</span>
                        <p class="mt-1 text-sm font-medium text-ink">{{ $ver->file_name }}</p>
                        <p class="text-xs text-muted">
                            {{ number_format($ver->file_size / 1024, 0) }} KB
                            · {{ $ver->created_at->format('d M Y H:i') }}
                            @if($ver->creator) · by {{ $ver->creator->name }} @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforeach

        @if($versions->isEmpty())
            <p class="py-8 text-center text-sm text-muted">No previous versions. This is the first upload.</p>
        @endif
    </div>
</x-layouts.crm>
