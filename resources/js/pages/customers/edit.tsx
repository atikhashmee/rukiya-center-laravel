import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, useForm, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, update } from "@/actions/App/Http/Controllers/CustomerController";
import { Button } from "@/components/ui/button";
import { CornerUpLeft } from 'lucide-react';

// --- Interfaces (Kept for clarity) ---

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

interface CustomerEditProps {
    customer: Customer; 
    errors: { [key: string]: string };
}

export default function Edit({ customer, errors }: CustomerEditProps) {
    const { data, setData, patch, processing } = useForm({
        name: customer.name,
        email: customer.email,
        phone_prefix: customer.phone_prefix || '',
        phone: customer.phone || '',
        interests: customer.interests || [],
        about: customer.about || '',
        is_active: customer.is_active,
    });

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Customers', href: index().url },
        { title: `Edit: ${customer.name}`, href: update(customer.id).url }
    ];

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        patch(update(customer.id).url, {
            preserveScroll: true,
        });
    };
    
    const handleInterestChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const interestArray = e.target.value.split(',').map(s => s.trim()).filter(s => s.length > 0);
        setData('interests', interestArray);
    };

    const INPUT_CLASSES = "mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 **p-2**";

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Customer: ${customer.name}`} />
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full ">
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
                            Editing Customer: <span className="text-gray-900">{customer.name}</span>
                        </h2>
                        
                        <form onSubmit={handleSubmit} className="space-y-6">
                            
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {/* Name */}
                                <div>
                                    <label htmlFor="name" className="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input
                                        id="name"
                                        type="text"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        className={INPUT_CLASSES} // <- APPLIED PADDING
                                    />
                                    {errors.name && <p className="mt-2 text-sm text-red-600">{errors.name}</p>}
                                </div>

                                {/* Email */}
                                <div>
                                    <label htmlFor="email" className="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input
                                        id="email"
                                        type="email"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        className={INPUT_CLASSES} // <- APPLIED PADDING
                                    />
                                    {errors.email && <p className="mt-2 text-sm text-red-600">{errors.email}</p>}
                                </div>
                            </div>

                            <div className="grid grid-cols-3 gap-6">
                                {/* Phone Prefix */}
                                <div>
                                    <label htmlFor="phone_prefix" className="block text-sm font-medium text-gray-700">Prefix</label>
                                    <input
                                        id="phone_prefix"
                                        type="text"
                                        value={data.phone_prefix || ''}
                                        onChange={(e) => setData('phone_prefix', e.target.value)}
                                        className={INPUT_CLASSES} // <- APPLIED PADDING
                                        placeholder="+44"
                                    />
                                    {errors.phone_prefix && <p className="mt-2 text-sm text-red-600">{errors.phone_prefix}</p>}
                                </div>
                                {/* Phone */}
                                <div className="col-span-2">
                                    <label htmlFor="phone" className="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input
                                        id="phone"
                                        type="text"
                                        value={data.phone || ''}
                                        onChange={(e) => setData('phone', e.target.value)}
                                        className={INPUT_CLASSES} // <- APPLIED PADDING
                                    />
                                    {errors.phone && <p className="mt-2 text-sm text-red-600">{errors.phone}</p>}
                                </div>
                            </div>
                            
                            {/* About */}
                            <div>
                                <label htmlFor="about" className="block text-sm font-medium text-gray-700">About Customer</label>
                                <textarea
                                    id="about"
                                    rows={3}
                                    value={data.about || ''}
                                    onChange={(e) => setData('about', e.target.value)}
                                    className={INPUT_CLASSES} // <- APPLIED PADDING
                                />
                                {errors.about && <p className="mt-2 text-sm text-red-600">{errors.about}</p>}
                            </div>

                            {/* Interests (Using a simple comma-separated input) */}
                            <div>
                                <label htmlFor="interests" className="block text-sm font-medium text-gray-700">Interests (Comma-Separated)</label>
                                <input
                                    id="interests"
                                    type="text"
                                    value={data.interests?.join(', ') || ''}
                                    onChange={handleInterestChange}
                                    className={INPUT_CLASSES} // <- APPLIED PADDING
                                    placeholder="e.g., Hiking, Cooking, Tech"
                                />
                                {errors.interests && <p className="mt-2 text-sm text-red-600">{errors.interests}</p>}
                            </div>

                            {/* Is Active Toggle */}
                            <div className="flex items-center pt-2">
                                <input
                                    id="is_active"
                                    type="checkbox"
                                    checked={data.is_active}
                                    onChange={(e) => setData('is_active', e.target.checked)}
                                    className="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                />
                                <label htmlFor="is_active" className="ml-2 block text-sm font-medium text-gray-700">
                                    Customer is Active
                                </label>
                                {errors.is_active && <p className="mt-2 text-sm text-red-600">{errors.is_active}</p>}
                            </div>

                            <div className="flex justify-end pt-4 border-t border-gray-200">
                                <Button type="submit" disabled={processing} className="bg-indigo-600 hover:bg-indigo-700">
                                    {processing ? 'Updating...' : 'Save Changes'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}