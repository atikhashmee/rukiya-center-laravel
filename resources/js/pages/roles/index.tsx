import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { index, create, edit, destroy } from '@/actions/App/Http/Controllers/RoleController';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Pencil, Trash2, Plus, CheckCircle, XCircle, ShieldCheck, Lock } from 'lucide-react';
import PageHeader from '@/components/page-header';
import { badgeClasses } from '@/lib/status';

interface Role {
    id: number;
    name: string;
    label: string;
    permissions: string[] | null;
    is_system: boolean;
    users_count: number;
}

interface PermissionGroup {
    key: string;
    label: string;
    view: string;
    manage: string;
}

interface RolesIndexProps {
    roles: Role[];
    permissionGroups: PermissionGroup[];
}

const th = 'text-xs font-medium uppercase tracking-wide text-muted-foreground';

export default function Index({ roles, permissionGroups }: RolesIndexProps) {
    const { flash } = usePage().props as any;

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Roles', href: index().url },
    ];

    const handleDelete = (role: Role) => {
        if (window.confirm(`Are you sure you want to delete the role "${role.label}"? This action cannot be undone.`)) {
            router.delete(destroy(role.id).url, {
                onError: (errors: any) => console.error('Deletion failed:', errors),
            });
        }
    };

    const totalPermissions = permissionGroups.length * 2;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Roles & Permissions" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                {flash?.success && (
                    <div className="flex items-center gap-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-700 dark:text-emerald-300">
                        <CheckCircle className="h-4 w-4 shrink-0" />
                        <p>{flash.success}</p>
                    </div>
                )}

                {flash?.error && (
                    <div className="flex items-center gap-3 rounded-lg border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm font-medium text-rose-700 dark:text-rose-300">
                        <XCircle className="h-4 w-4 shrink-0" />
                        <p>{flash.error}</p>
                    </div>
                )}

                <PageHeader
                    title="Roles & Permissions"
                    description="Decide what each admin role can see and change"
                    actions={
                        <Button asChild>
                            <Link href={create().url}>
                                <Plus className="h-4 w-4" />
                                New role
                            </Link>
                        </Button>
                    }
                />

                <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                    <div className="overflow-x-auto">
                        <Table className="min-w-full">
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={th}>Role</TableHead>
                                    <TableHead className={`text-center ${th}`}>Permissions</TableHead>
                                    <TableHead className={`text-center ${th}`}>Users</TableHead>
                                    <TableHead className={`w-[120px] text-center ${th}`}>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {roles.length > 0 ? (
                                    roles.map((role) => {
                                        const isSuperAdmin = role.name === 'super-admin';

                                        return (
                                            <TableRow key={role.id} className="transition-colors hover:bg-muted/40">
                                                <TableCell>
                                                    <div className="flex items-center gap-3">
                                                        <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                                                            <ShieldCheck className="h-4 w-4" />
                                                        </div>
                                                        <div className="min-w-0">
                                                            <div className="flex items-center gap-2">
                                                                <span className="font-medium">{role.label}</span>
                                                                {role.is_system && (
                                                                    <span className={`gap-1 ${badgeClasses('neutral')}`}>
                                                                        <Lock className="h-3 w-3" /> System
                                                                    </span>
                                                                )}
                                                            </div>
                                                            <div className="text-xs text-muted-foreground">{role.name}</div>
                                                        </div>
                                                    </div>
                                                </TableCell>

                                                <TableCell className="text-center">
                                                    {isSuperAdmin ? (
                                                        <span className={badgeClasses('accent')}>Full access</span>
                                                    ) : (
                                                        <span className={badgeClasses((role.permissions?.length ?? 0) > 0 ? 'info' : 'neutral')}>
                                                            {role.permissions?.length ?? 0} of {totalPermissions}
                                                        </span>
                                                    )}
                                                </TableCell>

                                                <TableCell className="text-center text-sm tabular-nums text-muted-foreground">
                                                    {role.users_count}
                                                </TableCell>

                                                <TableCell className="text-center">
                                                    <div className="flex justify-center gap-1">
                                                        <Button
                                                            variant="ghost"
                                                            size="icon"
                                                            className="h-8 w-8"
                                                            onClick={() => router.visit(edit(role.id).url)}
                                                            title="Edit role"
                                                        >
                                                            <Pencil className="h-4 w-4" />
                                                        </Button>
                                                        {!role.is_system && (
                                                            <Button
                                                                variant="ghost"
                                                                size="icon"
                                                                className="h-8 w-8 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                                onClick={() => handleDelete(role)}
                                                                title="Delete role"
                                                            >
                                                                <Trash2 className="h-4 w-4" />
                                                            </Button>
                                                        )}
                                                    </div>
                                                </TableCell>
                                            </TableRow>
                                        );
                                    })
                                ) : (
                                    <TableRow className="hover:bg-transparent">
                                        <TableCell colSpan={4}>
                                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                                <ShieldCheck className="h-8 w-8 opacity-40" />
                                                <p className="text-sm">No roles yet. Create one to get started.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
