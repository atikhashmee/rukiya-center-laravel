import { usePage } from '@inertiajs/react';

/**
 * Permission keys are "<section>.view" / "<section>.manage", shared from
 * HandleInertiaRequests as auth.permissions. Super admins receive every key.
 */
export function usePermissions(): string[] {
    const { auth } = usePage().props as unknown as { auth?: { permissions?: string[] } };

    return auth?.permissions ?? [];
}

/** True when the signed-in user holds this permission. Use inside components. */
export function useCan(permission: string): boolean {
    return usePermissions().includes(permission);
}

/** Check several at once: useCanAny(['bookings.view', 'orders.view']). */
export function useCanAny(permissions: string[]): boolean {
    const held = usePermissions();

    return permissions.some((p) => held.includes(p));
}
