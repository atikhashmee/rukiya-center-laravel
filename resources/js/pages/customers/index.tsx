import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, create, edit, destroy, verifyEmail } from "@/actions/App/Http/Controllers/CustomerController";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Pencil, Trash2, Plus, CheckCircle, XCircle, Mail, MailCheck, Users } from 'lucide-react';
import Pagination from '@/components/pagination';
import FilterBar from '@/components/filter-bar';
import PageHeader from '@/components/page-header';
import { badgeClasses } from '@/lib/status';
import { useCan } from '@/lib/permissions';

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

const TH = "text-xs font-medium uppercase tracking-wide text-muted-foreground";

export default function Index({ customers, filters }: CustomersIndexProps) {
    const { flash } = usePage().props as any;
    const canManage = useCan('customers.manage');

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
                    title="Customer Management"
                    description={`${customers.total} total customers`}
                    actions={canManage && (
                        <Button asChild>
                            <Link href={create().url}>
                                <Plus className="h-4 w-4" />
                                Add New Customer
                            </Link>
                        </Button>
                    )}
                />

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

                <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                    <div className="overflow-x-auto">
                        <Table className="min-w-full">
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={TH}>Customer Info</TableHead>
                                    <TableHead className={TH}>Contact</TableHead>
                                    <TableHead className={`text-center ${TH}`}>Status</TableHead>
                                    <TableHead className={`text-center ${TH}`}>Email Verified</TableHead>
                                    <TableHead className={`text-center ${TH}`}>Joined</TableHead>
                                    <TableHead className={`w-[200px] text-center ${TH}`}>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {customers.data.length > 0 ? (
                                    customers.data.map((customer) => (
                                        <TableRow
                                            key={customer.id}
                                            className={`transition-colors hover:bg-muted/40 ${!customer.is_active ? 'opacity-60' : ''}`}
                                        >
                                            <TableCell>
                                                <div className="flex items-center gap-3">
                                                    <div className={`flex h-10 w-10 shrink-0 items-center justify-center rounded-full font-semibold ${customer.is_active ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground'}`}>
                                                        {customer.name.charAt(0).toUpperCase()}
                                                    </div>
                                                    <div>
                                                        <div className="font-medium">{customer.name}</div>
                                                        <div className="text-xs text-muted-foreground">{customer.email}</div>
                                                    </div>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                {customer.phone ? (
                                                    <div className="text-sm">{customer.phone_prefix} {customer.phone}</div>
                                                ) : (
                                                    <span className="text-xs italic text-muted-foreground">No phone</span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className={badgeClasses(customer.is_active ? 'success' : 'danger')}>
                                                    {customer.is_active ? 'Active' : 'Inactive'}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                {customer.email_verified_at ? (
                                                    <span className={`gap-1 ${badgeClasses('info')}`}>
                                                        <MailCheck className="h-3 w-3" /> Verified
                                                    </span>
                                                ) : (
                                                    <span className={`gap-1 ${badgeClasses('warning')}`}>
                                                        <Mail className="h-3 w-3" /> Pending
                                                    </span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-center text-sm text-muted-foreground">
                                                {formatDate(customer.created_at)}
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex justify-center gap-1">
                                                    {canManage && (
                                                        <>
                                                            <Button
                                                                variant="ghost"
                                                                size="icon"
                                                                className="h-8 w-8"
                                                                onClick={() => router.visit(edit(customer.id).url)}
                                                                title="Edit"
                                                            >
                                                                <Pencil className="h-4 w-4" />
                                                            </Button>
                                                            <Button
                                                                variant="ghost"
                                                                size="icon"
                                                                className="h-8 w-8 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                                onClick={() => handleDelete(customer.id, customer.name)}
                                                                title="Delete"
                                                            >
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
                                        <TableCell colSpan={6}>
                                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                                <Users className="h-8 w-8 opacity-40" />
                                                <p className="text-sm">No customers found matching your filters.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </div>
                    <Pagination links={customers.links} />
                </div>
            </div>
        </AppLayout>
    );
}
