import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, useForm, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, update } from "@/actions/App/Http/Controllers/CustomerController";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import InputError from "@/components/input-error";
import PageHeader from "@/components/page-header";
import { ArrowLeft } from 'lucide-react';

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

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Customer: ${customer.name}`} />
            <div className="mx-auto flex w-full max-w-3xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={<>Editing Customer: <span className="text-muted-foreground">{customer.name}</span></>}
                    actions={
                        <Button variant="outline" asChild>
                            <Link href={index().url}>
                                <ArrowLeft className="h-4 w-4" />
                                Back to Customers
                            </Link>
                        </Button>
                    }
                />

                <form onSubmit={handleSubmit} className="rounded-xl border bg-card text-card-foreground shadow-sm">
                    <div className="border-b px-5 py-4">
                        <h2 className="font-semibold">Customer details</h2>
                    </div>
                    <div className="grid gap-5 p-5">
                        <div className="grid gap-5 sm:grid-cols-2">
                            <div className="grid gap-2">
                                <Label htmlFor="name">Full Name</Label>
                                <Input
                                    id="name"
                                    type="text"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="email">Email Address</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                />
                                <InputError message={errors.email} />
                            </div>
                        </div>

                        <div className="grid grid-cols-3 gap-5">
                            <div className="grid gap-2">
                                <Label htmlFor="phone_prefix">Prefix</Label>
                                <Input
                                    id="phone_prefix"
                                    type="text"
                                    value={data.phone_prefix || ''}
                                    onChange={(e) => setData('phone_prefix', e.target.value)}
                                    placeholder="+44"
                                />
                                <InputError message={errors.phone_prefix} />
                            </div>
                            <div className="col-span-2 grid gap-2">
                                <Label htmlFor="phone">Phone Number</Label>
                                <Input
                                    id="phone"
                                    type="text"
                                    value={data.phone || ''}
                                    onChange={(e) => setData('phone', e.target.value)}
                                />
                                <InputError message={errors.phone} />
                            </div>
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="about">About Customer</Label>
                            <Textarea
                                id="about"
                                rows={3}
                                value={data.about || ''}
                                onChange={(e) => setData('about', e.target.value)}
                            />
                            <InputError message={errors.about} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="interests">Interests (Comma-Separated)</Label>
                            <Input
                                id="interests"
                                type="text"
                                value={data.interests?.join(', ') || ''}
                                onChange={handleInterestChange}
                                placeholder="e.g., Hiking, Cooking, Tech"
                            />
                            <InputError message={errors.interests} />
                        </div>

                        <div className="grid gap-2">
                            <div className="flex items-center gap-2">
                                <input
                                    id="is_active"
                                    type="checkbox"
                                    checked={data.is_active}
                                    onChange={(e) => setData('is_active', e.target.checked)}
                                    className="h-4 w-4 rounded accent-primary"
                                />
                                <Label htmlFor="is_active">Customer is Active</Label>
                            </div>
                            <InputError message={errors.is_active} />
                        </div>
                    </div>

                    <div className="flex justify-end gap-2 border-t px-5 py-4">
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Updating...' : 'Save Changes'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
