import React, { useEffect, type ReactNode } from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, useForm } from '@inertiajs/react';
import { BreadcrumbItem} from "@/types";
import { dashboard } from '@/routes';
import { store, index, create } from "@/actions/App/Http/Controllers/ServiceController";
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';
import InputError from '@/components/input-error';
import PageHeader from '@/components/page-header';
import { ArrowLeft, Save } from 'lucide-react';

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

interface CreateServiceOptionProps {
    serviceCategories: { id: number; name: string; slug: string }[];
}

// --- Initial Data ---
const initialData: ServiceOptionFormData = {
    id_code: '',
    category_id: '',
    title: '',
    tagline: '',
    description: '',
    icon: 'Sparkles',
    card_color: 'border-l-indigo-500',
    features: ["Quick turnaround", "Basic analysis"],
    order: 1,
    price_type: 'FIXED',
    price_value: 50.00,
    min_donation: null,
    requires_custom_assessment: false,
    appointment_type: 'both',
    required_form_fields: ["email", "question"],
    submit_button_text: 'Book Now',
};

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

export default function Create({ serviceCategories = [] }: CreateServiceOptionProps) {

    const pageTitle = `Create New Service `;

    const { data, setData, errors, processing, post} = useForm<ServiceOptionFormData>(initialData);
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        const submitData = {
            ...data,
            features: data.features,
            required_form_fields: data.required_form_fields,
            price_value: data.price_type === 'FIXED' ? data.price_value : null,
            min_donation: data.price_type === 'DONATION' ? data.min_donation : null,
        };

        console.log("Final data structure being sent for Creation:", submitData);

        post(store().url, {
            ...submitData,
            onSuccess: () => {
                router.visit(index());
            },
        } as any
);
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Services', href: index().url },
        { title: 'Create service', href: create().url },
    ];

    // Cleanup Effect for Price Fields on Type Change
    useEffect(() => {
        if (data.price_type === 'FREE' || data.price_type === 'RESERVATION') {
            setData({ price_value: null, min_donation: null });
        }
        if (data.price_type === 'FIXED' && data.price_value === null) {
            setData('price_value', 50.00);
        }
        if (data.price_type === 'DONATION' && data.min_donation === null) {
            setData('min_donation', 10.00);
        }
    }, [data.price_type, setData, errors]);


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
                            {serviceCategories.length === 0 && (
                                <p className="text-xs text-amber-700 dark:text-amber-300">
                                    No categories yet — <a href="/admin/service-categories/create" className="text-primary hover:underline">create one first</a>.
                                </p>
                            )}
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
                            <Label htmlFor="features">Features (,) Comma separated</Label>
                            <Textarea
                                id="features"
                                value={data.features}
                                onChange={(e: any) => setData('features', e.target.value)}
                                aria-invalid={!!errors.features}
                                className="font-mono"
                                placeholder='["Feature 1", "Feature 2"]'
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
                            <Label htmlFor="required_form_fields">Required Form Fields (,)comma separated</Label>
                            <Textarea
                                id="required_form_fields"
                                value={data.required_form_fields}
                                onChange={(e: any) => setData('required_form_fields', e.target.value)}
                                aria-invalid={!!errors.required_form_fields}
                                className="font-mono"
                                placeholder='["motherName", "age", "symptoms"]'
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
                            {processing ? 'Saving.....' : 'Create Option'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
