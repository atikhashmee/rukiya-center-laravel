import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, useForm } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { store, index } from "@/actions/App/Http/Controllers/UserController";
import { ArrowLeft, Save } from 'lucide-react';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from "@/components/input-error";
import PageHeader from '@/components/page-header';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';

const Link: React.FC<any> = ({ children, href, className, ...props }) => <a href={href} className={className} {...props}>{children}</a>;

interface UserFormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    email_verified_at: string | null;
    role_id: string;
    instructor_id: string;
}

interface Option {
    id: number;
    name?: string;
    label?: string;
}

interface CreateUserProps {
    roles: Option[];
    instructors: Option[];
}

const initialData: UserFormData = {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    email_verified_at: null,
    role_id: '',
    instructor_id: '',
};

const Section: React.FC<{ title: string; description: string; children: React.ReactNode }> = ({ title, description, children }) => (
    <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
        <div className="border-b px-5 py-4">
            <h2 className="font-semibold">{title}</h2>
            <p className="mt-0.5 text-sm text-muted-foreground">{description}</p>
        </div>
        <div className="p-5">{children}</div>
    </div>
);

export default function Create({ roles, instructors }: CreateUserProps) {
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
            <div className="mx-auto flex w-full max-w-3xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={pageTitle}
                    description="Add a new admin user to the system"
                    actions={
                        <Button variant="outline" asChild>
                            <Link href={index().url}>
                                <ArrowLeft className="h-4 w-4" /> Back to Users
                            </Link>
                        </Button>
                    }
                />

                <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                    <Section title="Basic Information" description="User's personal details and login credentials">
                        <div className="grid gap-5">
                            <div className="grid gap-2">
                                <Label htmlFor="name">Full Name *</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    aria-invalid={!!errors.name}
                                    placeholder="Enter full name"
                                />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="email">Email Address *</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    aria-invalid={!!errors.email}
                                    placeholder="user@example.com"
                                />
                                <InputError message={errors.email} />
                            </div>
                        </div>
                    </Section>

                    <Section title="Access" description="Which role this account gets, and whether it belongs to an instructor">
                        <div className="grid gap-5 sm:grid-cols-2">
                            <div className="grid gap-2">
                                <Label htmlFor="role_id">Role *</Label>
                                <NativeSelect
                                    id="role_id"
                                    className="w-full"
                                    value={data.role_id}
                                    onChange={(e) => setData('role_id', e.target.value)}
                                    aria-invalid={!!errors.role_id}
                                >
                                    <NativeSelectOption value="">Select a role</NativeSelectOption>
                                    {roles.map((role) => (
                                        <NativeSelectOption key={role.id} value={String(role.id)}>{role.label}</NativeSelectOption>
                                    ))}
                                </NativeSelect>
                                <InputError message={errors.role_id} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="instructor_id">Linked instructor</Label>
                                <NativeSelect
                                    id="instructor_id"
                                    className="w-full"
                                    value={data.instructor_id}
                                    onChange={(e) => setData('instructor_id', e.target.value)}
                                    aria-invalid={!!errors.instructor_id}
                                >
                                    <NativeSelectOption value="">Not an instructor</NativeSelectOption>
                                    {instructors.map((instructor) => (
                                        <NativeSelectOption key={instructor.id} value={String(instructor.id)}>{instructor.name}</NativeSelectOption>
                                    ))}
                                </NativeSelect>
                                <InputError message={errors.instructor_id} />
                                <p className="text-xs text-muted-foreground">Linking an instructor limits this account to that instructor's own bookings.</p>
                            </div>
                        </div>
                    </Section>

                    <Section title="Security" description="Set a strong password (minimum 8 characters)">
                        <div className="grid gap-5 sm:grid-cols-2">
                            <div className="grid gap-2">
                                <Label htmlFor="password">Password *</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    aria-invalid={!!errors.password}
                                    placeholder="Enter password"
                                />
                                <InputError message={errors.password} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="password_confirmation">Confirm Password *</Label>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    aria-invalid={!!errors.password_confirmation}
                                    placeholder="Confirm password"
                                />
                                <InputError message={errors.password_confirmation} />
                            </div>
                        </div>
                    </Section>

                    <Section title="Email Verification" description="Set the email verification status">
                        <label
                            htmlFor="email_verified_at"
                            className={`flex cursor-pointer items-center gap-3 rounded-lg border p-4 transition-colors ${data.email_verified_at ? 'border-primary bg-primary/5 ring-1 ring-primary/30' : 'hover:bg-muted/50'}`}
                        >
                            <input
                                type="checkbox"
                                id="email_verified_at"
                                className="h-4 w-4 rounded accent-primary"
                                checked={!!data.email_verified_at}
                                onChange={(e) => setData('email_verified_at', e.target.checked ? new Date().toISOString() : null)}
                            />
                            <span className="text-sm font-medium">Mark email as verified immediately</span>
                        </label>
                        <p className="mt-3 text-xs text-muted-foreground">If unchecked, the user will need to verify their email address via email link.</p>
                    </Section>

                    <div className="flex justify-end gap-2">
                        <Button variant="outline" asChild>
                            <Link href={index().url}>Cancel</Link>
                        </Button>
                        <Button type="submit" disabled={processing}>
                            <Save className="h-4 w-4" />
                            {processing ? 'Creating...' : 'Create User'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
