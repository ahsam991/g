<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Support\Permissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('admin.roles.index', [
            'roles' => Role::with('permissions')->withCount('users')->orderBy('name')->get(),
            'catalogue' => Permissions::CATALOGUE,
        ]);
    }

    public function edit(Role $role): View
    {
        return view('admin.roles.edit', [
            'role' => $role->load('permissions'),
            'catalogue' => Permissions::CATALOGUE,
            'held' => $role->permissions->pluck('slug')->all(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,slug'],
        ]);

        // Admin must keep everything, or an administrator could lock themselves out.
        if ($role->slug === Role::ADMIN) {
            return back()->withErrors(['permissions' => 'The administrator role always holds every permission.']);
        }

        $role->permissions()->sync(
            Permission::whereIn('slug', $validated['permissions'] ?? [])->pluck('id')
        );

        return redirect()->route('admin.roles.index')->with('status', "{$role->name} permissions saved.");
    }
}
