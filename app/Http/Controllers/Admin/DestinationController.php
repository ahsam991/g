<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DestinationController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize('viewAny', Destination::class);

        $destinations = Destination::withCount('universities')->orderBy('sort_order')->get();

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create(): View
    {
        $this->authorize('create', Destination::class);

        return view('admin.destinations.form', ['destination' => new Destination]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Destination::class);

        $validated = $request->validate($this->rules());

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('destinations', 'public');
        }

        $validated['sort_order'] = Destination::max('sort_order') + 1;
        Destination::create($validated);

        return redirect()->route('admin.destinations.index')->with('status', 'Destination created.');
    }

    public function edit(Destination $destination): View
    {
        $this->authorize('update', $destination);

        return view('admin.destinations.form', compact('destination'));
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $this->authorize('update', $destination);

        $validated = $request->validate($this->rules());

        if ($request->hasFile('image')) {
            if ($destination->image) {
                Storage::disk('public')->delete($destination->image);
            }
            $validated['image'] = $request->file('image')->store('destinations', 'public');
        }

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')->with('status', 'Destination updated.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $this->authorize('delete', $destination);

        if ($destination->image) {
            Storage::disk('public')->delete($destination->image);
        }
        $destination->delete();

        return redirect()->route('admin.destinations.index')->with('status', 'Destination deleted.');
    }

    private function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'flag' => 'nullable|string|max:10',
            'image' => 'nullable|image|max:5120',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:5000',
            'gradient_classes' => 'nullable|string|max:200',
            'active' => 'sometimes|boolean',
            'featured' => 'sometimes|boolean',
        ];
    }
}
