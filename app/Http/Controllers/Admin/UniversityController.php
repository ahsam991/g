<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\University;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UniversityController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', University::class);

        $universities = University::with('destination:id,name')
            ->withCount('courses')
            ->when($request->input('destination'), fn ($q, $v) => $q->where('destination_id', $v))
            ->when($request->input('search'), fn ($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        $destinations = Destination::active()->get(['id', 'name']);

        return view('admin.universities.index', compact('universities', 'destinations'));
    }

    public function create(): View
    {
        $this->authorize('create', University::class);

        $destinations = Destination::active()->get(['id', 'name']);

        return view('admin.universities.form', ['university' => new University, 'destinations' => $destinations]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', University::class);

        $validated = $request->validate($this->rules());

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('universities', 'public');
        }

        University::create($validated);

        return redirect()->route('admin.universities.index')->with('status', 'University created.');
    }

    public function edit(University $university): View
    {
        $this->authorize('update', $university);

        $destinations = Destination::active()->get(['id', 'name']);

        return view('admin.universities.form', compact('university', 'destinations'));
    }

    public function update(Request $request, University $university): RedirectResponse
    {
        $this->authorize('update', $university);

        $validated = $request->validate($this->rules());

        if ($request->hasFile('logo')) {
            if ($university->logo) {
                Storage::disk('public')->delete($university->logo);
            }
            $validated['logo'] = $request->file('logo')->store('universities', 'public');
        }

        $university->update($validated);

        return redirect()->route('admin.universities.index')->with('status', 'University updated.');
    }

    public function destroy(University $university): RedirectResponse
    {
        $this->authorize('delete', $university);

        if ($university->logo) {
            Storage::disk('public')->delete($university->logo);
        }
        $university->delete();

        return redirect()->route('admin.universities.index')->with('status', 'University deleted.');
    }

    private function rules(): array
    {
        return [
            'name' => 'required|string|max:300',
            'destination_id' => 'required|exists:destinations,id',
            'logo' => 'nullable|image|max:2048',
            'website' => 'nullable|url|max:500',
            'city' => 'nullable|string|max:150',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:5000',
            'ranking' => 'nullable|string|max:100',
            'partner' => 'sometimes|boolean',
            'active' => 'sometimes|boolean',
            'featured' => 'sometimes|boolean',
        ];
    }
}
