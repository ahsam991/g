<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize('viewAny', Service::class);

        return view('admin.services.index', ['services' => Service::orderBy('sort_order')->get()]);
    }

    public function create(): View
    {
        $this->authorize('create', Service::class);

        return view('admin.services.form', ['service' => new Service]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Service::class);

        $validated = $request->validate($this->rules());
        $validated['sort_order'] = Service::max('sort_order') + 1;
        Service::create($validated);

        return redirect()->route('admin.services.index')->with('status', 'Service created.');
    }

    public function edit(Service $service): View
    {
        $this->authorize('update', $service);

        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->authorize('update', $service);

        $service->update($request->validate($this->rules()));

        return redirect()->route('admin.services.index')->with('status', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->authorize('delete', $service);

        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Service deleted.');
    }

    private function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'icon_svg' => 'nullable|string|max:2000',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:5000',
            'active' => 'sometimes|boolean',
            'featured' => 'sometimes|boolean',
        ];
    }
}
