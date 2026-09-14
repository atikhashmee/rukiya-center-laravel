import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, useForm, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index as bookingIndex, show, update } from '@/actions/App/Http/Controllers/BookingController';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { NativeSelect, NativeSelectOption } from "@/components/ui/native-select";
import InputError from "@/components/input-error";
import { ArrowLeft, Eye } from 'lucide-react';

type BookingStatus = 'new' | 'confirmed' | 'in_progress' | 'completed' | 'cancelled';
type PaymentStatus = 'pending' | 'paid' | 'failed' | 'assessment_required';

interface Booking {
    id: number;
    booking_id: string;
    service_id: string;
    instructor_id: number | null;
    booking_date: string | null;
    booking_time: string | null;
    first_name: string | null;
    last_name: string | null;
    full_name: string;
    email: string;
    phone_country: string | null;
    phone_number: string | null;
    mother_name: string | null;
    gender: string | null;
    age: string | null;
    language: string | null;
    ethnic_origin: string | null;
    is_first_appointment: string | null;
    symptoms: string[] | null;
    symptoms_other: string | null;
    inquiry_description: string | null;
    found_via: string[] | null;
    consent_updates: boolean | null;
    guardian_gender: string | null;
    guardian_name: string | null;
    guardian_relationship: string | null;
    guardian_phone: string | null;
    price_type: string;
    service_price: string | number;
    donation_addon: string | number | null;
    payment_status: PaymentStatus;
    booking_status: BookingStatus;
}

interface BookingsEditProps {
    booking: Booking;
    services: { id: number; title: string }[];
    instructors: { id: number; name: string }[];
    bookingStatuses: BookingStatus[];
    paymentStatuses: PaymentStatus[];
}

const priceTypes = ['FIXED', 'DONATION', 'FREE', 'RESERVATION'];
const label = (s: string) => s.charAt(0).toUpperCase() + s.slice(1).replaceAll('_', ' ');
const toList = (s: string) => s.split(',').map(v => v.trim()).filter(Boolean);

const Section: React.FC<{ title: string; children: React.ReactNode }> = ({ title, children }) => (
    <div className="border rounded-xl bg-card text-card-foreground shadow-sm">
        <h3 className="px-5 py-3 border-b bg-muted/50 rounded-t-xl font-semibold">{title}</h3>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 p-5">{children}</div>
    </div>
);

const Field: React.FC<{ id: string; name: string; error?: string; wide?: boolean; children: React.ReactNode }> = ({ id, name, error, wide, children }) => (
    <div className={`grid gap-2 content-start ${wide ? 'sm:col-span-2 lg:col-span-3' : ''}`}>
        <Label htmlFor={id}>{name}</Label>
        {children}
        <InputError message={error} />
    </div>
);

export default function Edit({ booking, services, instructors, bookingStatuses, paymentStatuses }: BookingsEditProps) {
    const { data, setData, transform, patch, processing, errors } = useForm({
        service_id: String(booking.service_id ?? ''),
        instructor_id: booking.instructor_id ? String(booking.instructor_id) : '',
        booking_date: booking.booking_date?.slice(0, 10) ?? '',
        booking_time: booking.booking_time?.slice(0, 5) ?? '',
        booking_status: booking.booking_status,
        payment_status: booking.payment_status,
        first_name: booking.first_name ?? '',
        last_name: booking.last_name ?? '',
        full_name: booking.full_name ?? '',
        email: booking.email ?? '',
        phone_country: booking.phone_country ?? '',
        phone_number: booking.phone_number ?? '',
        mother_name: booking.mother_name ?? '',
        gender: booking.gender ?? '',
        age: booking.age ?? '',
        language: booking.language ?? '',
        ethnic_origin: booking.ethnic_origin ?? '',
        is_first_appointment: booking.is_first_appointment ?? '',
        symptoms: (booking.symptoms ?? []).join(', '),
        symptoms_other: booking.symptoms_other ?? '',
        inquiry_description: booking.inquiry_description ?? '',
        found_via: (booking.found_via ?? []).join(', '),
        consent_updates: Boolean(booking.consent_updates),
        guardian_name: booking.guardian_name ?? '',
        guardian_relationship: booking.guardian_relationship ?? '',
        guardian_gender: booking.guardian_gender ?? '',
        guardian_phone: booking.guardian_phone ?? '',
        price_type: booking.price_type,
        service_price: String(booking.service_price ?? '0'),
        donation_addon: String(booking.donation_addon ?? '0'),
    });

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Bookings', href: bookingIndex().url },
        { title: `Edit: ${booking.booking_id}`, href: '#' },
    ];

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        transform((d) => ({ ...d, symptoms: toList(d.symptoms), found_via: toList(d.found_via) }));
        patch(update(booking.id).url, { preserveScroll: true });
    };

    type Key = keyof typeof data;
    const text = (key: Key, name: string, type = 'text') => (
        <Field id={key} name={name} error={errors[key]}>
            <Input id={key} type={type} value={data[key] as string} onChange={(e) => setData(key, e.target.value)} />
        </Field>
    );
    const select = (key: Key, name: string, options: { value: string; label: string }[], empty?: string) => (
        <Field id={key} name={name} error={errors[key]}>
            <NativeSelect id={key} className="w-full" value={data[key] as string} onChange={(e) => setData(key, e.target.value)}>
                {empty !== undefined && <NativeSelectOption value="">{empty}</NativeSelectOption>}
                {options.map(o => <NativeSelectOption key={o.value} value={o.value}>{o.label}</NativeSelectOption>)}
            </NativeSelect>
        </Field>
    );
    const genders = [{ value: 'male', label: 'Male' }, { value: 'female', label: 'Female' }];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Booking: ${booking.booking_id}`} />
            <div className="container py-4 px-4 max-w-5xl">
                <div className="flex flex-wrap items-center justify-between gap-3 mb-6">
                    <h2 className="text-2xl font-bold">Edit Booking {booking.booking_id}</h2>
                    <div className="flex gap-2">
                        <Link href={bookingIndex().url}>
                            <Button variant="outline"><ArrowLeft className="h-4 w-4" /> Back</Button>
                        </Link>
                        <Link href={show(booking.id).url}>
                            <Button variant="outline"><Eye className="h-4 w-4" /> View</Button>
                        </Link>
                    </div>
                </div>

                <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                    <Section title="Booking">
                        {select('service_id', 'Service', services.map(s => ({ value: String(s.id), label: s.title })))}
                        {select('instructor_id', 'Instructor', instructors.map(i => ({ value: String(i.id), label: i.name })), 'No instructor')}
                        {text('booking_date', 'Date', 'date')}
                        {text('booking_time', 'Time', 'time')}
                        {select('booking_status', 'Booking status', bookingStatuses.map(s => ({ value: s, label: label(s) })))}
                        {select('payment_status', 'Payment status', paymentStatuses.map(s => ({ value: s, label: label(s) })))}
                        {text('is_first_appointment', 'First appointment')}
                    </Section>

                    <Section title="Client details">
                        {text('first_name', 'First name')}
                        {text('last_name', 'Last name')}
                        {text('full_name', 'Full name')}
                        {text('email', 'Email', 'email')}
                        {text('phone_country', 'Phone country')}
                        {text('phone_number', 'Phone number', 'tel')}
                        {select('gender', 'Gender', genders, '—')}
                        {text('age', 'Age')}
                        {text('language', 'Language')}
                        {text('ethnic_origin', 'Ethnic origin')}
                        {text('mother_name', "Mother's name")}
                    </Section>

                    <Section title="Guardian">
                        {text('guardian_name', 'Name')}
                        {text('guardian_relationship', 'Relationship')}
                        {select('guardian_gender', 'Gender', genders, '—')}
                        {text('guardian_phone', 'Phone', 'tel')}
                    </Section>

                    <Section title="Enquiry & symptoms">
                        <Field id="symptoms" name="Symptoms (comma separated)" error={errors.symptoms} wide>
                            <Input id="symptoms" value={data.symptoms} onChange={(e) => setData('symptoms', e.target.value)} />
                        </Field>
                        <Field id="symptoms_other" name="Other symptoms" error={errors.symptoms_other} wide>
                            <Textarea id="symptoms_other" rows={2} value={data.symptoms_other} onChange={(e) => setData('symptoms_other', e.target.value)} />
                        </Field>
                        <Field id="inquiry_description" name="Inquiry description" error={errors.inquiry_description} wide>
                            <Textarea id="inquiry_description" rows={4} value={data.inquiry_description} onChange={(e) => setData('inquiry_description', e.target.value)} />
                        </Field>
                    </Section>

                    <Section title="Pricing">
                        {select('price_type', 'Price type', priceTypes.map(p => ({ value: p, label: p })))}
                        {text('service_price', 'Service price (£)', 'number')}
                        {text('donation_addon', 'Donation add-on (£)', 'number')}
                    </Section>

                    <Section title="Marketing">
                        <Field id="found_via" name="Found us via (comma separated)" error={errors.found_via} wide>
                            <Input id="found_via" value={data.found_via} onChange={(e) => setData('found_via', e.target.value)} />
                        </Field>
                        <label className="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                className="h-4 w-4 accent-primary"
                                checked={data.consent_updates}
                                onChange={(e) => setData('consent_updates', e.target.checked)}
                            />
                            Consents to receive updates
                        </label>
                    </Section>

                    <div className="flex justify-end">
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Saving...' : 'Save Changes'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
