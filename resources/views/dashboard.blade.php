<x-layouts.crm title="Dashboard">
    <x-page-header :title="'Hello, '.Str::before(auth()->user()->name, ' ')"
                   :subtitle="auth()->user()->role?->name.' · '.(auth()->user()->branch?->name ?? 'No office set')" />

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Students" value="—" />
        <x-stat-card label="Open applications" value="—" />
        <x-stat-card label="Documents to verify" value="—" tone="warning" />
        <x-stat-card label="Tasks due today" value="—" tone="orange" />
    </div>

    <div class="mt-6">
        <x-empty-state
            title="Your dashboard fills up as the system is built"
            message="Live counts, charts and the application funnel arrive with the reporting step. Everything you see here already reads from the database." />
    </div>
</x-layouts.crm>
