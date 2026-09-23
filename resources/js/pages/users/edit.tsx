import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, useForm } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index, update } from "@/actions/App/Http/Controllers/UserController";
import { ArrowLeft, Save, AlertCircle, CalendarDays, MailCheck } from 'lucide-react';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from "@/components/input-error";
import PageHeader from '@/components/page-header';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';
import { badgeClasses, tones } from '@/lib/status';

const Link: React.FC<any> = ({ children, href, className, ...props }) => <a href={href} className={className} {...props}>{children}</a>;

interface UserFormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    role_id: string;
    instructor_id: string;
}

interface Option {
    id: number;
    name?: string;
    label?: string;
}

interface EditUserProps {
    user: {
        id: number;
        name: string;
        email: string;
        email_verified_at: string | null;
        created_at: string;
        role_id: number | null;
        instructor_id: number | null;
    };
    roles: Option[];
    instructors: Option[];
}

const Section: React.FC<{ title: string; description: string; children: React.ReactNode }> = ({ title, description, children }) => (
    <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
        <div className="border-b px-5 py-4">
            <h2 className="font-semibold">{title}</h2>
            <p className="mt-0.5 text-sm text-muted-foreground">{description}</p>
        </div>
        <div className="p-5">{children}</div>
    </div>
);

export default function Edit({ user, roles, instructors }: EditUserProps) {
    const pageTitle = `Edit User: ${user.name}`;

    const { data, setData, errors, processing, put } = useForm<UserFormData>({
        name: user.name,
        email: user.email,
        password: '',
        password_confirmation: '',
        role_id: user.role_id ? String(user.role_id) : '',
        instructor_id: user.instructor_id ? String(user.instructor_id) : '',
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
            <div className="mx-auto flex w-full max-w-3xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={pageTitle}
                    description="Update user information and credentials"
                    actions={
                        <Button variant="outline" asChild>
                            <Link href={index().url}>
                                <ArrowLeft className="h-4 w-4" /> Back to Users
                            </Link>
                        </Button>
                    }
                />

                {/* User Info Card */}
                <div className="grid gap-4 rounded-xl border bg-card p-5 text-card-foreground shadow-sm sm:grid-cols-2">
                    <div className="flex items-center gap-3">
                        <div className={`rounded-lg p-2 ${tones.info}`}>
                            <CalendarDays className="h-4 w-4" />
                        </div>
                        <div>
                            <p className="text-xs text-muted-foreground">Registered</p>
                            <p className="text-sm font-medium">{formatDate(user.created_at)}</p>
                        </div>
                    </div>
                    <div className="flex items-center gap-3">
                        <div className={`rounded-lg p-2 ${tones[user.email_verified_at ? 'success' : 'warning']}`}>
                            <MailCheck className="h-4 w-4" />
                        </div>
                        <div>
                            <p className="text-xs text-muted-foreground">Email Status</p>
                            {user.email_verified_at ? (
                                <p className="text-sm font-medium">Verified on {formatDate(user.email_verified_at)}</p>
                            ) : (
                                <span className={badgeClasses('warning')}>Not Verified</span>
                            )}
                        </div>
                    </div>
                </div>

                <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                    <Section title="Basic Information" description="Update user's personal details and email">
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

                    <Section title="Change Password" description="Leave blank to keep current password">
                        <div className="grid gap-5">
                            <div className="flex items-start gap-3 rounded-lg border border-amber-500/30 bg-amber-500/10 p-3 text-amber-700 dark:text-amber-300">
                                <AlertCircle className="mt-0.5 h-4 w-4 flex-shrink-0" />
                                <div>
                                    <p className="text-sm font-medium">Optional Password Change</p>
                                    <p className="mt-1 text-xs opacity-90">Only fill these fields if you want to change the user's password. If left empty, the current password will remain unchanged.</p>
                                </div>
                            </div>

                            <div className="grid gap-5 sm:grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="password">New Password</Label>
                                    <Input
                                        id="password"
                                        type="password"
                                        value={data.password}
                                        onChange={(e) => setData('password', e.target.value)}
                                        aria-invalid={!!errors.password}
                                        placeholder="Enter new password"
                                    />
                                    <InputError message={errors.password} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="password_confirmation">Confirm New Password</Label>
                                    <Input
                                        id="password_confirmation"
                                        type="password"
                                        value={data.password_confirmation}
                                        onChange={(e) => setData('password_confirmation', e.target.value)}
                                        aria-invalid={!!errors.password_confirmation}
                                        placeholder="Confirm new password"
                                    />
                                    <InputError message={errors.password_confirmation} />
                                </div>
                            </div>
                        </div>
                    </Section>

                    <div className="flex justify-end gap-2">
                        <Button variant="outline" asChild>
                            <Link href={index().url}>Cancel</Link>
                        </Button>
                        <Button type="submit" disabled={processing}>
                            <Save className="h-4 w-4" />
                            {processing ? 'Updating...' : 'Update User'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
