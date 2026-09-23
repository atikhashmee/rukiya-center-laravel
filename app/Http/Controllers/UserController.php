<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Support\Permissions;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with(['role', 'instructor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('verified')) {
            if ($request->verified === 'yes') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->verified === 'no') {
                $query->whereNull('email_verified_at');
            }
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return Inertia::render('users/index', [
            'users' => $users,
            'roles' => Role::orderBy('label')->get(['id', 'label']),
            'filters' => $request->only(['search', 'verified', 'role_id']),
        ]);
    }

    public function create()
    {
        return Inertia::render('users/create', $this->formOptions());
    }

    /** Roles and instructor records offered by the create/edit forms. */
    private function formOptions(): array
    {
        return [
            // Role permissions travel with each role so the form can show what the
            // chosen role already grants, and only ask for extras on top of it.
            'roles' => Role::orderBy('label')->get(['id', 'name', 'label', 'permissions']),
            'instructors' => Instructor::orderBy('name')->get(['id', 'name']),
            'permissionGroups' => Permissions::groups(),
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'email_verified_at' => 'nullable|date',
            'role_id' => 'required|exists:roles,id',
            'instructor_id' => 'nullable|exists:instructors,id',
            'permissions' => 'array',
            'permissions.*' => 'in:'.implode(',', Permissions::all()),
        ]);

        $validated['permissions'] = $validated['permissions'] ?? [];

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('users/edit', [
            'user' => $user,
            ...$this->formOptions(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role_id' => 'required|exists:roles,id',
            'instructor_id' => 'nullable|exists:instructors,id',
            'permissions' => 'array',
            'permissions.*' => 'in:'.implode(',', Permissions::all()),
        ]);

        $validated['permissions'] = $validated['permissions'] ?? [];

        // Don't let the last super admin (or yourself) drop super admin rights and lock the panel.
        if ($user->isSuperAdmin() && (int) $validated['role_id'] !== $user->role_id
            && User::where('role_id', $user->role_id)->count() === 1) {
            return back()->with('error', 'This is the last Super Admin. Assign another Super Admin first.');
        }

        // Only update password if provided
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }
}
