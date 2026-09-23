import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { Head, Link, useForm } from '@inertiajs/react';
import { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { index, store } from '@/actions/App/Http/Controllers/RoleController';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error';
import PageHeader from '@/components/page-header';
import { PermissionGrid, type PermissionGroup } from '@/components/permission-grid';
import { ArrowLeft, Save } from 'lucide-react';

export default function Create({ permissionGroups }: { permissionGroups: PermissionGroup[] }) {
    const { data, setData, errors, processing, post } = useForm<{ label: string; permissions: string[] }>({
        label: '',
        permissions: [],
    });

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Roles', href: index().url },
        { title: 'New role', href: '#' },
    ];

    const has = (permission: string) => data.permissions.includes(permission);

    /** Managing implies reading, so the two checkboxes move together in one direction. */
    const toggle = (group: PermissionGroup, key: 'view' | 'manage', checked: boolean) => {
        const add = checked ? (key === 'manage' ? [group.manage, group.view] : [group.view]) : [];
        const remove = checked ? [] : key === 'view' ? [group.view, group.manage] : [group.manage];

        setData('permissions', [...new Set([...data.permissions.filter((p) => !remove.includes(p)), ...add])]);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(store().url);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Role" />
            <div className="mx-auto flex w-full max-w-3xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title="Create Role"
                    description="Name the role, then pick what it can see and change"
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
                                    placeholder="e.g. Front desk"
                                />
                                <InputError message={errors.label} />
                            </div>
                        </div>
                    </div>

                    <PermissionGrid
                        groups={permissionGroups}
                        has={has}
                        toggle={toggle}
                        onSelectAll={() => setData('permissions', permissionGroups.flatMap((g) => [g.view, g.manage]))}
                        onClear={() => setData('permissions', [])}
                        error={errors.permissions}
                    />

                    <div className="flex justify-end gap-2">
                        <Button variant="outline" asChild>
                            <Link href={index().url}>Cancel</Link>
                        </Button>
                        <Button type="submit" disabled={processing}>
                            <Save className="h-4 w-4" />
                            {processing ? 'Creating...' : 'Create Role'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
