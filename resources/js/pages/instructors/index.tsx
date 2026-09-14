import React from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Pencil, Trash2, Plus, CheckCircle, GraduationCap } from 'lucide-react';
import Pagination from '@/components/pagination';
import FilterBar from '@/components/filter-bar';
import PageHeader from '@/components/page-header';
import { badgeClasses } from '@/lib/status';

interface Instructor {
    id: number;
    name: string;
    email: string | null;
    phone: string | null;
    bio: string | null;
    is_active: boolean;
    services_count: number;
}

interface PaginatedInstructors {
    data: Instructor[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

interface InstructorsIndexProps {
    instructors: PaginatedInstructors;
    filters: Record<string, string>;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Instructors', href: '/admin/instructors' },
];

const TH = "text-xs font-medium uppercase tracking-wide text-muted-foreground";

export default function Index({ instructors, filters }: InstructorsIndexProps) {
    const { flash } = usePage().props as any;

    const handleDelete = (id: number) => {
        if (confirm("Are you sure you want to delete this instructor?")) {
            router.delete(`/admin/instructors/${id}`, { preserveScroll: true });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Instructors" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                {flash?.success && (
                    <div className="flex items-center gap-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-700 dark:text-emerald-300">
                        <CheckCircle className="h-4 w-4 shrink-0" />
                        <p>{flash.success}</p>
                    </div>
                )}

                <PageHeader
                    title="Instructors"
                    description={`${instructors.total} total instructors`}
                    actions={
                        <Button onClick={() => window.location.href = '/admin/instructors/create'}>
                            <Plus className="h-4 w-4" /> Add Instructor
                        </Button>
                    }
                />

                <FilterBar
                    filters={filters}
                    placeholder="Search by name or email..."
                    baseUrl="/admin/instructors"
                    filterConfigs={[
                        { key: 'status', label: 'Status', options: [
                            { value: 'active', label: 'Active' },
                            { value: 'inactive', label: 'Inactive' },
                        ]},
                    ]}
                />

                <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                    <div className="overflow-x-auto">
                        <Table className="min-w-full">
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={TH}>Name</TableHead>
                                    <TableHead className={TH}>Email</TableHead>
                                    <TableHead className={TH}>Phone</TableHead>
                                    <TableHead className={`text-center ${TH}`}>Status</TableHead>
                                    <TableHead className={`text-center ${TH}`}>Services</TableHead>
                                    <TableHead className={`w-[150px] text-center ${TH}`}>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {instructors.data.length > 0 ? (
                                    instructors.data.map((instructor) => (
                                        <TableRow key={instructor.id} className="transition-colors hover:bg-muted/40">
                                            <TableCell>
                                                <div className="flex items-center gap-3">
                                                    <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary">
                                                        {instructor.name.charAt(0).toUpperCase()}
                                                    </div>
                                                    <span className="font-medium">{instructor.name}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell className="text-sm text-muted-foreground">{instructor.email || '—'}</TableCell>
                                            <TableCell className="text-sm text-muted-foreground">{instructor.phone || '—'}</TableCell>
                                            <TableCell className="text-center">
                                                <span className={badgeClasses(instructor.is_active ? 'success' : 'danger')}>
                                                    {instructor.is_active ? 'Active' : 'Inactive'}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className={badgeClasses('info')}>
                                                    {instructor.services_count}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex justify-center gap-1">
                                                    <Button variant="ghost" size="icon" className="h-8 w-8" title="Edit"
                                                        onClick={() => window.location.href = `/admin/instructors/${instructor.id}/edit`}>
                                                        <Pencil className="h-4 w-4" />
                                                    </Button>
                                                    <Button variant="ghost" size="icon" title="Delete"
                                                        className="h-8 w-8 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                        onClick={() => handleDelete(instructor.id)}>
                                                        <Trash2 className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow className="hover:bg-transparent">
                                        <TableCell colSpan={6}>
                                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                                <GraduationCap className="h-8 w-8 opacity-40" />
                                                <p className="text-sm">No instructors found.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </div>
                    <Pagination links={instructors.links} />
                </div>
            </div>
        </AppLayout>
    );
}
