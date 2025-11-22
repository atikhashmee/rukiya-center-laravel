import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, create, edit, destroy } from "@/actions/App/Http/Controllers/UserController";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableCaption } from "@/components/ui/table";
import { Pencil, Trash2, PlusCircle, CheckCircle, XCircle, Mail, MailCheck, Shield, UserX } from 'lucide-react';
import Pagination from '@/components/pagination';

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
}

export default function Index({ users }: UsersIndexProps) {
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
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full">
                    
                    {/* Flash Messages */}
                    {flash?.success && (
                        <div className="flex items-center gap-3 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm animate-fade-in">
                            <CheckCircle className="h-5 w-5 text-green-600" />
                            <p className="text-sm font-medium text-green-800">{flash.success}</p>
                        </div>
                    )}
                    
                    {flash?.error && (
                        <div className="flex items-center gap-3 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm animate-fade-in">
                            <XCircle className="h-5 w-5 text-red-600" />
                            <p className="text-sm font-medium text-red-800">{flash.error}</p>
                        </div>
                    )}

                    {/* Header */}
                    <div className="flex justify-between items-center mb-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-800">User Management</h2>
                            <p className="text-sm text-gray-500 mt-1">Manage admin users and their verification status</p>
                        </div>
                        <Link 
                            href={create().url}
                            className="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center shadow-sm"
                        >
                            <PlusCircle className="mr-2 h-4 w-4" />
                            Add New User
                        </Link>
                    </div>

                    {/* Users Table */}
                    <div className="p-3 border rounded-xl bg-white shadow-xl overflow-x-auto">
                        <Table className="min-w-full">
                            <TableCaption>List of all admin users</TableCaption>
                            <TableHeader className="bg-gray-100/70">
                                <TableRow className="hover:bg-gray-100/70">
                                    <TableHead className="w-[50px] font-bold text-gray-700">#</TableHead>
                                    <TableHead className="font-bold text-gray-700">User Info</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Email Status</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Registered</TableHead>
                                    <TableHead className="text-center w-[180px] font-bold text-gray-700">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {users.data.length > 0 ? (
                                    users.data.map((user, index) => (
                                        <TableRow 
                                            key={user.id} 
                                            className={`hover:bg-indigo-50/50 transition-colors ${user.id === currentUserId ? 'bg-blue-50/30' : ''}`}
                                        >
                                            {/* Index */}
                                            <TableCell className="font-semibold text-gray-600">
                                                {users.from + index}
                                            </TableCell>

                                            {/* User Info */}
                                            <TableCell>
                                                <div className="flex items-center gap-3">
                                                    <div className="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                                        {user.name.charAt(0).toUpperCase()}
                                                    </div>
                                                    <div>
                                                        <div className="flex items-center gap-2">
                                                            <span className="font-semibold text-gray-800">{user.name}</span>
                                                            {user.id === currentUserId && (
                                                                <span className="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                                                    <Shield className="h-3 w-3 mr-1" /> You
                                                                </span>
                                                            )}
                                                        </div>
                                                        <div className="text-xs text-gray-500">{user.email}</div>
                                                    </div>
                                                </div>
                                            </TableCell>

                                            {/* Email Verified */}
                                            <TableCell className="text-center">
                                                {user.email_verified_at ? (
                                                    <div className="flex flex-col items-center">
                                                        <span className="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                            <MailCheck className="h-3 w-3 mr-1" /> Verified
                                                        </span>
                                                        <span className="text-xs text-gray-400 mt-1">
                                                            {formatDate(user.email_verified_at)}
                                                        </span>
                                                    </div>
                                                ) : (
                                                    <span className="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                        <Mail className="h-3 w-3 mr-1" /> Not Verified
                                                    </span>
                                                )}
                                            </TableCell>

                                            {/* Registered Date */}
                                            <TableCell className="text-center text-sm text-gray-600">
                                                {formatDate(user.created_at)}
                                            </TableCell>
                                            
                                            {/* Action Buttons */}
                                            <TableCell className="text-center">
                                                <div className="flex space-x-2 justify-center">
                                                    
                                                    
                                                    {/* Edit Button */}
                                                    <Button 
                                                        variant="outline" 
                                                        size="icon" 
                                                        className="h-8 w-8 hover:bg-indigo-100 border-indigo-300 text-indigo-600 transition-transform hover:scale-105"
                                                        onClick={() => router.visit(edit(user.id).url)}
                                                        title="Edit User"
                                                    >
                                                        <Pencil className="h-4 w-4" />
                                                    </Button>
                                                    
                                                    {/* Delete Button */}
                                                    <Button
                                                        variant="destructive"
                                                        size="icon"
                                                        className="h-8 w-8 bg-red-600 hover:bg-red-700 transition-transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
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
                                    <TableRow>
                                        <TableCell colSpan={5} className="h-24 text-center text-muted-foreground bg-gray-50/50">
                                            <div className="flex flex-col items-center justify-center">
                                                <UserX className="h-8 w-8 text-gray-400 mb-2" />
                                                <p>No users found. Click "Add New User" to begin.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                        <Pagination links={users.links} />
                    </div>

                    {/* Info Box */}
                    <div className="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                        <div className="flex items-start">
                            <Shield className="h-5 w-5 text-blue-600 mt-0.5 mr-3" />
                            <div>
                                <h3 className="text-sm font-semibold text-blue-900">Admin User Management</h3>
                                <p className="text-sm text-blue-700 mt-1">
                                    These are administrative users with access to the admin panel. You cannot delete your own account for security reasons.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}