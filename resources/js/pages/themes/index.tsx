import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem, Theme } from "@/types";
import { dashboard } from '@/routes';
import { index, create, edit, destroy, activate, deactivate } from "@/actions/App/Http/Controllers/ThemeController";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableCaption } from "@/components/ui/table";
import { Pencil, Trash2, PlusCircle, CheckCircle, XCircle, Palette } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Themes', href: index().url },
];

interface ThemesIndexPageProps {
    themes: Theme[];
}

export default function ThemesIndex() {
    const { themes } = usePage().props as ThemesIndexPageProps;

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
            <div className="container p-4">
                <div className="flex flex-col justify-between gap-3">
                    <div className="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div className="md:col-span-10">
                            <h1 className="text-2xl font-bold flex items-center gap-2">
                                <Palette className="h-6 w-6" />
                                Theme Management
                            </h1>
                            <p className="text-muted-foreground mt-1">
                                Create and manage website themes. Activate a theme to apply it to the public site.
                            </p>
                        </div>
                        <Button className="md:col-start-11 md:col-span-2 bg-blue-600 hover:bg-blue-700 text-white" onClick={() => window.location.href = create().url}>
                            <PlusCircle className="h-4 w-4 mr-2" />
                            New Theme
                        </Button>
                    </div>
                    <div className="p-3 border rounded-xl bg-white shadow-xl md:col-span-12">
                        <Table>
                            <TableCaption>A list of all themes.</TableCaption>
                            <TableHeader>
                                <TableRow>
                                    <TableHead className="w-[100px]">SL. NO.</TableHead>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Slug</TableHead>
                                    <TableHead>Description</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {themes.length > 0 ? (
                                    themes.map((theme, index) => (
                                        <TableRow key={theme.id}>
                                            <TableCell className="font-medium">{index + 1}</TableCell>
                                            <TableCell className="font-semibold">{theme.name}</TableCell>
                                            <TableCell className="text-muted-foreground">{theme.slug}</TableCell>
                                            <TableCell>{theme.description || '—'}</TableCell>
                                            <TableCell>
                                                {theme.is_active ? (
                                                    <span className="inline-flex items-center gap-1 text-green-600 font-medium">
                                                        <CheckCircle className="h-4 w-4" /> Active
                                                    </span>
                                                ) : (
                                                    <span className="inline-flex items-center gap-1 text-muted-foreground">
                                                        <XCircle className="h-4 w-4" /> Inactive
                                                    </span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-right">
                                                <div className="flex flex-row gap-2 justify-end">
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        onClick={() => window.location.href = edit(theme.id).url}
                                                    >
                                                        <Pencil className="h-4 w-4" />
                                                    </Button>
                                                    {theme.is_active ? (
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => handleDeactivate(theme)}
                                                        >
                                                            <XCircle className="h-4 w-4" />
                                                        </Button>
                                                    ) : (
                                                        <Button
                                                            size="sm"
                                                            className="bg-blue-600 hover:bg-blue-700 text-white"
                                                            onClick={() => handleActivate(theme)}
                                                        >
                                                            <CheckCircle className="h-4 w-4" />
                                                        </Button>
                                                    )}
                                                    {!theme.is_active && (
                                                        <Button
                                                            size="sm"
                                                            variant="destructive"
                                                            onClick={() => handleDelete(theme)}
                                                        >
                                                            <Trash2 className="h-4 w-4" />
                                                        </Button>
                                                    )}
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow>
                                        <TableCell colSpan={6} className="text-center text-muted-foreground py-8">
                                            No themes found. Create your first theme to get started.
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
