<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->with(['role', 'branch'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q')->toString();

                $query->where(fn ($q) => $q
                    ->where('name', 'like', "{$term}%")
                    ->orWhere('email', 'like', "{$term}%"));
            })
            ->when($request->filled('role'), fn ($q) => $q->whereRelation('role', 'slug', $request->string('role')))
            ->orderBy('name')
            ->paginate(settings('crm.per_page', 20));

        return view('admin.users.index', [
            'users' => $users,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', [
            'user' => new User,
            'roles' => Role::orderBy('name')->get(),
            'branches' => Branch::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        $user->forceFill(['email_verified_at' => now()])->save();

        return redirect()->route('admin.users.index')
            ->with('status', "{$user->name} added.");
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
            'branches' => Branch::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Leaving the password blank means "keep the current one".
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', "{$user->name} updated.");
    }

    /** Suspends rather than deletes, so audit and finance history survives. */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()->hasPermission('users.manage'), 403);

        if ($user->is($request->user())) {
            return back()->withErrors(['user' => 'You cannot suspend your own account.']);
        }

        $user->update(['status' => 'suspended']);

        return redirect()->route('admin.users.index')->with('status', "{$user->name} suspended.");
    }
}
