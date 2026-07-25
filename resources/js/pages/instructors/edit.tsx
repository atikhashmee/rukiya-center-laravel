import React from 'react';
import { Head, router, useForm } from '@inertiajs/react';
import AppLayout from "@/layouts/app-layout";
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { CornerUpLeft, Trash2, PlusCircle } from 'lucide-react';

interface Service {
    id: number;
    title: string;
    category: string;
}

interface Schedule {
    id: number;
    day_of_week: number;
    start_time: string;
    end_time: string;
    is_active: boolean;
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
    schedules: Schedule[];
}

interface Props {
    instructor: Instructor;
    services: Service[];
}

const DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

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

    const [scheduleData, setScheduleData] = React.useState({
        day_of_week: 1,
        start_time: '09:00',
        end_time: '17:00',
    });
    const [addingSchedule, setAddingSchedule] = React.useState(false);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/admin/instructors/${instructor.id}`);
    };

    const toggleService = (id: number) => {
        setData('service_ids', data.service_ids.includes(id)
            ? data.service_ids.filter(s => s !== id)
            : [...data.service_ids, id]);
    };

    const handleAddSchedule = (e: React.FormEvent) => {
        e.preventDefault();
        setAddingSchedule(true);
        router.post(`/admin/instructors/${instructor.id}/schedules`, scheduleData, {
            onFinish: () => setAddingSchedule(false),
        });
    };

    const handleDeleteSchedule = (scheduleId: number) => {
        if (confirm('Remove this schedule?')) {
            router.delete(`/admin/instructors/${instructor.id}/schedules/${scheduleId}`);
        }
    };

    const grouped = services.reduce((acc, s) => {
        (acc[s.category] = acc[s.category] || []).push(s);
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

                    {/* Schedule Management */}
                    <div className="bg-white border rounded-xl shadow-xl p-6">
                        <h2 className="text-xl font-bold text-gray-800 mb-6">Weekly Availability</h2>

                        {instructor.schedules.length > 0 ? (
                            <div className="space-y-3 mb-6">
                                {instructor.schedules.map(schedule => (
                                    <div key={schedule.id} className="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                        <div className="flex items-center gap-4">
                                            <span className="font-medium text-sm text-gray-800">{DAYS[schedule.day_of_week]}</span>
                                            <span className="text-sm text-gray-500">{schedule.start_time} — {schedule.end_time}</span>
                                        </div>
                                        <Button variant="destructive" size="icon" className="h-8 w-8" onClick={() => handleDeleteSchedule(schedule.id)}>
                                            <Trash2 className="h-4 w-4" />
                                        </Button>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="text-sm text-gray-400 mb-6">No schedules set. Add availability below.</p>
                        )}

                        <form onSubmit={handleAddSchedule} className="flex flex-wrap items-end gap-4 p-4 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                            <div>
                                <Label className="text-xs font-medium text-gray-600">Day</Label>
                                <select value={scheduleData.day_of_week} onChange={e => setScheduleData({ ...scheduleData, day_of_week: Number(e.target.value) })}
                                    className="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    {DAYS.map((day, i) => (
                                        <option key={i} value={i}>{day}</option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <Label className="text-xs font-medium text-gray-600">Start Time</Label>
                                <Input type="time" value={scheduleData.start_time} onChange={e => setScheduleData({ ...scheduleData, start_time: e.target.value })} className="mt-1" />
                            </div>
                            <div>
                                <Label className="text-xs font-medium text-gray-600">End Time</Label>
                                <Input type="time" value={scheduleData.end_time} onChange={e => setScheduleData({ ...scheduleData, end_time: e.target.value })} className="mt-1" />
                            </div>
                            <Button type="submit" disabled={addingSchedule} className="bg-blue-600 hover:bg-blue-700 text-white">
                                <PlusCircle className="mr-2 h-4 w-4" /> {addingSchedule ? 'Adding...' : 'Add'}
                            </Button>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
