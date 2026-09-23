import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { Head, Link, useForm } from '@inertiajs/react';
import { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { index, update } from '@/actions/App/Http/Controllers/RoleController';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error';
import PageHeader from '@/components/page-header';
import { PermissionGrid, type PermissionGroup } from '@/components/permission-grid';
import { tones } from '@/lib/status';
import { ArrowLeft, Save, ShieldCheck } from 'lucide-react';

interface Role {
    id: number;
    name: string;
    label: string;
    permissions: string[] | null;
    is_system: boolean;
}

export default function Edit({ role, permissionGroups }: { role: Role; permissionGroups: PermissionGroup[] }) {
    const isSuperAdmin = role.name === 'super-admin';

    const { data, setData, errors, processing, put } = useForm<{ label: string; permissions: string[] }>({
        label: role.label,
        permissions: role.permissions ?? [],
    });

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Roles', href: index().url },
        { title: `Edit: ${role.label}`, href: '#' },
    ];

    // Super Admin implicitly holds everything, so its grid is shown full and locked.
    const has = (permission: string) => isSuperAdmin || data.permissions.includes(permission);

    /** Managing implies reading, so the two checkboxes move together in one direction. */
    const toggle = (group: PermissionGroup, key: 'view' | 'manage', checked: boolean) => {
        const add = checked ? (key === 'manage' ? [group.manage, group.view] : [group.view]) : [];
        const remove = checked ? [] : key === 'view' ? [group.view, group.manage] : [group.manage];

        setData('permissions', [...new Set([...data.permissions.filter((p) => !remove.includes(p)), ...add])]);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(update(role.id).url, { preserveScroll: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Role: ${role.label}`} />
            <div className="mx-auto flex w-full max-w-3xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={
                        <>
                            Editing Role: <span className="text-muted-foreground">{role.label}</span>
                        </>
                    }
                    description="Change the name, then pick what this role can see and change"
                    actions={
                        <Button variant="outline" asChild>
                            <Link href={index().url}>
                                <ArrowLeft className="h-4 w-4" />
                                Back to Roles
                            </Link>
                        </Button>
                    }
                />

                <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                    <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div className="border-b px-5 py-4">
                            <h2 className="font-semibold">Role details</h2>
                        </div>
                        <div className="grid gap-5 p-5">
                            <div className="grid gap-2">
                                <Label htmlFor="label">Role name *</Label>
                                <Input
                                    id="label"
                                    value={data.label}
                                    onChange={(e) => setData('label', e.target.value)}
                                    aria-invalid={!!errors.label}
                                />
                                <InputError message={errors.label} />
                            </div>
                        </div>
                    </div>

                    <PermissionGrid
                        groups={permissionGroups}
                        has={has}
                        toggle={toggle}
                        disabled={isSuperAdmin}
                        onSelectAll={() => setData('permissions', permissionGroups.flatMap((g) => [g.view, g.manage]))}
                        onClear={() => setData('permissions', [])}
                        error={errors.permissions}
                        note={
                            isSuperAdmin && (
                                <div className="flex items-start gap-3">
                                    <div className={`rounded-lg p-2 ${tones.accent}`}>
                                        <ShieldCheck className="h-4 w-4" />
                                    </div>
                                    <div>
                                        <p className="text-sm font-medium">Super Admin always has full access</p>
                                        <p className="mt-1 text-sm text-muted-foreground">
                                            Every permission stays on for this role, including any added later. Only the name can be
                                            edited here.
                                        </p>
                                    </div>
                                </div>
                            )
                        }
                    />

                    <div className="flex justify-end gap-2">
                        <Button variant="outline" asChild>
                            <Link href={index().url}>Cancel</Link>
                        </Button>
                        <Button type="submit" disabled={processing}>
                            <Save className="h-4 w-4" />
                            {processing ? 'Saving...' : 'Save Changes'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
