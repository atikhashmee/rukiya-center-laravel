import React from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, create, destroy, edit } from '@/routes/serviceCategories';
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Pencil, Trash2, Plus, FolderOpen, CheckCircle } from 'lucide-react';
import Pagination from '@/components/pagination';
import FilterBar from '@/components/filter-bar';
import PageHeader from '@/components/page-header';
import { badgeClasses } from '@/lib/status';
import { useCan } from '@/lib/permissions';

interface ServiceCategory {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    icon: string | null;
    services_count: number;
}

interface PaginatedCategories {
    data: ServiceCategory[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

interface CategoriesIndexProps {
    categories: PaginatedCategories;
    filters: Record<string, string>;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Service Categories', href: index().url },
];

export default function Index({ categories, filters }: CategoriesIndexProps) {
    const { flash } = usePage().props as any;
    const canManage = useCan('services.manage');

    const handleDelete = (categoryId: number) => {
        if (confirm("Are you sure you want to delete this category? This action is irreversible.")) {
            router.delete(destroy(categoryId), { preserveScroll: true });
        }
    };

    const th = "text-xs font-medium uppercase tracking-wide text-muted-foreground";

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Service Categories" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                {flash?.success && (
                    <div className="flex items-center gap-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-700 dark:text-emerald-300">
                        <CheckCircle className="h-4 w-4 shrink-0" />
                        <p>{flash.success}</p>
                    </div>
                )}

                <PageHeader
                    title="Service Categories"
                    description={`${categories.total} total categories`}
                    actions={canManage && (
                        <Button onClick={() => window.location.href = create().url}>
                            <Plus className="h-4 w-4" /> Add Category
                        </Button>
                    )}
                />

                <FilterBar
                    filters={filters}
                    placeholder="Search by name..."
                    baseUrl={index().url}
                    filterConfigs={[]}
                />

                <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                    <div className="overflow-x-auto">
                        <Table>
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={th}>Name</TableHead>
                                    <TableHead className={th}>Slug</TableHead>
                                    <TableHead className={th}>Description</TableHead>
                                    <TableHead className={`text-center ${th}`}>Services</TableHead>
                                    <TableHead className={`w-[120px] text-right ${th}`}>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {categories.data.length > 0 ? (
                                    categories.data.map((category) => (
                                        <TableRow key={category.id} className="hover:bg-muted/40">
                                            <TableCell className="font-medium">{category.name}</TableCell>
                                            <TableCell className="font-mono text-xs text-muted-foreground">{category.slug}</TableCell>
                                            <TableCell className="max-w-[240px] truncate text-sm text-muted-foreground">{category.description ?? '—'}</TableCell>
                                            <TableCell className="text-center">
                                                <span className={badgeClasses('info')}>
                                                    {category.services_count}
                                                </span>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex justify-end gap-1">
                                                    {canManage && (
                                                        <>
                                                            <Button variant="ghost" size="icon" className="h-8 w-8"
                                                                onClick={() => window.location.href = edit(category.id).url}>
                                                                <Pencil className="h-4 w-4" />
                                                            </Button>
                                                            <Button variant="ghost" size="icon" className="h-8 w-8 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                                onClick={() => handleDelete(category.id)}>
                                                                <Trash2 className="h-4 w-4" />
                                                            </Button>
                                                        </>
                                                    )}
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow className="hover:bg-transparent">
                                        <TableCell colSpan={5}>
                                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                                <FolderOpen className="h-8 w-8 opacity-40" />
                                                <p className="text-sm">No categories found.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </div>
                    <Pagination links={categories.links} />
                </div>
            </div>
        </AppLayout>
    );
}
