import React from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableCaption } from "@/components/ui/table";
import { Pencil, Trash2, PlusCircle } from 'lucide-react';
import Pagination from '@/components/pagination';
import FilterBar from '@/components/filter-bar';

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
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full">
                    {flash?.success && (
                        <div className="p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                            <p className="text-sm font-medium text-green-800">{flash.success}</p>
                        </div>
                    )}

                    <div className="flex justify-between items-center mb-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-800">Instructors</h2>
                            <p className="text-sm text-gray-500 mt-1">{instructors.total} total instructors</p>
                        </div>
                        <Button onClick={() => window.location.href = '/admin/instructors/create'} className="bg-blue-600 hover:bg-blue-700 text-white">
                            <PlusCircle className="mr-2 h-4 w-4" /> Add Instructor
                        </Button>
                    </div>

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

                    <div className="p-3 border rounded-xl bg-white shadow-xl overflow-x-auto">
                        <Table className="min-w-full">
                            <TableCaption>A list of all instructors.</TableCaption>
                            <TableHeader className="bg-gray-100/70">
                                <TableRow>
                                    <TableHead className="font-bold text-gray-700">Name</TableHead>
                                    <TableHead className="font-bold text-gray-700">Email</TableHead>
                                    <TableHead className="font-bold text-gray-700">Phone</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Status</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Services</TableHead>
                                    <TableHead className="text-center w-[150px] font-bold text-gray-700">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {instructors.data.length > 0 ? (
                                    instructors.data.map((instructor) => (
                                        <TableRow key={instructor.id} className="hover:bg-gray-50 transition-colors">
                                            <TableCell className="font-semibold text-gray-800">{instructor.name}</TableCell>
                                            <TableCell className="text-sm text-gray-500">{instructor.email || '—'}</TableCell>
                                            <TableCell className="text-sm text-gray-500">{instructor.phone || '—'}</TableCell>
                                            <TableCell className="text-center">
                                                <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${instructor.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                                                    {instructor.is_active ? 'Active' : 'Inactive'}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                                    {instructor.services_count}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex gap-2 justify-center">
                                                    <Button variant="outline" size="icon" className="h-8 w-8"
                                                        onClick={() => window.location.href = `/admin/instructors/${instructor.id}/edit`}>
                                                        <Pencil className="h-4 w-4" />
                                                    </Button>
                                                    <Button variant="destructive" size="icon" className="h-8 w-8"
                                                        onClick={() => handleDelete(instructor.id)}>
                                                        <Trash2 className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow>
                                        <TableCell colSpan={6} className="h-24 text-center text-gray-400">
                                            No instructors found.
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                        <Pagination links={instructors.links} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
