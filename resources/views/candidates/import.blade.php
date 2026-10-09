<x-layouts.crm title="Import students">
    <x-page-header title="Import students"
                   subtitle="Upload a spreadsheet exported from the old Excel tracker." />

    @if(session('import_errors'))
        <div class="mb-4 rounded-xl border border-warning/40 bg-warning/10 p-4">
            <p class="font-medium">Rows that were not imported</p>
            <ul class="mt-2 space-y-1 text-sm">
                @foreach(session('import_errors') as $error)
                    <li>Row {{ $error['row'] }} — {{ $error['reason'] }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('candidates.import.store') }}" enctype="multipart/form-data"
          class="max-w-xl space-y-5 rounded-xl border border-line bg-white p-6 shadow-glass">
        @csrf

        <div>
            <x-input-label for="file" value="Spreadsheet" />
            <input id="file" name="file" type="file" accept=".xlsx,.xls,.csv" required
                   class="mt-1 block w-full rounded-xl border border-line p-2 text-sm" />
            <x-input-error :messages="$errors->get('file')" class="mt-2" />
            <p class="mt-2 text-sm text-muted">
                Excel or CSV, up to 10 MB. Column names do not have to match exactly:
                the import recognises common variations such as "Mobile" for phone and
                "DOB" for date of birth. Only a name and an email are required per row.
            </p>
        </div>

        <label class="flex items-start gap-2 text-sm">
            <input type="hidden" name="skip_duplicates" value="0">
            <input type="checkbox" name="skip_duplicates" value="1" checked
                   class="mt-0.5 rounded border-line text-orange focus:ring-orange" />
            <span>
                Skip rows that match an existing student
                <span class="block text-xs text-muted">Compared on email, phone and passport number.</span>
            </span>
        </label>

        <div class="flex items-center gap-3">
            <x-primary-button>Import</x-primary-button>
            <a href="{{ route('candidates.index') }}" class="text-sm text-muted underline">Cancel</a>
        </div>
    </form>
</x-layouts.crm>
