import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, useForm, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, store } from "@/actions/App/Http/Controllers/CustomerController";
import { Button } from "@/components/ui/button";
import { CornerUpLeft } from 'lucide-react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        phone_prefix: '',
        phone: '',
        interests: [] as string[],
        about: '',
        password: '',
        password_confirmation: '',
    });

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Customers', href: index().url },
        { title: 'Create', href: '#' },
    ];

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(store().url, {
            preserveScroll: true,
        });
    };

    const handleInterestChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const interestArray = e.target.value.split(',').map(s => s.trim()).filter(s => s.length > 0);
        setData('interests', interestArray);
    };

    const INPUT_CLASSES = "mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2";

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Customer" />
            <div className="container py-4 pl-4 max-w-4xl mx-auto">
                <div className="flex flex-col gap-6 w-full">
                    <div className="flex justify-between items-center mb-4">
                        <Link
                            href={index().url}
                            className="text-gray-600 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center shadow-sm"
                        >
                            <CornerUpLeft className="mr-2 h-4 w-4" />
                            Back to Customers
                        </Link>
                    </div>

                    <div className="p-6 border rounded-xl bg-white shadow-xl max-w-3xl mx-auto w-full">
                        <h2 className="text-2xl font-bold mb-6 text-indigo-700">
                            Create New Customer
                        </h2>

                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label htmlFor="name" className="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input
                                        id="name"
                                        type="text"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                    {errors.name && <p className="mt-1 text-xs text-red-500">{errors.name}</p>}
                                </div>

                                <div>
                                    <label htmlFor="email" className="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input
                                        id="email"
                                        type="email"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                    {errors.email && <p className="mt-1 text-xs text-red-500">{errors.email}</p>}
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label htmlFor="password" className="block text-sm font-medium text-gray-700">Password</label>
                                    <input
                                        id="password"
                                        type="password"
                                        value={data.password}
                                        onChange={(e) => setData('password', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                    {errors.password && <p className="mt-1 text-xs text-red-500">{errors.password}</p>}
                                </div>

                                <div>
                                    <label htmlFor="password_confirmation" className="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input
                                        id="password_confirmation"
                                        type="password"
                                        value={data.password_confirmation}
                                        onChange={(e) => setData('password_confirmation', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                </div>
                            </div>

                            <div className="grid grid-cols-3 gap-6">
                                <div>
                                    <label htmlFor="phone_prefix" className="block text-sm font-medium text-gray-700">Prefix</label>
                                    <input
                                        id="phone_prefix"
                                        type="text"
                                        value={data.phone_prefix || ''}
                                        onChange={(e) => setData('phone_prefix', e.target.value)}
                                        className={INPUT_CLASSES}
                                        placeholder="+44"
                                    />
                                    {errors.phone_prefix && <p className="mt-1 text-xs text-red-500">{errors.phone_prefix}</p>}
                                </div>
                                <div className="col-span-2">
                                    <label htmlFor="phone" className="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input
                                        id="phone"
                                        type="text"
                                        value={data.phone || ''}
                                        onChange={(e) => setData('phone', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                    {errors.phone && <p className="mt-1 text-xs text-red-500">{errors.phone}</p>}
                                </div>
                            </div>

                            <div>
                                <label htmlFor="about" className="block text-sm font-medium text-gray-700">About Customer</label>
                                <textarea
                                    id="about"
                                    rows={3}
                                    value={data.about || ''}
                                    onChange={(e) => setData('about', e.target.value)}
                                    className={INPUT_CLASSES}
                                />
                                {errors.about && <p className="mt-1 text-xs text-red-500">{errors.about}</p>}
                            </div>

                            <div>
                                <label htmlFor="interests" className="block text-sm font-medium text-gray-700">Interests (Comma-Separated)</label>
                                <input
                                    id="interests"
                                    type="text"
                                    value={data.interests?.join(', ') || ''}
                                    onChange={handleInterestChange}
                                    className={INPUT_CLASSES}
                                    placeholder="e.g., Hiking, Cooking, Tech"
                                />
                                {errors.interests && <p className="mt-1 text-xs text-red-500">{errors.interests}</p>}
                            </div>

                            <div className="flex justify-end pt-4 border-t border-gray-200">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm disabled:opacity-50"
                                >
                                    {processing ? 'Creating...' : 'Create Customer'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
