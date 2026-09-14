import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { Head, Link } from '@inertiajs/react';
import { BreadcrumbItem } from '@/types';
import { index as bookingIndex, edit } from '@/actions/App/Http/Controllers/BookingController';
import { Button } from '@/components/ui/button';
import { ArrowLeft, Pencil } from 'lucide-react';
import { dashboard } from '@/routes';
import PageHeader from '@/components/page-header';
import { statusClasses, statusLabel } from '@/lib/status';

type Value = string | number | boolean | string[] | null | undefined;

interface Payment {
    id: number;
    payment_intent_id: string | null;
    amount: number;
    currency: string;
    status: string;
    created_at: string;
}

interface Booking {
    id: number;
    booking_id: string;
    booking_status: string;
    payment_status: string;
    booking_date: string | null;
    booking_time: string | null;
    created_at: string;
    updated_at: string;
    service_id: string;
    service: { title: string; id_code: string; appointment_type: string | null } | null;
    instructor: { name: string; title: string | null; email: string | null; phone: string | null } | null;
    first_name: string | null;
    last_name: string | null;
    full_name: string;
    email: string;
    phone_country: string | null;
    phone_number: string | null;
    gender: string | null;
    age: string | null;
    language: string | null;
    ethnic_origin: string | null;
    mother_name: string | null;
    is_first_appointment: string | boolean | null;
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
    service_price: string;
    donation_addon: string | null;
    customer: {
        id: number;
        name: string;
        email: string;
        phone_prefix: string | null;
        phone: string | null;
        interests: string[] | null;
        about: string | null;
        is_active: boolean;
        email_verified_at: string | null;
        created_at: string;
    } | null;
}

const format = (value: Value): string => {
    if (value === null || value === undefined || value === '') return '—';
    if (typeof value === 'boolean') return value ? 'Yes' : 'No';
    if (Array.isArray(value)) return value.filter(Boolean).map(String).join(', ') || '—';
    return String(value);
};

const formatDate = (value: string | null, withTime = false) =>
    value
        ? new Date(value).toLocaleString('en-GB', withTime
            ? { dateStyle: 'medium', timeStyle: 'short' }
            : { dateStyle: 'medium' })
        : '—';

const Section: React.FC<{ title: string; children: React.ReactNode }> = ({ title, children }) => (
    <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
        <div className="flex items-center justify-between border-b px-5 py-4">
            <h2 className="font-semibold">{title}</h2>
        </div>
        <dl className="grid grid-cols-1 gap-x-6 gap-y-5 p-5 sm:grid-cols-2">{children}</dl>
    </div>
);

const Field: React.FC<{ name: string; value: Value; wide?: boolean; children?: React.ReactNode }> = ({ name, value, wide, children }) => (
    <div className={wide ? 'sm:col-span-2' : ''}>
        <dt className="text-xs font-medium uppercase tracking-wide text-muted-foreground">{name}</dt>
        <dd className="mt-1 text-sm whitespace-pre-line break-words">{children ?? format(value)}</dd>
    </div>
);

export default function Show({ booking, payments }: { booking: Booking; payments: Payment[] }) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Bookings', href: bookingIndex().url },
        { title: booking.booking_id, href: '#' },
    ];

    const total = Number(booking.service_price) + Number(booking.donation_addon ?? 0);
    const hasGuardian = booking.guardian_name || booking.guardian_relationship || booking.guardian_phone;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Booking ${booking.booking_id}`} />
            <div className="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={
                        <span className="flex flex-wrap items-center gap-3">
                            Booking {booking.booking_id}
                            <span className={statusClasses(booking.booking_status)}>{statusLabel(booking.booking_status)}</span>
                            <span className={statusClasses(booking.payment_status)}>{statusLabel(booking.payment_status)}</span>
                        </span>
                    }
                    description={`Created ${formatDate(booking.created_at, true)}`}
                    actions={
                        <>
                            <Button variant="outline" asChild>
                                <Link href={bookingIndex().url}><ArrowLeft className="h-4 w-4" /> Back</Link>
                            </Button>
                            <Button asChild>
                                <Link href={edit(booking.id).url}><Pencil className="h-4 w-4" /> Edit</Link>
                            </Button>
                        </>
                    }
                />

                <div className="flex flex-col gap-6">
                    <Section title="Booking">
                        <Field name="Booking status" value={booking.booking_status}>
                            <span className={statusClasses(booking.booking_status)}>{statusLabel(booking.booking_status)}</span>
                        </Field>
                        <Field name="Payment status" value={booking.payment_status}>
                            <span className={statusClasses(booking.payment_status)}>{statusLabel(booking.payment_status)}</span>
                        </Field>
                        <Field name="Service" value={booking.service ? `${booking.service.title} (${booking.service.id_code})` : booking.service_id} />
                        <Field name="Appointment type" value={booking.service?.appointment_type} />
                        <Field name="Date" value={formatDate(booking.booking_date)} />
                        <Field name="Time" value={booking.booking_time?.slice(0, 5)} />
                        <Field name="Instructor" value={booking.instructor ? [booking.instructor.name, booking.instructor.title].filter(Boolean).join(' — ') : null} />
                        <Field name="Instructor contact" value={booking.instructor ? [booking.instructor.email, booking.instructor.phone].filter(Boolean).join(' / ') : null} />
                        <Field name="First appointment" value={booking.is_first_appointment} />
                        <Field name="Last updated" value={formatDate(booking.updated_at, true)} />
                    </Section>

                    <Section title="Client details">
                        <Field name="First name" value={booking.first_name} />
                        <Field name="Last name" value={booking.last_name} />
                        <Field name="Full name" value={booking.full_name} />
                        <Field name="Email" value={booking.email} />
                        <Field name="Phone" value={booking.phone_number ? [booking.phone_country, booking.phone_number].filter(Boolean).join(' ') : null} />
                        <Field name="Gender" value={booking.gender} />
                        <Field name="Age" value={booking.age} />
                        <Field name="Language" value={booking.language} />
                        <Field name="Ethnic origin" value={booking.ethnic_origin} />
                        <Field name="Mother's name" value={booking.mother_name} />
                    </Section>

                    {hasGuardian && (
                        <Section title="Guardian">
                            <Field name="Name" value={booking.guardian_name} />
                            <Field name="Relationship" value={booking.guardian_relationship} />
                            <Field name="Gender" value={booking.guardian_gender} />
                            <Field name="Phone" value={booking.guardian_phone} />
                        </Section>
                    )}

                    <Section title="Enquiry & symptoms">
                        <Field name="Symptoms" value={booking.symptoms} wide />
                        <Field name="Other symptoms" value={booking.symptoms_other} wide />
                        <Field name="Inquiry description" value={booking.inquiry_description} wide />
                    </Section>

                    <Section title="Pricing">
                        <Field name="Price type" value={booking.price_type} />
                        <Field name="Service price" value={`£${Number(booking.service_price).toFixed(2)}`} />
                        <Field name="Donation add-on" value={`£${Number(booking.donation_addon ?? 0).toFixed(2)}`} />
                        <Field name="Total" value={null}>
                            <span className="text-base font-semibold">£{total.toFixed(2)}</span>
                        </Field>
                    </Section>

                    <Section title="Marketing">
                        <Field name="Found us via" value={booking.found_via} />
                        <Field name="Consent to updates" value={booking.consent_updates} />
                    </Section>

                    <Section title="Customer account">
                        {booking.customer ? (
                            <>
                                <Field name="Name" value={booking.customer.name} />
                                <Field name="Email" value={booking.customer.email} />
                                <Field name="Phone" value={booking.customer.phone ? [booking.customer.phone_prefix, booking.customer.phone].filter(Boolean).join(' ') : null} />
                                <Field name="Email verified" value={booking.customer.email_verified_at ? formatDate(booking.customer.email_verified_at, true) : 'No'} />
                                <Field name="Active" value={booking.customer.is_active} />
                                <Field name="Joined" value={formatDate(booking.customer.created_at)} />
                                <Field name="Interests" value={booking.customer.interests} wide />
                                <Field name="About" value={booking.customer.about} wide />
                            </>
                        ) : (
                            <p className="text-sm text-muted-foreground sm:col-span-2">Booked as a guest (no customer account).</p>
                        )}
                    </Section>

                    <div className="overflow-hidden rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div className="flex items-center justify-between border-b px-5 py-4">
                            <h2 className="font-semibold">Payments</h2>
                        </div>
                        {payments.length > 0 ? (
                            <div className="overflow-x-auto">
                                <table className="w-full text-sm">
                                    <thead className="bg-muted/50 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                        <tr>
                                            <th className="px-5 py-2.5">Date</th>
                                            <th className="px-5 py-2.5">Amount</th>
                                            <th className="px-5 py-2.5">Status</th>
                                            <th className="px-5 py-2.5">Stripe payment ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {payments.map((p) => (
                                            <tr key={p.id} className="border-t hover:bg-muted/40">
                                                <td className="px-5 py-2.5">{formatDate(p.created_at, true)}</td>
                                                <td className="px-5 py-2.5">{(p.amount / 100).toFixed(2)} {p.currency.toUpperCase()}</td>
                                                <td className="px-5 py-2.5"><span className={statusClasses(p.status)}>{statusLabel(p.status)}</span></td>
                                                <td className="px-5 py-2 font-mono text-xs">{format(p.payment_intent_id)}</td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        ) : (
                            <p className="py-12 text-center text-sm text-muted-foreground">No payment records for this booking.</p>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
