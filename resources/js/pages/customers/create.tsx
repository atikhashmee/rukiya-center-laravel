import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, useForm, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, store } from "@/actions/App/Http/Controllers/CustomerController";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import InputError from "@/components/input-error";
import PageHeader from "@/components/page-header";
import { ArrowLeft } from 'lucide-react';

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

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Customer" />
            <div className="mx-auto flex w-full max-w-3xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title="Create New Customer"
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

                        <div className="grid gap-5 sm:grid-cols-2">
                            <div className="grid gap-2">
                                <Label htmlFor="password">Password</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                />
                                <InputError message={errors.password} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="password_confirmation">Confirm Password</Label>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                />
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
                    </div>

                    <div className="flex justify-end gap-2 border-t px-5 py-4">
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Creating...' : 'Create Customer'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
