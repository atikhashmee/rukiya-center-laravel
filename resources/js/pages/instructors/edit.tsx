import React from 'react';
import { Head, router, useForm } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import InputError from "@/components/input-error";
import PageHeader from "@/components/page-header";
import { ArrowLeft } from 'lucide-react';

interface Service {
    id: number;
    title: string;
    category: { id: number; name: string; slug: string } | null;
}

interface Instructor {
    id: number;
    name: string;
    title: string | null;
    email: string | null;
    phone: string | null;
    bio: string | null;
    languages: string[] | string | null;
    experience: string | null;
    location: string | null;
    appointment_type: string | null;
    is_active: boolean;
    services: { id: number }[];
}

interface Props {
    instructor: Instructor;
    services: Service[];
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Instructors', href: '/admin/instructors' },
    { title: 'Edit', href: '#' },
];

export default function EditInstructor({ instructor, services }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        name: instructor.name,
        title: instructor.title || '',
        email: instructor.email || '',
        phone: instructor.phone || '',
        bio: instructor.bio || '',
        languages: Array.isArray(instructor.languages) ? instructor.languages.join(', ') : (instructor.languages || ''),
        experience: instructor.experience || '',
        location: instructor.location || '',
        appointment_type: instructor.appointment_type || '',
        is_active: instructor.is_active,
        service_ids: instructor.services.map(s => s.id),
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/admin/instructors/${instructor.id}`);
    };

    const toggleService = (id: number) => {
        setData('service_ids', data.service_ids.includes(id)
            ? data.service_ids.filter(s => s !== id)
            : [...data.service_ids, id]);
    };

    const grouped = services.reduce((acc, s) => {
        const label = s.category?.name ?? 'Uncategorized';
        (acc[label] = acc[label] || []).push(s);
        return acc;
    }, {} as Record<string, Service[]>);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${instructor.name}`} />
            <div className="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title="Edit Instructor"
                    description={instructor.name}
                    actions={
                        <Button variant="outline" onClick={() => router.get('/admin/instructors')}>
                            <ArrowLeft className="h-4 w-4" /> Back to Instructors
                        </Button>
                    }
                />

                <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                    <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div className="border-b px-5 py-4">
                            <h2 className="font-semibold">Profile</h2>
                        </div>
                        <div className="grid gap-5 p-5">
                            <div className="grid gap-5 sm:grid-cols-2">
                                <div className="grid gap-2">
                                    <Label>Full Name *</Label>
                                    <Input value={data.name} onChange={e => setData('name', e.target.value)} />
                                    <InputError message={errors.name} />
                                </div>
                                <div className="grid gap-2">
                                    <Label>Title</Label>
                                    <Input value={data.title} onChange={e => setData('title', e.target.value)} placeholder="e.g. Senior Imam & Ruqyah Practitioner" />
                                    <InputError message={errors.title} />
                                </div>
                                <div className="grid gap-2">
                                    <Label>Email</Label>
                                    <Input type="email" value={data.email} onChange={e => setData('email', e.target.value)} />
                                    <InputError message={errors.email} />
                                </div>
                                <div className="grid gap-2">
                                    <Label>Phone</Label>
                                    <Input value={data.phone} onChange={e => setData('phone', e.target.value)} />
                                    <InputError message={errors.phone} />
                                </div>
                                <div className="flex items-center gap-2">
                                    <input type="checkbox" id="is_active" checked={data.is_active} onChange={e => setData('is_active', e.target.checked)} className="h-4 w-4 rounded accent-primary" />
                                    <Label htmlFor="is_active">Active</Label>
                                </div>
                            </div>

                            <div className="grid gap-2">
                                <Label>Bio</Label>
                                <Textarea value={data.bio} onChange={e => setData('bio', e.target.value)} rows={3} />
                                <InputError message={errors.bio} />
                            </div>
                            <div className="grid gap-2">
                                <Label>Languages (comma-separated)</Label>
                                <Input value={data.languages} onChange={e => setData('languages', e.target.value)} placeholder="English, Arabic, Bengali, Urdu" />
                                <InputError message={errors.languages} />
                            </div>
                            <div className="grid gap-2">
                                <div className="grid gap-5 md:grid-cols-3">
                                    <div className="grid gap-2">
                                        <Label>Experience</Label>
                                        <Input value={data.experience} onChange={e => setData('experience', e.target.value)} placeholder="30+ Years Experience" />
                                        <InputError message={errors.experience} />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label>Location</Label>
                                        <Input value={data.location} onChange={e => setData('location', e.target.value)} placeholder="UK Based" />
                                        <InputError message={errors.location} />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label>Appointment Type</Label>
                                        <Input value={data.appointment_type} onChange={e => setData('appointment_type', e.target.value)} placeholder="Online & In Person" />
                                        <InputError message={errors.appointment_type} />
                                    </div>
                                </div>
                                <p className="text-xs text-muted-foreground">These three show as small badges on the booking page. Leave any blank to hide that badge.</p>
                            </div>
                        </div>
                    </div>

                    <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div className="border-b px-5 py-4">
                            <h2 className="font-semibold">Assigned Services *</h2>
                        </div>
                        <div className="grid gap-5 p-5">
                            {Object.entries(grouped).map(([category, items]) => (
                                <div key={category}>
                                    <p className="mb-2 text-xs font-semibold uppercase tracking-wide text-muted-foreground">{category}</p>
                                    <div className="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                        {items.map(service => {
                                            const selected = data.service_ids.includes(service.id);
                                            return (
                                                <label key={service.id} className={`flex cursor-pointer items-center gap-2 rounded-lg border p-3 transition ${selected ? 'border-primary bg-primary/5 ring-1 ring-primary/30' : 'hover:bg-muted/50'}`}>
                                                    <input type="checkbox" checked={selected} onChange={() => toggleService(service.id)} className="h-4 w-4 rounded accent-primary" />
                                                    <span className="text-sm">{service.title}</span>
                                                </label>
                                            );
                                        })}
                                    </div>
                                </div>
                            ))}
                            <InputError message={errors.service_ids} />
                        </div>
                    </div>

                    <div className="flex justify-end gap-2">
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Saving...' : 'Update Instructor'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
