import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, create, edit, destroy, verifyEmail } from "@/actions/App/Http/Controllers/CustomerController";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableCaption } from "@/components/ui/table";
import { Pencil, Trash2, PlusCircle, CheckCircle, XCircle, Mail, MailCheck, Power, UserX, UserCheck } from 'lucide-react';
import Pagination from '@/components/pagination';
import FilterBar from '@/components/filter-bar';

const Link: React.FC<any> = ({ children, href, className, ...props }) => <a href={href} className={className} {...props}>{children}</a>;

interface Customer {
    id: number;
    name: string;
    email: string;
    phone_prefix: string | null;
    phone: string | null;
    interests: string[] | null;
    email_verified_at: string | null;
    about: string | null;
    is_active: boolean;
    created_at: string;
}

interface PaginatedCustomers {
    data: Customer[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

interface CustomersIndexProps {
    customers: PaginatedCustomers;
    filters: Record<string, string>;
}

export default function Index({ customers, filters }: CustomersIndexProps) {
    const { flash } = usePage().props as any;

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Customers', href: index().url }
    ];

    const handleDelete = (customerId: number, name: string) => {
        if (window.confirm(`Are you sure you want to delete customer "${name}"? This action cannot be undone.`)) {
            router.delete(destroy(customerId).url, {
                onSuccess: () => {},
                onError: (errors: any) => console.error("Deletion failed:", errors),
            });
        }
    };

    const handleVerifyEmail = (customerId: number, currentStatus: string | null, name: string) => {
        const action = currentStatus ? 'unverify' : 'verify';
        if (window.confirm(`Are you sure you want to ${action} email for customer "${name}"?`)) {
            router.post(verifyEmail(customerId).url, {}, {
                onSuccess: () => {},
                onError: (errors: any) => console.error("Email verification toggle failed:", errors),
            });
        }
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-GB', {
            day: '2-digit', month: 'short', year: 'numeric'
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Customers Management" />
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full">
                    {flash?.success && (
                        <div className="flex items-center gap-3 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm">
                            <CheckCircle className="h-5 w-5 text-green-600" />
                            <p className="text-sm font-medium text-green-800">{flash.success}</p>
                        </div>
                    )}

                    {flash?.error && (
                        <div className="flex items-center gap-3 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm">
                            <XCircle className="h-5 w-5 text-red-600" />
                            <p className="text-sm font-medium text-red-800">{flash.error}</p>
                        </div>
                    )}

                    <div className="flex justify-between items-center mb-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-800">Customer Management</h2>
                            <p className="text-sm text-gray-500 mt-1">{customers.total} total customers</p>
                        </div>
                        <Link
                            href={create().url}
                            className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center shadow-sm"
                        >
                            <PlusCircle className="mr-2 h-4 w-4" />
                            Add New Customer
                        </Link>
                    </div>

                    <FilterBar
                        filters={filters}
                        placeholder="Search by name or email..."
                        baseUrl={index().url}
                        filterConfigs={[
                            {
                                key: 'status',
                                label: 'All Status',
                                options: [
                                    { label: 'Active', value: 'active' },
                                    { label: 'Inactive', value: 'inactive' },
                                ],
                            },
                            {
                                key: 'verified',
                                label: 'All Verification',
                                options: [
                                    { label: 'Verified', value: 'yes' },
                                    { label: 'Not Verified', value: 'no' },
                                ],
                            },
                        ]}
                    />

                    <div className="p-3 border rounded-xl bg-white shadow-xl overflow-x-auto">
                        <Table className="min-w-full">
                            <TableCaption>List of all registered customers</TableCaption>
                            <TableHeader className="bg-gray-100/70">
                                <TableRow className="hover:bg-gray-100/70">
                                    <TableHead className="font-bold text-gray-700">Customer Info</TableHead>
                                    <TableHead className="font-bold text-gray-700">Contact</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Status</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Email Verified</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Joined</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700 w-[200px]">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {customers.data.length > 0 ? (
                                    customers.data.map((customer) => (
                                        <TableRow
                                            key={customer.id}
                                            className={`hover:bg-gray-50 transition-colors ${!customer.is_active ? 'opacity-60' : ''}`}
                                        >
                                            <TableCell>
                                                <div className="flex items-center gap-3">
                                                    <div className={`h-10 w-10 rounded-full flex items-center justify-center text-white font-semibold ${customer.is_active ? 'bg-teal-600' : 'bg-gray-400'}`}>
                                                        {customer.name.charAt(0).toUpperCase()}
                                                    </div>
                                                    <div>
                                                        <div className="font-semibold text-gray-800">{customer.name}</div>
                                                        <div className="text-xs text-gray-500">{customer.email}</div>
                                                    </div>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                {customer.phone ? (
                                                    <div className="text-sm text-gray-700">{customer.phone_prefix} {customer.phone}</div>
                                                ) : (
                                                    <span className="text-xs text-gray-400 italic">No phone</span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${customer.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                                                    {customer.is_active ? 'Active' : 'Inactive'}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                {customer.email_verified_at ? (
                                                    <span className="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                        <MailCheck className="h-3 w-3 mr-1" /> Verified
                                                    </span>
                                                ) : (
                                                    <span className="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                        <Mail className="h-3 w-3 mr-1" /> Pending
                                                    </span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-center text-sm text-gray-600">
                                                {formatDate(customer.created_at)}
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex space-x-1 justify-center">
                                                    <Button
                                                        variant="outline"
                                                        size="icon"
                                                        className="h-8 w-8 hover:bg-indigo-100 border-indigo-300 text-indigo-600 transition-transform hover:scale-105"
                                                        onClick={() => router.visit(edit(customer.id).url)}
                                                        title="Edit"
                                                    >
                                                        <Pencil className="h-4 w-4" />
                                                    </Button>
                                                    <Button
                                                        variant="destructive"
                                                        size="icon"
                                                        className="h-8 w-8 bg-red-600 hover:bg-red-700 transition-transform hover:scale-105"
                                                        onClick={() => handleDelete(customer.id, customer.name)}
                                                        title="Delete"
                                                    >
                                                        <Trash2 className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow>
                                        <TableCell colSpan={6} className="h-24 text-center text-gray-400">
                                            No customers found matching your filters.
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                        <Pagination links={customers.links} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
