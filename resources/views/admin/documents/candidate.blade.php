<x-layouts.crm :title="$candidate->first_name.' '.$candidate->last_name.' — Documents'">
    <x-page-header :title="$candidate->first_name.' '.$candidate->last_name" :subtitle="$candidate->uid.' · Documents'">
        <x-slot:actions>
            <a href="{{ route('admin.documents.upload', $candidate) }}" class="rounded-xl bg-navy px-4 py-2 text-sm font-semibold text-white hover:bg-navy/90">Upload document</a>
            <a href="{{ route('candidates.show', $candidate) }}" class="rounded-xl border border-line px-4 py-2 text-sm font-medium text-ink hover:bg-canvas">Back to profile</a>
        </x-slot:actions>
    </x-page-header>

    {{-- Progress bar --}}
    <div class="mb-6 rounded-2xl border border-line bg-white p-5">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-navy">Document Progress</h3>
            <span class="text-sm font-bold text-navy">{{ $progress }}%</span>
        </div>
        <div class="mt-2 h-3 overflow-hidden rounded-full bg-canvas">
            <div class="h-full rounded-full transition-all duration-500 {{ $progress >= 100 ? 'bg-green-500' : ($progress >= 50 ? 'bg-orange' : 'bg-amber-400') }}" style="width: {{ $progress }}%"></div>
        </div>
        <p class="mt-1 text-xs text-muted">{{ $verifiedTypeIds->intersect($requiredTypes)->count() }} of {{ $requiredTypes->count() }} required documents verified</p>
    </div>

    {{-- Pending requests --}}
    @if($requests->isNotEmpty())
    <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-5">
        <h3 class="text-sm font-semibold text-amber-800">Pending Requests</h3>
        <div class="mt-3 space-y-2">
            @foreach($requests as $req)
                <div class="flex items-center justify-between rounded-xl bg-white px-4 py-2.5">
                    <div>
                        <p class="text-sm font-medium text-ink">{{ $req->documentType->name }}</p>
                        @if($req->message)
                            <p class="text-xs text-muted">{{ $req->message }}</p>
                        @endif
                    </div>
                    @if($req->due_date)
                        <span class="text-xs text-amber-700">Due {{ $req->due_date->format('d M Y') }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Request a document --}}
    @can('documents.request')
    <div class="mb-6" x-data="{ open: false }">
        <button @click="open = !open" class="text-sm font-medium text-orange hover:underline">+ Request a document from student</button>
        <form method="POST" action="{{ route('admin.documents.request', $candidate) }}" x-show="open" x-cloak class="mt-3 rounded-2xl border border-line bg-white p-5">
            @csrf
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-xs font-medium text-muted">Document type</label>
                    <select name="document_type_id" required class="mt-1 w-full rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                        @foreach($types as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-muted">Due date</label>
                    <input type="date" name="due_date" class="mt-1 w-full rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                </div>
                <div>
                    <label class="block text-xs font-medium text-muted">Message</label>
                    <input type="text" name="message" placeholder="Optional note..." class="mt-1 w-full rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                </div>
            </div>
            <button type="submit" class="mt-3 rounded-xl bg-orange px-4 py-2 text-sm font-semibold text-navy hover:bg-orange/90">Send request</button>
        </form>
    </div>
    @endcan

    {{-- Documents grid --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($types as $type)
            @php
                $doc = $documents->firstWhere('document_type_id', $type->id);
                $isRequired = $requiredTypes->contains($type->id);
            @endphp
            <div @class([
                'rounded-2xl border p-5 transition-all',
                'border-green-200 bg-green-50/50' => $doc && $doc->status === 'verified',
                'border-amber-200 bg-amber-50/50' => $doc && $doc->status === 'pending',
                'border-blue-200 bg-blue-50/50' => $doc && $doc->status === 'under_review',
                'border-red-200 bg-red-50/50' => $doc && ($doc->status === 'rejected' || $doc->status === 'expired'),
                'border-dashed border-line bg-white' => !$doc,
            ])>
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="text-sm font-semibold text-navy">{{ $type->name }}</h4>
                        @if($isRequired)
                            <span class="text-xs font-medium text-red-600">Required</span>
                        @else
                            <span class="text-xs text-muted">Optional</span>
                        @endif
                    </div>
                    @if($doc)
                        <span @class([
                            'rounded-full px-2 py-0.5 text-xs font-medium',
                            'bg-green-100 text-green-700' => $doc->status === 'verified',
                            'bg-amber-100 text-amber-700' => $doc->status === 'pending',
                            'bg-blue-100 text-blue-700' => $doc->status === 'under_review',
                            'bg-red-100 text-red-700' => $doc->status === 'rejected',
                            'bg-red-200 text-red-800' => $doc->status === 'expired',
                        ])>{{ ucfirst(str_replace('_', ' ', $doc->status)) }}</span>
                    @else
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">Missing</span>
                    @endif
                </div>

                @if($doc)
                    <p class="mt-2 truncate text-xs text-muted" title="{{ $doc->file_name }}">{{ $doc->file_name }}</p>
                    <p class="text-xs text-muted">v{{ $doc->version }} · {{ number_format($doc->file_size / 1024, 0) }} KB · {{ $doc->created_at->diffForHumans() }}</p>

                    @if($doc->rejection_reason)
                        <p class="mt-2 rounded-lg bg-red-100 px-3 py-2 text-xs text-red-700">{{ $doc->rejection_reason }}</p>
                    @endif

                    @if($doc->expiry_date)
                        <p class="mt-1 text-xs {{ $doc->isExpired() ? 'text-red-600 font-medium' : ($doc->isExpiringSoon() ? 'text-amber-600' : 'text-muted') }}">
                            Expires {{ $doc->expiry_date->format('d M Y') }}
                        </p>
                    @endif

                    <div class="mt-3 flex flex-wrap gap-2">
                        @if(in_array($doc->mime_type, ['application/pdf', 'image/jpeg', 'image/png']))
                            <a href="{{ route('admin.documents.preview', $doc) }}" target="_blank" class="rounded-lg bg-canvas px-3 py-1.5 text-xs font-medium text-ink hover:bg-line">Preview</a>
                        @endif
                        <a href="{{ route('admin.documents.download', $doc) }}" class="rounded-lg bg-canvas px-3 py-1.5 text-xs font-medium text-ink hover:bg-line">Download</a>
                        <a href="{{ route('admin.documents.versions', $doc) }}" class="rounded-lg bg-canvas px-3 py-1.5 text-xs font-medium text-muted hover:bg-line">v{{ $doc->version }}</a>

                        @can('documents.verify')
                            @if(in_array($doc->status, ['pending', 'under_review']))
                                <form method="POST" action="{{ route('admin.documents.verify', $doc) }}" class="inline">
                                    @csrf
                                    <button class="rounded-lg bg-green-100 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-200">Verify</button>
                                </form>
                                <div x-data="{ show: false }" class="inline">
                                    <button @click="show = true" class="rounded-lg bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-200">Reject</button>
                                    <div x-show="show" x-cloak class="mt-2 w-full">
                                        <form method="POST" action="{{ route('admin.documents.reject', $doc) }}">
                                            @csrf
                                            <textarea name="rejection_reason" required placeholder="Reason for rejection..." rows="2" class="w-full rounded-lg border-line text-xs focus:border-orange focus:ring-orange"></textarea>
                                            <div class="mt-1 flex gap-2">
                                                <button type="submit" class="rounded-lg bg-red-600 px-3 py-1 text-xs text-white">Confirm reject</button>
                                                <button type="button" @click="show = false" class="text-xs text-muted">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                            @if($doc->status === 'rejected')
                                @can('documents.reverse')
                                    <form method="POST" action="{{ route('admin.documents.reverse', $doc) }}" class="inline">
                                        @csrf
                                        <button class="rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-200">Reverse rejection</button>
                                    </form>
                                @endcan
                            @endif
                        @endcan
                    </div>
                @else
                    <p class="mt-3 text-xs text-muted">{{ $type->description ?? 'Not yet uploaded.' }}</p>
                    <a href="{{ route('admin.documents.upload', $candidate) }}?type={{ $type->id }}" class="mt-3 inline-block rounded-lg bg-navy px-3 py-1.5 text-xs font-medium text-white hover:bg-navy/90">Upload</a>
                @endif
            </div>
        @endforeach
    </div>
</x-layouts.crm>
