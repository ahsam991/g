<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SliderController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize('viewAny', Slider::class);

        $sliders = Slider::orderBy('sort_order')->get();

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create(): View
    {
        $this->authorize('create', Slider::class);

        return view('admin.sliders.form', ['slider' => new Slider]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Slider::class);

        $validated = $request->validate($this->rules());

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $validated['sort_order'] = Slider::max('sort_order') + 1;

        Slider::create($validated);

        return redirect()->route('admin.sliders.index')->with('status', 'Slide created.');
    }

    public function edit(Slider $slider): View
    {
        $this->authorize('update', $slider);

        return view('admin.sliders.form', compact('slider'));
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $this->authorize('update', $slider);

        $validated = $request->validate($this->rules());

        if ($request->hasFile('image')) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')->with('status', 'Slide updated.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        $this->authorize('delete', $slider);

        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('status', 'Slide deleted.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $this->authorize('viewAny', Slider::class);

        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:sliders,id']);

        foreach ($request->ids as $index => $id) {
            Slider::where('id', $id)->update(['sort_order' => $index]);
        }

        return back()->with('status', 'Order updated.');
    }

    private function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'subtitle' => 'nullable|string|max:500',
            'cta_text' => 'nullable|string|max:100',
            'cta_link' => 'nullable|string|max:500',
            'cta2_text' => 'nullable|string|max:100',
            'cta2_link' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:5120',
            'badge_text' => 'nullable|string|max:200',
            'active' => 'sometimes|boolean',
        ];
    }
}
