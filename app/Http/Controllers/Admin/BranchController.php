<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(): View
    {
        return view('admin.branches.index', [
            'branches' => Branch::withCount('users')->orderBy('name')->paginate(settings('crm.per_page', 20)),
        ]);
    }

    public function create(): View
    {
        return view('admin.branches.form', ['branch' => new Branch]);
    }

    public function store(Request $request): RedirectResponse
    {
        Branch::create($this->validated($request));

        return redirect()->route('admin.branches.index')->with('status', 'Office added.');
    }

    public function edit(Branch $branch): View
    {
        return view('admin.branches.form', ['branch' => $branch]);
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $branch->update($this->validated($request, $branch));

        return redirect()->route('admin.branches.index')->with('status', 'Office updated.');
    }

    private function validated(Request $request, ?Branch $branch = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'code' => ['required', 'string', 'max:10', Rule::unique('branches', 'code')->ignore($branch?->id)],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:80'],
            'country' => ['nullable', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:190'],
            'is_active' => ['boolean'],
        ]);
    }
}
