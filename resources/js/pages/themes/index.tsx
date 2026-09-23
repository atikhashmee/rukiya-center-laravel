import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem, Theme } from "@/types";
import { dashboard } from '@/routes';
import { index, create, edit, destroy, activate, deactivate } from "@/actions/App/Http/Controllers/ThemeController";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Pencil, Trash2, Plus, CheckCircle, XCircle, Palette } from 'lucide-react';
import PageHeader from '@/components/page-header';
import { badgeClasses } from '@/lib/status';
import { useCan } from '@/lib/permissions';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Themes', href: index().url },
];

interface ThemesIndexPageProps {
    themes: Theme[];
}

const TH = "text-xs font-medium uppercase tracking-wide text-muted-foreground";

export default function ThemesIndex() {
    const { themes } = usePage().props as ThemesIndexPageProps;
    const canManage = useCan('themes.manage');

    const handleActivate = (theme: Theme) => {
        router.post(activate(theme.id).url, {}, {
            preserveScroll: true,
        });
    };

    const handleDeactivate = (theme: Theme) => {
        router.post(deactivate(theme.id).url, {}, {
            preserveScroll: true,
        });
    };

    const handleDelete = (theme: Theme) => {
        if (window.confirm(`Are you sure you want to delete "${theme.name}"?`)) {
            router.delete(destroy(theme.id).url, {
                preserveScroll: true,
            });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Themes" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title="Theme Management"
                    description="Create and manage website themes. Activate a theme to apply it to the public site."
                    actions={canManage && (
                        <Button onClick={() => window.location.href = create().url}>
                            <Plus className="h-4 w-4" />
                            New Theme
                        </Button>
                    )}
                />
                <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                    <div className="overflow-x-auto">
                        <Table>
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={`w-[100px] ${TH}`}>SL. NO.</TableHead>
                                    <TableHead className={TH}>Name</TableHead>
                                    <TableHead className={TH}>Slug</TableHead>
                                    <TableHead className={TH}>Description</TableHead>
                                    <TableHead className={TH}>Status</TableHead>
                                    <TableHead className={`text-right ${TH}`}>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {themes.length > 0 ? (
                                    themes.map((theme, index) => (
                                        <TableRow key={theme.id} className="hover:bg-muted/40">
                                            <TableCell className="text-muted-foreground">{index + 1}</TableCell>
                                            <TableCell className="font-medium">{theme.name}</TableCell>
                                            <TableCell className="font-mono text-xs text-muted-foreground">{theme.slug}</TableCell>
                                            <TableCell className="text-sm text-muted-foreground">{theme.description || '—'}</TableCell>
                                            <TableCell>
                                                {theme.is_active ? (
                                                    <span className={`gap-1 ${badgeClasses('success')}`}>
                                                        <CheckCircle className="h-3 w-3" /> Active
                                                    </span>
                                                ) : (
                                                    <span className={`gap-1 ${badgeClasses('neutral')}`}>
                                                        <XCircle className="h-3 w-3" /> Inactive
                                                    </span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-right">
                                                <div className="flex flex-row justify-end gap-1">
                                                    {canManage && (
                                                    <>
                                                    <Button
                                                        size="icon"
                                                        variant="ghost"
                                                        className="h-8 w-8"
                                                        title="Edit"
                                                        onClick={() => window.location.href = edit(theme.id).url}
                                                    >
                                                        <Pencil className="h-4 w-4" />
                                                    </Button>
                                                    {theme.is_active ? (
                                                        <Button
                                                            size="icon"
                                                            variant="ghost"
                                                            className="h-8 w-8"
                                                            title="Deactivate"
                                                            onClick={() => handleDeactivate(theme)}
                                                        >
                                                            <XCircle className="h-4 w-4" />
                                                        </Button>
                                                    ) : (
                                                        <Button
                                                            size="icon"
                                                            variant="ghost"
                                                            className="h-8 w-8 text-primary hover:bg-primary/10 hover:text-primary"
                                                            title="Activate"
                                                            onClick={() => handleActivate(theme)}
                                                        >
                                                            <CheckCircle className="h-4 w-4" />
                                                        </Button>
                                                    )}
                                                    {!theme.is_active && (
                                                        <Button
                                                            size="icon"
                                                            variant="ghost"
                                                            className="h-8 w-8 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                            title="Delete"
                                                            onClick={() => handleDelete(theme)}
                                                        >
                                                            <Trash2 className="h-4 w-4" />
                                                        </Button>
                                                    )}
                                                    </>
                                                    )}
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow className="hover:bg-transparent">
                                        <TableCell colSpan={6}>
                                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                                <Palette className="h-8 w-8 opacity-40" />
                                                <p className="text-sm">No themes found. Create your first theme to get started.</p>
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
