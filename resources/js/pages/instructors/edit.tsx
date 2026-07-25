import React from 'react';
import { Head, router, useForm } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { CornerUpLeft } from 'lucide-react';

interface Service {
    id: number;
    title: string;
    category: { id: number; name: string; slug: string } | null;
}

interface Instructor {
    id: number;
    name: string;
    email: string | null;
    phone: string | null;
    bio: string | null;
    languages: string[] | string | null;
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
        email: instructor.email || '',
        phone: instructor.phone || '',
        bio: instructor.bio || '',
        languages: Array.isArray(instructor.languages) ? instructor.languages.join(', ') : (instructor.languages || ''),
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
            <div className="container py-4 pl-4">
                <div className="max-w-4xl mx-auto">
                    <button onClick={() => router.get('/admin/instructors')}
                        className="flex items-center gap-2 text-sm text-gray-600 border border-gray-300 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 mb-6">
                        <CornerUpLeft className="h-4 w-4" /> Back to Instructors
                    </button>

                    <div className="bg-white border rounded-xl shadow-xl p-6 mb-6">
                        <h2 className="text-xl font-bold text-gray-800 mb-6">Edit Instructor</h2>

                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <Label className="text-sm font-medium text-gray-700">Full Name *</Label>
                                    <Input value={data.name} onChange={e => setData('name', e.target.value)} className="mt-1" />
                                    {errors.name && <p className="text-xs text-red-500 mt-1">{errors.name}</p>}
                                </div>
                                <div>
                                    <Label className="text-sm font-medium text-gray-700">Email</Label>
                                    <Input type="email" value={data.email} onChange={e => setData('email', e.target.value)} className="mt-1" />
                                    {errors.email && <p className="text-xs text-red-500 mt-1">{errors.email}</p>}
                                </div>
                                <div>
                                    <Label className="text-sm font-medium text-gray-700">Phone</Label>
                                    <Input value={data.phone} onChange={e => setData('phone', e.target.value)} className="mt-1" />
                                    {errors.phone && <p className="text-xs text-red-500 mt-1">{errors.phone}</p>}
                                </div>
                                <div className="flex items-center gap-2 pt-6">
                                    <input type="checkbox" id="is_active" checked={data.is_active} onChange={e => setData('is_active', e.target.checked)} className="h-4 w-4 rounded border-gray-300" />
                                    <Label htmlFor="is_active" className="text-sm font-medium text-gray-700">Active</Label>
                                </div>
                            </div>

                            <div>
                                <Label className="text-sm font-medium text-gray-700">Bio</Label>
                                <textarea value={data.bio} onChange={e => setData('bio', e.target.value)} rows={3} className="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                                {errors.bio && <p className="text-xs text-red-500 mt-1">{errors.bio}</p>}
                            </div>
                            <div>
                                <Label className="text-sm font-medium text-gray-700">Languages (comma-separated)</Label>
                                <Input value={data.languages} onChange={e => setData('languages', e.target.value)} className="mt-1" placeholder="English, Arabic, Bengali, Urdu" />
                                {errors.languages && <p className="text-xs text-red-500 mt-1">{errors.languages}</p>}
                            </div>

                            <div>
                                <Label className="text-sm font-medium text-gray-700 mb-3 block">Assigned Services *</Label>
                                {Object.entries(grouped).map(([category, items]) => (
                                    <div key={category} className="mb-4">
                                        <p className="text-xs font-semibold text-gray-500 uppercase mb-2">{category}</p>
                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            {items.map(service => (
                                                <label key={service.id} className={`flex items-center gap-2 p-3 border rounded-lg cursor-pointer transition ${data.service_ids.includes(service.id) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50'}`}>
                                                    <input type="checkbox" checked={data.service_ids.includes(service.id)} onChange={() => toggleService(service.id)} className="h-4 w-4 rounded border-gray-300" />
                                                    <span className="text-sm text-gray-700">{service.title}</span>
                                                </label>
                                            ))}
                                        </div>
                                    </div>
                                ))}
                                {errors.service_ids && <p className="text-xs text-red-500 mt-1">{errors.service_ids}</p>}
                            </div>

                            <div className="flex justify-end pt-4 border-t">
                                <Button type="submit" disabled={processing} className="bg-blue-600 hover:bg-blue-700 text-white">
                                    {processing ? 'Saving...' : 'Update Instructor'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
