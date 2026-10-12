<x-layouts.crm title="Upload Document">
    <x-page-header title="Upload Document" :subtitle="$candidate->first_name.' '.$candidate->last_name.' · '.$candidate->uid">
        <x-slot:actions>
            <a href="{{ route('admin.documents.candidate', $candidate) }}" class="rounded-xl border border-line px-4 py-2 text-sm font-medium text-ink hover:bg-canvas">Back to documents</a>
        </x-slot:actions>
    </x-page-header>

    <div class="mx-auto max-w-2xl">
        <form method="POST" action="{{ route('admin.documents.store', $candidate) }}" enctype="multipart/form-data"
              class="rounded-2xl border border-line bg-white p-6">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="document_type_id" class="block text-sm font-medium text-ink">Document type</label>
                    <select id="document_type_id" name="document_type_id" required
                            class="mt-1 w-full rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" @selected(request('type') == $type->id || old('document_type_id') == $type->id)>
                                {{ $type->name }}
                                @if($existingDocs->has($type->id)) (replace v{{ $existingDocs[$type->id]->version }}) @endif
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('document_type_id')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink">Files</label>
                    <div x-data="{ files: [] }"
                         class="mt-1 rounded-xl border-2 border-dashed border-line p-8 text-center transition-colors hover:border-orange/50"
                         x-on:dragover.prevent="$el.classList.add('border-orange')"
                         x-on:dragleave.prevent="$el.classList.remove('border-orange')"
                         x-on:drop.prevent="$el.classList.remove('border-orange'); files = $event.dataTransfer.files; $refs.input.files = $event.dataTransfer.files">
                        <svg class="mx-auto h-10 w-10 text-muted" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z"/></svg>
                        <p class="mt-2 text-sm text-muted">Drag and drop files here, or</p>
                        <label class="mt-2 inline-block cursor-pointer rounded-lg bg-orange px-4 py-2 text-sm font-semibold text-navy hover:bg-orange/90">
                            Browse files
                            <input x-ref="input" type="file" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="sr-only"
                                   @change="files = $event.target.files">
                        </label>
                        <p class="mt-2 text-xs text-muted">PDF, JPG, PNG, DOCX — max 10 MB each</p>
                        <template x-if="files.length > 0">
                            <div class="mt-3 space-y-1">
                                <template x-for="(file, i) in files" :key="i">
                                    <p class="text-xs text-ink" x-text="file.name + ' (' + (file.size / 1024 / 1024).toFixed(1) + ' MB)'"></p>
                                </template>
                            </div>
                        </template>
                    </div>
                    <x-input-error :messages="$errors->get('files')" class="mt-1" />
                    <x-input-error :messages="$errors->get('files.*')" class="mt-1" />
                </div>

                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-ink">Expiry date <span class="text-muted">(optional)</span></label>
                    <input id="expiry_date" type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                           class="mt-1 w-full rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                    <x-input-error :messages="$errors->get('expiry_date')" class="mt-1" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.documents.candidate', $candidate) }}" class="text-sm text-muted hover:text-ink">Cancel</a>
                <button type="submit" class="rounded-xl bg-navy px-6 py-2.5 text-sm font-semibold text-white transition-all hover:bg-navy/90 active:scale-[.98]">
                    Upload
                </button>
            </div>
        </form>
    </div>
</x-layouts.crm>
