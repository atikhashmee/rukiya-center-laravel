<?php

use App\Models\Role;
use App\Models\User;
use App\Support\Permissions;
use Illuminate\Database\Migrations\Migration;

/**
 * Seeds the starter roles and gives every existing admin account the super admin
 * role. Without this, a deployment that only runs migrations leaves every user
 * role-less, and every admin page (including the redirect after login) returns 403.
 * Idempotent: safe to run on databases that already have roles.
 */
return new class extends Migration
{
    public function up(): void
    {
        $superAdmin = Role::updateOrCreate(
            ['name' => Role::SUPER_ADMIN],
            ['label' => 'Super Admin', 'permissions' => Permissions::all(), 'is_system' => true],
        );

        $manager = [];
        foreach (['dashboard', 'bookings', 'services', 'products', 'orders', 'customers', 'instructors', 'blog'] as $section) {
            $manager[] = "{$section}.view";
            $manager[] = "{$section}.manage";
        }

        Role::firstOrCreate(
            ['name' => 'manager'],
            ['label' => 'Manager', 'permissions' => $manager, 'is_system' => false],
        );

        Role::firstOrCreate(
            ['name' => 'instructor'],
            ['label' => 'Instructor', 'permissions' => ['dashboard.view', 'bookings.view', 'bookings.manage'], 'is_system' => false],
        );

        User::whereNull('role_id')->update(['role_id' => $superAdmin->id]);
    }

    public function down(): void
    {
        // Roles are data, not schema: dropping the roles table (previous migration) removes them.
    }
};
