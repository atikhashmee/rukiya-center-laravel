import React, { useEffect, useState, type ReactNode } from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, useForm } from '@inertiajs/react';
import { BreadcrumbItem} from "@/types";
import { dashboard } from '@/routes';
import { index, update } from "@/actions/App/Http/Controllers/ServiceController";
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';
import InputError from '@/components/input-error';
import PageHeader from '@/components/page-header';
import { ArrowLeft, Save, Trash2, Plus, CalendarClock } from 'lucide-react';

const DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

type PriceType = 'FREE' | 'DONATION' | 'FIXED' | 'RESERVATION';
type AppointmentType = 'online' | 'in_person' | 'both';

interface ServiceOptionFormData {
    id_code: string;
    category_id: number | '';
    title: string;
    tagline: string;
    description: string;
    icon: string;
    card_color: string;
    features: string[];
    order: number;
    price_type: PriceType;
    price_value: number | null;
    min_donation: number | null;
    requires_custom_assessment: boolean;
    appointment_type: AppointmentType;
    required_form_fields: string[];
    submit_button_text: string;
}

interface Schedule {
    id: number;
    day_of_week: number;
    start_time: string;
    end_time: string;
    is_active: boolean;
}

interface EditServiceOptionProps {
    service: ServiceOptionFormData & { id: number; schedules: Schedule[] };
    serviceCategories: { id: number; name: string; slug: string }[];
}

function Section({ title, description, children }: { title: string; description: string; children: ReactNode }) {
    return (
        <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
            <div className="border-b px-5 py-4">
                <h2 className="font-semibold">{title}</h2>
                <p className="mt-0.5 text-sm text-muted-foreground">{description}</p>
            </div>
            <div className="grid gap-5 p-5 sm:grid-cols-2">{children}</div>
        </div>
    );
}

export default function Edit({ service, serviceCategories = [] }: EditServiceOptionProps) {

    const pageTitle = `Edit Service: ${service.title}`;

    const [scheduleData, setScheduleData] = useState({
        day_of_week: 1,
        start_time: '09:00',
        end_time: '17:00',
    });
    const [addingSchedule, setAddingSchedule] = useState(false);

    const handleAddSchedule = (e: React.FormEvent) => {
        e.preventDefault();
        setAddingSchedule(true);
        router.post(`/admin/services/${service.id}/schedules`, scheduleData, {
            onFinish: () => setAddingSchedule(false),
        });
    };

    const handleDeleteSchedule = (scheduleId: number) => {
        if (confirm('Remove this schedule?')) {
            router.delete(`/admin/services/${service.id}/schedules/${scheduleId}`);
        }
    };

    // Convert arrays to comma-separated strings for the form
    const initialFormData = {
        ...service,
        features: Array.isArray(service.features) ? service.features.join(', ') : service.features,
        required_form_fields: Array.isArray(service.required_form_fields)
            ? service.required_form_fields.join(', ')
            : service.required_form_fields
    };

    const { data, setData, errors, processing, put } = useForm<any>(initialFormData);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // Convert comma-separated strings back to arrays
        const processedFeatures = typeof data.features === 'string'
            ? data.features.split(',').map((f: string) => f.trim()).filter((f: string) => f !== '')
            : Array.isArray(data.features) ? data.features : [];

        const processedFormFields = typeof data.required_form_fields === 'string'
            ? data.required_form_fields.split(',').map((f: string) => f.trim()).filter((f: string) => f !== '')
            : Array.isArray(data.required_form_fields) ? data.required_form_fields : [];

        const submitData = {
            ...data,
            features: processedFeatures,
            required_form_fields: processedFormFields,
            price_value: data.price_type === 'FIXED' ? data.price_value : null,
            min_donation: data.price_type === 'DONATION' ? data.min_donation : null,
        };

        console.log("Final data structure being sent for Update:", submitData);

        put(update(service.id).url, {
            data: submitData,
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
        { title: 'Services', href: index().url },
        { title: `Edit: ${service.title}`, href: '#' },
    ];

    // Cleanup Effect for Price Fields on Type Change
    useEffect(() => {
        if (data.price_type === 'FREE' || data.price_type === 'RESERVATION') {
            setData({ ...data, price_value: null, min_donation: null });
        }
        if (data.price_type === 'FIXED' && data.price_value === null) {
            setData('price_value', 50.00);
        }
        if (data.price_type === 'DONATION' && data.min_donation === null) {
            setData('min_donation', 10.00);
        }
    }, [data.price_type]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={pageTitle} />
            <div className="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={pageTitle}
                    actions={
                        <Button variant="outline" asChild>
                            <a href={index().url}>
                                <ArrowLeft className="h-4 w-4" /> Back to Services
                            </a>
                        </Button>
                    }
                />

                <form onSubmit={handleSubmit} className="flex flex-col gap-6">

                    {/* 1. Core Identification and Ordering */}
                    <Section title="Core Identity" description="Unique code, category, and display order.">
                        <div className="grid gap-2">
                            <Label htmlFor="id_code">ID Code (Unique)</Label>
                            <Input
                                id="id_code"
                                value={data.id_code}
                                onChange={(e) => setData('id_code', e.target.value.toUpperCase().replace(/\s/g, '_'))}
                                aria-invalid={!!errors.id_code}
                                className="font-mono"
                                placeholder="E.g., ISTEKHARA_DEEP"
                            />
                            <InputError message={errors.id_code} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="order">Order</Label>
                            <Input
                                id="order"
                                type="number"
                                value={data.order}
                                onChange={(e) => setData('order', parseInt(e.target.value) || 1)}
                                aria-invalid={!!errors.order}
                            />
                            <InputError message={errors.order} />
                        </div>

                        <div className="grid gap-2 sm:col-span-2">
                            <Label htmlFor="category_id">Category</Label>
                            <NativeSelect
                                id="category_id"
                                className="w-full"
                                value={data.category_id}
                                onChange={(e) => setData('category_id', e.target.value ? Number(e.target.value) : '')}
                                aria-invalid={!!errors.category_id}
                            >
                                <NativeSelectOption disabled value="">Select Category</NativeSelectOption>
                                {serviceCategories.map((cat) => (
                                    <NativeSelectOption key={cat.id} value={cat.id}>{cat.name}</NativeSelectOption>
                                ))}
                            </NativeSelect>
                            <InputError message={errors.category_id} />
                        </div>
                    </Section>

                    {/* 2. Content & Presentation */}
                    <Section title="Content & Design" description="Titles, descriptions, icons, and visual styling.">
                        <div className="grid gap-2 sm:col-span-2">
                            <Label htmlFor="title">Title</Label>
                            <Input
                                id="title"
                                value={data.title}
                                onChange={(e) => setData('title', e.target.value)}
                                aria-invalid={!!errors.title}
                                placeholder="E.g., Full Istekhara Assessment"
                            />
                            <InputError message={errors.title} />
                        </div>

                        <div className="grid gap-2 sm:col-span-2">
                            <Label htmlFor="tagline">Tagline</Label>
                            <Input
                                id="tagline"
                                value={data.tagline}
                                onChange={(e) => setData('tagline', e.target.value)}
                                aria-invalid={!!errors.tagline}
                                placeholder="Brief, punchy description."
                            />
                            <InputError message={errors.tagline} />
                        </div>

                        <div className="grid gap-2 sm:col-span-2">
                            <Label htmlFor="description">Full Description</Label>
                            <Textarea
                                id="description"
                                value={data.description}
                                onChange={(e) => setData('description', e.target.value)}
                                aria-invalid={!!errors.description}
                                placeholder="Detailed explanation of the service option."
                            />
                            <InputError message={errors.description} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="icon">Lucide Icon Name</Label>
                            <Input
                                id="icon"
                                value={data.icon}
                                onChange={(e) => setData('icon', e.target.value)}
                                aria-invalid={!!errors.icon}
                                placeholder="E.g., Zap, Heart, Sunrise"
                            />
                            <InputError message={errors.icon} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="card_color">Card Highlight Color (Tailwind Class)</Label>
                            <Input
                                id="card_color"
                                value={data.card_color}
                                onChange={(e) => setData('card_color', e.target.value)}
                                aria-invalid={!!errors.card_color}
                                className="font-mono"
                                placeholder="E.g., border-l-red-500"
                            />
                            <InputError message={errors.card_color} />
                        </div>

                        <div className="grid gap-2 sm:col-span-2">
                            <Label htmlFor="features">Features (comma separated)</Label>
                            <Textarea
                                id="features"
                                value={data.features}
                                onChange={(e) => setData('features', e.target.value)}
                                aria-invalid={!!errors.features}
                                className="font-mono"
                                placeholder='Feature 1, Feature 2, Feature 3'
                                rows={3}
                            />
                            <InputError message={errors.features} />
                        </div>
                    </Section>

                    {/* 3. Booking and Payment Logic */}
                    <Section title="Pricing & Logic" description="Payment type, values, and assessment requirement.">
                        <div className="grid gap-2">
                            <Label htmlFor="price_type">Price Type</Label>
                            <NativeSelect
                                id="price_type"
                                className="w-full"
                                value={data.price_type}
                                onChange={(e) => setData('price_type', e.target.value as PriceType)}
                                aria-invalid={!!errors.price_type}
                            >
                                <NativeSelectOption disabled value="">Select Price Type</NativeSelectOption>
                                <NativeSelectOption value="FIXED">FIXED (Set Price)</NativeSelectOption>
                                <NativeSelectOption value="DONATION">DONATION (Minimum Contribution)</NativeSelectOption>
                                <NativeSelectOption value="FREE">FREE</NativeSelectOption>
                                <NativeSelectOption value="RESERVATION">RESERVATION (Assessment Required)</NativeSelectOption>
                            </NativeSelect>
                            <InputError message={errors.price_type} />
                        </div>

                        {/* Dynamic Price Inputs */}
                        {data.price_type === 'FIXED' && (
                            <div className="grid gap-2">
                                <Label htmlFor="price_value">Fixed Price (£)</Label>
                                <Input
                                    id="price_value"
                                    type="number"
                                    step="0.01"
                                    value={data.price_value || ''}
                                    onChange={(e) => setData('price_value', parseFloat(e.target.value))}
                                    aria-invalid={!!errors.price_value}
                                    placeholder="e.g., 120.00"
                                />
                                <InputError message={errors.price_value} />
                            </div>
                        )}

                        {data.price_type === 'DONATION' && (
                            <div className="grid gap-2">
                                <Label htmlFor="min_donation">Minimum Donation (£)</Label>
                                <Input
                                    id="min_donation"
                                    type="number"
                                    step="0.01"
                                    value={data.min_donation || ''}
                                    onChange={(e) => setData('min_donation', parseFloat(e.target.value))}
                                    aria-invalid={!!errors.min_donation}
                                    placeholder="e.g., 10.00"
                                />
                                <InputError message={errors.min_donation} />
                            </div>
                        )}

                        <div className="grid gap-2 sm:col-span-2">
                            <Label htmlFor="appointment_type">Appointment Type</Label>
                            <NativeSelect
                                id="appointment_type"
                                className="w-full"
                                value={data.appointment_type}
                                onChange={(e) => setData('appointment_type', e.target.value as AppointmentType)}
                                aria-invalid={!!errors.appointment_type}
                            >
                                <NativeSelectOption disabled value="">Select Appointment Type</NativeSelectOption>
                                <NativeSelectOption value="online">Online only</NativeSelectOption>
                                <NativeSelectOption value="in_person">In-person only</NativeSelectOption>
                                <NativeSelectOption value="both">Both (customer chooses)</NativeSelectOption>
                            </NativeSelect>
                            <p className="text-xs text-muted-foreground">Controls which consultation formats are offered on the booking form.</p>
                            <InputError message={errors.appointment_type} />
                        </div>

                        <label
                            htmlFor="requires_custom_assessment"
                            className={`flex cursor-pointer items-center gap-3 rounded-lg border px-4 py-3 text-sm font-medium transition sm:col-span-2 ${data.requires_custom_assessment ? 'border-primary bg-primary/5 ring-1 ring-primary/30' : 'hover:bg-muted/50'}`}
                        >
                            <input
                                type="checkbox"
                                id="requires_custom_assessment"
                                className="h-4 w-4 rounded accent-primary"
                                checked={data.requires_custom_assessment}
                                onChange={(e) => setData('requires_custom_assessment', e.target.checked)}
                            />
                            Requires Custom Assessment (Reservation-style booking)
                        </label>

                        <div className="grid gap-2 sm:col-span-2">
                            <Label htmlFor="required_form_fields">Required Form Fields (comma separated)</Label>
                            <Textarea
                                id="required_form_fields"
                                value={data.required_form_fields}
                                onChange={(e) => setData('required_form_fields', e.target.value)}
                                aria-invalid={!!errors.required_form_fields}
                                className="font-mono"
                                placeholder='motherName, age, symptoms'
                                rows={3}
                            />
                            <p className="text-xs text-muted-foreground">These fields are collected during booking (e.g., motherName, phone).</p>
                            <InputError message={errors.required_form_fields} />
                        </div>

                        <div className="grid gap-2 sm:col-span-2">
                            <Label htmlFor="submit_button_text">Submit Button Text</Label>
                            <Input
                                id="submit_button_text"
                                value={data.submit_button_text}
                                onChange={(e) => setData('submit_button_text', e.target.value)}
                                aria-invalid={!!errors.submit_button_text}
                                placeholder="E.g., Book Now, Request Assessment"
                            />
                            <InputError message={errors.submit_button_text} />
                        </div>
                    </Section>

                    <div className="flex justify-end gap-2">
                        <Button variant="outline" asChild>
                            <a href={index().url}>Cancel</a>
                        </Button>
                        <Button type="submit" disabled={processing}>
                            <Save className="h-4 w-4" />
                            {processing ? 'Updating.....' : 'Update Service'}
                        </Button>
                    </div>
                </form>

                {/* Schedule Management */}
                <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                    <div className="border-b px-5 py-4">
                        <h2 className="font-semibold">Weekly Availability</h2>
                        <p className="mt-0.5 text-sm text-muted-foreground">Days and times customers can book this service, regardless of practitioner.</p>
                    </div>

                    <div className="space-y-5 p-5">
                        {service.schedules.length > 0 ? (
                            <div className="divide-y overflow-hidden rounded-lg border">
                                {service.schedules.map(schedule => (
                                    <div key={schedule.id} className="flex items-center justify-between px-4 py-3 hover:bg-muted/40">
                                        <div className="flex items-center gap-4">
                                            <span className="w-24 text-sm font-medium">{DAYS[schedule.day_of_week]}</span>
                                            <span className="text-sm tabular-nums text-muted-foreground">{schedule.start_time} — {schedule.end_time}</span>
                                        </div>
                                        <Button type="button" variant="ghost" size="icon"
                                            className="h-8 w-8 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                            onClick={() => handleDeleteSchedule(schedule.id)}>
                                            <Trash2 className="h-4 w-4" />
                                        </Button>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="flex flex-col items-center gap-2 py-8 text-center text-muted-foreground">
                                <CalendarClock className="h-8 w-8 opacity-40" />
                                <p className="text-sm">No schedules set. Add availability below.</p>
                            </div>
                        )}

                        <form onSubmit={handleAddSchedule} className="flex flex-wrap items-end gap-4 rounded-lg border border-dashed bg-muted/50 p-4">
                            <div className="grid gap-2">
                                <Label htmlFor="schedule_day">Day</Label>
                                <NativeSelect id="schedule_day" value={scheduleData.day_of_week} onChange={e => setScheduleData({ ...scheduleData, day_of_week: Number(e.target.value) })}>
                                    {DAYS.map((day, i) => (
                                        <NativeSelectOption key={i} value={i}>{day}</NativeSelectOption>
                                    ))}
                                </NativeSelect>
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="schedule_start">Start Time</Label>
                                <Input id="schedule_start" type="time" value={scheduleData.start_time} onChange={(e) => setScheduleData({ ...scheduleData, start_time: e.target.value })} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="schedule_end">End Time</Label>
                                <Input id="schedule_end" type="time" value={scheduleData.end_time} onChange={(e) => setScheduleData({ ...scheduleData, end_time: e.target.value })} />
                            </div>
                            <Button type="submit" disabled={addingSchedule}>
                                <Plus className="h-4 w-4" /> {addingSchedule ? 'Adding...' : 'Add'}
                            </Button>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
