import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, useForm } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, update } from "@/actions/App/Http/Controllers/UserController";
import { CornerUpLeft, Save, UserCog, AlertCircle } from 'lucide-react';

const Link: React.FC<any> = ({ children, href, className, ...props }) => <a href={href} className={className} {...props}>{children}</a>;
const Button: React.FC<any> = ({ children, className = '', ...props }) => <button {...props} className={`px-4 py-2 rounded-lg font-medium transition-colors ${className}`}>{children}</button>;
const Input: React.FC<any> = (props) => <input {...props} className="border border-gray-300 p-2 rounded-lg w-full focus:ring-indigo-500 focus:border-indigo-500" />;
const Label: React.FC<any> = ({ children, ...props }) => <label {...props} className="block text-sm font-medium text-gray-700 mb-1">{children}</label>;

interface UserFormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}

interface EditUserProps {
    user: UserFormData & { id: number; email_verified_at: string | null; created_at: string };
}

export default function Edit({ user }: EditUserProps) {
    const pageTitle = `Edit User: ${user.name}`;

    const { data, setData, errors, processing, put } = useForm<UserFormData>({
        name: user.name,
        email: user.email,
        password: '',
        password_confirmation: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        console.log("Final data structure being sent for Update:", data);

        put(update(user.id).url, {
            ...data,
            onSuccess: () => {
                router.visit(index().url);
            },
            onError: (errors: any) => {
                console.error("Update failed:", errors);
            }
        } as any);
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Users', href: index().url },
        { title: `Edit: ${user.name}`, href: '#' },
    ];

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
            <Head title={pageTitle} />
            <div className="container py-4 pl-4 max-w-3xl mx-auto">
                <div className="flex flex-col gap-6 w-full">
                    
                    {/* Header and Back Button */}
                    <div className="flex justify-between items-center mb-4">
                        <div className="flex items-center gap-3">
                            <div className="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                <UserCog className="h-6 w-6" />
                            </div>
                            <div>
                                <h2 className="text-2xl font-bold text-gray-800">{pageTitle}</h2>
                                <p className="text-sm text-gray-500">Update user information and credentials</p>
                            </div>
                        </div>
                        <Link 
                            href={index().url}
                            className="text-gray-600 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center shadow-sm"
                        >
                            <CornerUpLeft className="mr-2 h-4 w-4" />
                            Back to Users
                        </Link>
                    </div>

                    {/* User Info Card */}
                    <div className="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg">
                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <p className="text-xs text-gray-500 font-medium">Registered</p>
                                <p className="text-sm text-gray-800 font-semibold">{formatDate(user.created_at)}</p>
                            </div>
                            <div>
                                <p className="text-xs text-gray-500 font-medium">Email Status</p>
                                {user.email_verified_at ? (
                                    <p className="text-sm text-green-700 font-semibold">✓ Verified on {formatDate(user.email_verified_at)}</p>
                                ) : (
                                    <p className="text-sm text-yellow-700 font-semibold">⚠ Not Verified</p>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* FORM CARD */}
                    <form onSubmit={handleSubmit} className="p-6 border rounded-xl bg-white shadow-2xl space-y-6">
                        
                        {/* Basic Information */}
                        <div className="space-y-4">
                            <div className="border-b pb-3">
                                <h3 className="text-lg font-semibold text-indigo-700">Basic Information</h3>
                                <p className="text-sm text-gray-500">Update user's personal details and email</p>
                            </div>

                            {/* Name */}
                            <div>
                                <Label htmlFor="name">Full Name *</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(e: any) => setData('name', e.target.value)}
                                    className={errors.name ? 'border-red-500' : ''}
                                    placeholder="Enter full name"
                                />
                                {errors.name && <p className="text-xs text-red-500 mt-1">{errors.name}</p>}
                            </div>

                            {/* Email */}
                            <div>
                                <Label htmlFor="email">Email Address *</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={(e: any) => setData('email', e.target.value)}
                                    className={errors.email ? 'border-red-500' : ''}
                                    placeholder="user@example.com"
                                />
                                {errors.email && <p className="text-xs text-red-500 mt-1">{errors.email}</p>}
                            </div>
                        </div>

                        {/* Password Section */}
                        <div className="space-y-4">
                            <div className="border-b pb-3">
                                <h3 className="text-lg font-semibold text-indigo-700">Change Password</h3>
                                <p className="text-sm text-gray-500">Leave blank to keep current password</p>
                            </div>

                            {/* Info Alert */}
                            <div className="flex items-start gap-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                <AlertCircle className="h-5 w-5 text-amber-600 mt-0.5 flex-shrink-0" />
                                <div>
                                    <p className="text-sm text-amber-800 font-medium">Optional Password Change</p>
                                    <p className="text-xs text-amber-700 mt-1">Only fill these fields if you want to change the user's password. If left empty, the current password will remain unchanged.</p>
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {/* Password */}
                                <div>
                                    <Label htmlFor="password">New Password</Label>
                                    <Input
                                        id="password"
                                        type="password"
                                        value={data.password}
                                        onChange={(e: any) => setData('password', e.target.value)}
                                        className={errors.password ? 'border-red-500' : ''}
                                        placeholder="Enter new password"
                                    />
                                    {errors.password && <p className="text-xs text-red-500 mt-1">{errors.password}</p>}
                                </div>

                                {/* Confirm Password */}
                                <div>
                                    <Label htmlFor="password_confirmation">Confirm New Password</Label>
                                    <Input
                                        id="password_confirmation"
                                        type="password"
                                        value={data.password_confirmation}
                                        onChange={(e: any) => setData('password_confirmation', e.target.value)}
                                        className={errors.password_confirmation ? 'border-red-500' : ''}
                                        placeholder="Confirm new password"
                                    />
                                    {errors.password_confirmation && <p className="text-xs text-red-500 mt-1">{errors.password_confirmation}</p>}
                                </div>
                            </div>
                        </div>

                        {/* Form Submission */}
                        <div className="flex justify-end pt-4 border-t gap-3">
                            <Link 
                                href={index().url}
                                className="text-gray-700 border border-gray-300 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center"
                            >
                                Cancel
                            </Link>
                            <Button 
                                type="submit" 
                                disabled={processing} 
                                className="bg-blue-600 hover:bg-blue-700 transition duration-150 shadow-md hover:shadow-lg text-white disabled:opacity-50"
                            >
                                <Save className="mr-2 h-4 w-4" />
                                {processing ? 'Updating...' : 'Update User'}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}