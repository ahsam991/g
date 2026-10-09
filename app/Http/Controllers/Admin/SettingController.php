<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(private readonly Settings $settings) {}

    public function edit(): View
    {
        return view('admin.settings.edit', ['groups' => $this->settings->grouped()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*' => ['nullable', 'string', 'max:2000'],
        ]);

        $this->settings->setMany($validated['settings']);

        return redirect()->route('admin.settings.edit')->with('status', 'Settings saved.');
    }
}
