import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, useForm } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { store, index } from "@/actions/App/Http/Controllers/UserController";
import { CornerUpLeft, Save, UserPlus } from 'lucide-react';

const Link: React.FC<any> = ({ children, href, className, ...props }) => <a href={href} className={className} {...props}>{children}</a>;
const Button: React.FC<any> = ({ children, className = '', ...props }) => <button {...props} className={`px-4 py-2 rounded-lg font-medium transition-colors ${className}`}>{children}</button>;
const Input: React.FC<any> = (props) => <input {...props} className="border border-gray-300 p-2 rounded-lg w-full focus:ring-indigo-500 focus:border-indigo-500" />;
const Label: React.FC<any> = ({ children, ...props }) => <label {...props} className="block text-sm font-medium text-gray-700 mb-1">{children}</label>;
const Checkbox: React.FC<any> = (props) => <input type="checkbox" {...props} className="rounded text-indigo-600 h-4 w-4 border-gray-300 focus:ring-indigo-500" />;

interface UserFormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    email_verified_at: string | null;
}

const initialData: UserFormData = {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    email_verified_at: null,
};

export default function Create() {
    const pageTitle = 'Create New User';

    const { data, setData, errors, processing, post } = useForm<UserFormData>(initialData);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        const submitData = {
            ...data,
            email_verified_at: data.email_verified_at ? new Date().toISOString() : null,
        };

        console.log("Final data structure being sent for Creation:", submitData);

        post(store().url, {
            ...submitData,
            onSuccess: () => {
                router.visit(index().url);
            },
        } as any);
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Users', href: index().url },
        { title: 'Create user', href: '#' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={pageTitle} />
            <div className="container py-4 pl-4 max-w-3xl mx-auto">
                <div className="flex flex-col gap-6 w-full">
                    
                    {/* Header and Back Button */}
                    <div className="flex justify-between items-center mb-4">
                        <div className="flex items-center gap-3">
                            <div className="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg">
                                <UserPlus className="h-6 w-6" />
                            </div>
                            <div>
                                <h2 className="text-2xl font-bold text-gray-800">{pageTitle}</h2>
                                <p className="text-sm text-gray-500">Add a new admin user to the system</p>
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

                    {/* FORM CARD */}
                    <form onSubmit={handleSubmit} className="p-6 border rounded-xl bg-white shadow-2xl space-y-6">
                        
                        {/* Basic Information */}
                        <div className="space-y-4">
                            <div className="border-b pb-3">
                                <h3 className="text-lg font-semibold text-indigo-700">Basic Information</h3>
                                <p className="text-sm text-gray-500">User's personal details and login credentials</p>
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
                                <h3 className="text-lg font-semibold text-indigo-700">Security</h3>
                                <p className="text-sm text-gray-500">Set a strong password (minimum 8 characters)</p>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {/* Password */}
                                <div>
                                    <Label htmlFor="password">Password *</Label>
                                    <Input
                                        id="password"
                                        type="password"
                                        value={data.password}
                                        onChange={(e: any) => setData('password', e.target.value)}
                                        className={errors.password ? 'border-red-500' : ''}
                                        placeholder="Enter password"
                                    />
                                    {errors.password && <p className="text-xs text-red-500 mt-1">{errors.password}</p>}
                                </div>

                                {/* Confirm Password */}
                                <div>
                                    <Label htmlFor="password_confirmation">Confirm Password *</Label>
                                    <Input
                                        id="password_confirmation"
                                        type="password"
                                        value={data.password_confirmation}
                                        onChange={(e: any) => setData('password_confirmation', e.target.value)}
                                        className={errors.password_confirmation ? 'border-red-500' : ''}
                                        placeholder="Confirm password"
                                    />
                                    {errors.password_confirmation && <p className="text-xs text-red-500 mt-1">{errors.password_confirmation}</p>}
                                </div>
                            </div>
                        </div>

                        {/* Email Verification */}
                        <div className="space-y-4">
                            <div className="border-b pb-3">
                                <h3 className="text-lg font-semibold text-indigo-700">Email Verification</h3>
                                <p className="text-sm text-gray-500">Set the email verification status</p>
                            </div>

                            <div className="flex items-center space-x-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <Checkbox
                                    id="email_verified_at"
                                    checked={!!data.email_verified_at}
                                    onChange={(e: any) => setData('email_verified_at', e.target.checked ? new Date().toISOString() : null)}
                                />
                                <label
                                    htmlFor="email_verified_at"
                                    className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 cursor-pointer"
                                >
                                    Mark email as verified immediately
                                </label>
                            </div>
                            <p className="text-xs text-gray-500">If unchecked, the user will need to verify their email address via email link.</p>
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
                                className="bg-green-600 hover:bg-green-700 transition duration-150 shadow-md hover:shadow-lg text-white disabled:opacity-50"
                            >
                                <Save className="mr-2 h-4 w-4" />
                                {processing ? 'Creating...' : 'Create User'}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}