<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Support\Permissions;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Super admin's permissions list is ignored - it always holds everything.
        $superAdmin = Role::updateOrCreate(
            ['name' => Role::SUPER_ADMIN],
            ['label' => 'Super Admin', 'permissions' => Permissions::all(), 'is_system' => true],
        );

        $manager = [];
        foreach (['dashboard', 'bookings', 'services', 'products', 'orders', 'customers', 'instructors', 'blog'] as $section) {
            $manager[] = "{$section}.view";
            $manager[] = "{$section}.manage";
        }

        Role::updateOrCreate(
            ['name' => 'manager'],
            ['label' => 'Manager', 'permissions' => $manager, 'is_system' => false],
        );

        Role::updateOrCreate(
            ['name' => 'instructor'],
            [
                'label' => 'Instructor',
                // Linked instructor accounts only ever see their own bookings.
                'permissions' => ['dashboard.view', 'bookings.view', 'bookings.manage'],
                'is_system' => false,
            ],
        );

        // Never lock anyone out: existing accounts become super admins.
        User::whereNull('role_id')->update(['role_id' => $superAdmin->id]);
    }
}
