import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { index as bookingIndex, edit, show, updateStatus, sendOrderEmail } from '@/actions/App/Http/Controllers/BookingController';
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { CalendarX2, Eye, Mail, Pencil } from 'lucide-react';
import Pagination from '@/components/pagination';
import { dashboard } from '@/routes';
import FilterBar from '@/components/filter-bar';
import PageHeader from '@/components/page-header';
import { statusClasses, statusLabel } from '@/lib/status';
import { useCan } from '@/lib/permissions';

interface Customer { id: number; name: string; }

type BookingStatus = 'new' | 'confirmed' | 'in_progress' | 'completed' | 'cancelled';
type PaymentStatus = 'pending' | 'paid' | 'failed' | 'assessment_required';

interface Booking {
    id: number;
    customer_id: number | null;
    customer: Customer | null;
    booking_id: string;
    full_name: string;
    email: string;
    service_id: string;
    service: { title: string } | null;
    instructor: { name: string } | null;
    booking_date: string | null;
    booking_time: string | null;
    service_price: number;
    price_type: string;
    payment_status: PaymentStatus;
    booking_status: BookingStatus;
    phone_number: string | null;
    created_at: string;
}

interface PaginatedBookings {
    data: Booking[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

interface BookingsIndexProps {
    bookings: PaginatedBookings;
    bookingStatuses: BookingStatus[];
    paymentStatuses: PaymentStatus[];
    filters: Record<string, string>;
}

const th = 'text-xs font-medium uppercase tracking-wide text-muted-foreground';

export default function Index({ bookings, bookingStatuses, paymentStatuses, filters }: BookingsIndexProps) {
    const canManage = useCan('bookings.manage');
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Bookings', href: bookingIndex().url }
    ];

    const handleStatusChange = (booking: Booking, newStatus: BookingStatus) => {
        if (window.confirm(`Change Booking #${booking.booking_id} status to ${newStatus.toUpperCase()}?`)) {
            router.patch(updateStatus(booking.id).url, { booking_status: newStatus }, {
                preserveScroll: true,
            });
        }
    };

    const handleSendEmail = (booking: Booking) => {
        if (window.confirm(`Send booking confirmation email to ${booking.email} for Booking #${booking.booking_id}?`)) {
            router.post(sendOrderEmail(booking.id).url, {}, {
                preserveScroll: true,
            });
        }
    };

    const StatusDropdown: React.FC<{ booking: Booking }> = ({ booking }) => (
        <select
            value={booking.booking_status}
            onChange={(e) => handleStatusChange(booking, e.target.value as BookingStatus)}
            className={`${statusClasses(booking.booking_status)} cursor-pointer appearance-none pr-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-ring`}
        >
            {bookingStatuses.map(s => (
                <option key={s} value={s} className="bg-background text-foreground">
                    {statusLabel(s)}
                </option>
            ))}
        </select>
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Bookings Management" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                    <PageHeader title="Booking Management" description={`${bookings.total} total bookings`} />

                    <FilterBar
                        filters={filters}
                        placeholder="Search by name, ref, email, or service..."
                        baseUrl={bookingIndex().url}
                        filterConfigs={[
                            {
                                key: 'booking_status',
                                label: 'All Booking Status',
                                options: bookingStatuses.map(s => ({ label: statusLabel(s), value: s })),
                            },
                            {
                                key: 'payment_status',
                                label: 'All Payment Status',
                                options: paymentStatuses.map(s => ({ label: statusLabel(s), value: s })),
                            },
                        ]}
                    />

                    <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                      <div className="overflow-x-auto">
                        <Table>
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={`w-[150px] ${th}`}>Ref / Client</TableHead>
                                    <TableHead className={th}>Service Info</TableHead>
                                    <TableHead className={`text-center ${th}`}>Booking Status</TableHead>
                                    <TableHead className={`text-center ${th}`}>Payment Status</TableHead>
                                    <TableHead className={`text-right ${th}`}>Price</TableHead>
                                    <TableHead className={`w-[150px] text-center ${th}`}>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {bookings.data.length > 0 ? (
                                    bookings.data.map((booking) => (
                                        <TableRow key={booking.id} className="hover:bg-muted/40">
                                            <TableCell>
                                                <Link href={show(booking.id).url} className="font-medium text-primary hover:underline">{booking.booking_id}</Link>
                                                <div className="text-sm font-medium">{booking.full_name}</div>
                                                <div className="text-xs text-muted-foreground">{booking.email}</div>
                                                {booking.phone_number && <div className="text-xs text-muted-foreground">{booking.phone_number}</div>}
                                            </TableCell>
                                            <TableCell>
                                                <div className="font-medium">{booking.service?.title ?? booking.service_id}</div>
                                                <div className="text-xs text-muted-foreground">
                                                    {booking.booking_date ? new Date(booking.booking_date).toLocaleDateString('en-GB') : 'No date'}
                                                    {booking.booking_time && ` · ${booking.booking_time.slice(0, 5)}`}
                                                </div>
                                                {booking.instructor && <div className="text-xs text-muted-foreground">{booking.instructor.name}</div>}
                                                <div className="text-xs text-muted-foreground">{booking.price_type}</div>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                {canManage ? (
                                                    <StatusDropdown booking={booking} />
                                                ) : (
                                                    <span className={statusClasses(booking.booking_status)}>
                                                        {statusLabel(booking.booking_status)}
                                                    </span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className={statusClasses(booking.payment_status)}>
                                                    {statusLabel(booking.payment_status)}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-right">
                                                <div className="text-sm font-semibold tabular-nums">£{Number(booking.service_price).toFixed(2)}</div>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex justify-center gap-1">
                                                    <Button variant="ghost" size="icon" className="h-8 w-8" title="View Details" asChild>
                                                        <Link href={show(booking.id).url}><Eye className="h-4 w-4" /></Link>
                                                    </Button>
                                                    {canManage && (
                                                        <>
                                                            <Button variant="ghost" size="icon" className="h-8 w-8" title="Edit Booking" asChild>
                                                                <Link href={edit(booking.id).url}><Pencil className="h-4 w-4" /></Link>
                                                            </Button>
                                                            <Button
                                                                variant="ghost" size="icon"
                                                                onClick={() => handleSendEmail(booking)}
                                                                className="h-8 w-8"
                                                                title="Send Confirmation Email"
                                                            >
                                                                <Mail className="h-4 w-4" />
                                                            </Button>
                                                        </>
                                                    )}
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow className="hover:bg-transparent">
                                        <TableCell colSpan={6}>
                                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                                <CalendarX2 className="h-8 w-8 opacity-40" />
                                                <p className="text-sm">No bookings found matching your filters.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                      </div>
                        <Pagination links={bookings.links} />
                    </div>
            </div>
        </AppLayout>
    );
}
