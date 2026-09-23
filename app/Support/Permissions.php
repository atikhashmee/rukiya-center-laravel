<?php

namespace App\Support;

/**
 * Every admin section gets two permissions: "<section>.view" (read) and
 * "<section>.manage" (create/edit/delete). Routes are guarded with Laravel's
 * built-in "can:" middleware against gates registered in AppServiceProvider.
 */
class Permissions
{
    public const SECTIONS = [
        'dashboard' => 'Dashboard',
        'bookings' => 'Bookings',
        'services' => 'Services & categories',
        'products' => 'Products & categories',
        'orders' => 'Orders',
        'customers' => 'Customers',
        'instructors' => 'Instructors',
        'blog' => 'Blog',
        'users' => 'Admin users',
        'roles' => 'Roles & permissions',
        'themes' => 'Themes',
        'settings' => 'Site settings',
    ];

    /** All permission keys: ["dashboard.view", "dashboard.manage", ...] */
    public static function all(): array
    {
        $keys = [];

        foreach (array_keys(self::SECTIONS) as $section) {
            $keys[] = "{$section}.view";
            $keys[] = "{$section}.manage";
        }

        return $keys;
    }

    /** Grouped for the roles screen: [["key" => "bookings", "label" => "Bookings", "view" => "bookings.view", "manage" => "bookings.manage"], ...] */
    public static function groups(): array
    {
        return array_map(fn ($section, $label) => [
            'key' => $section,
            'label' => $label,
            'view' => "{$section}.view",
            'manage' => "{$section}.manage",
        ], array_keys(self::SECTIONS), self::SECTIONS);
    }
}
