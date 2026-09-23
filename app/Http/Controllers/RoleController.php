<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Support\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        return Inertia::render('roles/index', [
            'roles' => Role::withCount('users')->orderBy('label')->get(),
            'permissionGroups' => Permissions::groups(),
        ]);
    }

    public function create()
    {
        return Inertia::render('roles/create', [
            'permissionGroups' => Permissions::groups(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        Role::create([
            'name' => Str::slug($validated['label']) ?: Str::random(8),
            'label' => $validated['label'],
            'permissions' => $validated['permissions'] ?? [],
            'is_system' => false,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return Inertia::render('roles/edit', [
            'role' => $role,
            'permissionGroups' => Permissions::groups(),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $this->validated($request, $role);

        // The super admin role always holds everything; only its label is editable.
        $role->update([
            'label' => $validated['label'],
            'permissions' => $role->isSuperAdmin() ? Permissions::all() : ($validated['permissions'] ?? []),
        ]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return back()->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'This role is still assigned to users. Move them to another role first.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    private function validated(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:255', Rule::unique('roles', 'label')->ignore($role?->id)],
            'permissions' => ['array'],
            'permissions.*' => [Rule::in(Permissions::all())],
        ]);
    }
}
