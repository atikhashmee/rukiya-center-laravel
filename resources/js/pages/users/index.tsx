import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, create, edit, destroy, verifyEmail } from "@/actions/App/Http/Controllers/UserController";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Pencil, Trash2, Plus, CheckCircle, XCircle, Mail, MailCheck, Shield, UserX } from 'lucide-react';
import Pagination from '@/components/pagination';
import FilterBar from '@/components/filter-bar';
import PageHeader from '@/components/page-header';
import { badgeClasses, tones } from '@/lib/status';

const Link: React.FC<any> = ({ children, href, className, ...props }) => <a href={href} className={className} {...props}>{children}</a>;

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedData<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
}

interface UsersIndexProps {
    users: PaginatedData<User>;
    filters: Record<string, string>;
}

const th = "text-xs font-medium uppercase tracking-wide text-muted-foreground";

export default function Index({ users, filters }: UsersIndexProps) {
    const { flash, auth } = usePage().props as any;
    const currentUserId = auth?.user?.id;

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Users', href: index().url }
    ];

    const handleDelete = (userId: number, name: string) => {
        if (userId === currentUserId) {
            alert("You cannot delete your own account!");
            return;
        }

        if (window.confirm(`Are you sure you want to delete user "${name}"? This action cannot be undone.`)) {
            router.delete(destroy(userId).url, {
                onSuccess: () => {
                    console.log(`User ${name} deleted successfully.`);
                },
                onError: (errors: any) => {
                    console.error("Deletion failed:", errors);
                }
            });
        }
    };

    const handleVerifyEmail = (userId: number, currentStatus: string | null, name: string) => {
        const action = currentStatus ? 'unverify' : 'verify';
        if (window.confirm(`Are you sure you want to ${action} email for user "${name}"?`)) {
            router.patch(verifyEmail(userId).url, {}, {
                onSuccess: () => {
                    console.log(`User ${name} email ${action}ied successfully.`);
                },
                onError: (errors: any) => {
                    console.error("Email verification toggle failed:", errors);
                }
            });
        }
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Users Management" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="flex items-center gap-2 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-700 dark:text-emerald-300">
                        <CheckCircle className="h-4 w-4" />
                        {flash.success}
                    </div>
                )}

                {flash?.error && (
                    <div className="flex items-center gap-2 rounded-lg border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm font-medium text-rose-700 dark:text-rose-300">
                        <XCircle className="h-4 w-4" />
                        {flash.error}
                    </div>
                )}

                <PageHeader
                    title="User Management"
                    description="Manage admin users and their verification status"
                    actions={
                        <Button asChild>
                            <Link href={create().url}>
                                <Plus className="h-4 w-4" /> Add New User
                            </Link>
                        </Button>
                    }
                />

                <FilterBar
                    filters={filters}
                    placeholder="Search by name or email..."
                    baseUrl={index().url}
                    filterConfigs={[
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

                {/* Users Table */}
                <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                    <div className="overflow-x-auto">
                        <Table>
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={`w-[50px] ${th}`}>#</TableHead>
                                    <TableHead className={th}>User Info</TableHead>
                                    <TableHead className={`text-center ${th}`}>Email Status</TableHead>
                                    <TableHead className={`text-center ${th}`}>Registered</TableHead>
                                    <TableHead className={`w-[140px] text-center ${th}`}>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {users.data.length > 0 ? (
                                    users.data.map((user, index) => (
                                        <TableRow
                                            key={user.id}
                                            className={`hover:bg-muted/40 ${user.id === currentUserId ? 'bg-primary/5' : ''}`}
                                        >
                                            <TableCell className="text-muted-foreground tabular-nums">
                                                {users.from + index}
                                            </TableCell>

                                            <TableCell>
                                                <div className="flex items-center gap-3">
                                                    <div className="flex h-9 w-9 items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary">
                                                        {user.name.charAt(0).toUpperCase()}
                                                    </div>
                                                    <div>
                                                        <div className="flex items-center gap-2">
                                                            <span className="font-medium">{user.name}</span>
                                                            {user.id === currentUserId && (
                                                                <span className={`gap-1 ${badgeClasses('info')}`}>
                                                                    <Shield className="h-3 w-3" /> You
                                                                </span>
                                                            )}
                                                        </div>
                                                        <div className="text-xs text-muted-foreground">{user.email}</div>
                                                    </div>
                                                </div>
                                            </TableCell>

                                            <TableCell className="text-center">
                                                {user.email_verified_at ? (
                                                    <div className="flex flex-col items-center">
                                                        <span className={`gap-1 ${badgeClasses('success')}`}>
                                                            <MailCheck className="h-3 w-3" /> Verified
                                                        </span>
                                                        <span className="mt-1 text-xs text-muted-foreground">
                                                            {formatDate(user.email_verified_at)}
                                                        </span>
                                                    </div>
                                                ) : (
                                                    <span className={`gap-1 ${badgeClasses('warning')}`}>
                                                        <Mail className="h-3 w-3" /> Not Verified
                                                    </span>
                                                )}
                                            </TableCell>

                                            <TableCell className="text-center text-sm text-muted-foreground">
                                                {formatDate(user.created_at)}
                                            </TableCell>

                                            <TableCell className="text-center">
                                                <div className="flex justify-center gap-1">
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        className="h-8 w-8"
                                                        onClick={() => router.visit(edit(user.id).url)}
                                                        title="Edit User"
                                                    >
                                                        <Pencil className="h-4 w-4" />
                                                    </Button>
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        className="h-8 w-8 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                        onClick={() => handleDelete(user.id, user.name)}
                                                        disabled={user.id === currentUserId}
                                                        title={user.id === currentUserId ? "Cannot delete yourself" : "Delete User"}
                                                    >
                                                        <Trash2 className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow className="hover:bg-transparent">
                                        <TableCell colSpan={5}>
                                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                                <UserX className="h-8 w-8 opacity-40" />
                                                <p className="text-sm">No users found. Click "Add New User" to begin.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </div>
                    <Pagination links={users.links} />
                </div>

                {/* Info Box */}
                <div className="flex items-start gap-3 rounded-xl border bg-card p-4 text-card-foreground shadow-sm">
                    <div className={`rounded-lg p-2 ${tones.info}`}>
                        <Shield className="h-4 w-4" />
                    </div>
                    <div>
                        <h3 className="text-sm font-semibold">Admin User Management</h3>
                        <p className="mt-1 text-sm text-muted-foreground">
                            These are administrative users with access to the admin panel. You cannot delete your own account for security reasons.
                        </p>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
