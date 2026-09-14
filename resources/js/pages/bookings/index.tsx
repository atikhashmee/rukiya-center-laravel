import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { index as bookingIndex, edit, show, updateStatus, sendOrderEmail } from '@/actions/App/Http/Controllers/BookingController';
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Eye, Mail, Pencil } from 'lucide-react';
import Pagination from '@/components/pagination';
import { dashboard } from '@/routes';
import FilterBar from '@/components/filter-bar';

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

const getStatusColor = (status: string) => {
    switch (status) {
        case 'completed': return 'bg-green-100 text-green-800 border-green-300 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800';
        case 'confirmed': return 'bg-indigo-100 text-indigo-800 border-indigo-300 dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-800';
        case 'in_progress':
        case 'paid':
        case 'assessment_required': return 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800';
        case 'cancelled':
        case 'failed': return 'bg-red-100 text-red-800 border-red-300 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800';
        case 'new':
        case 'pending':
        default: return 'bg-yellow-100 text-yellow-800 border-yellow-300 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800';
    }
};

const label = (s: string) => s.charAt(0).toUpperCase() + s.slice(1).replaceAll('_', ' ');

export default function Index({ bookings, bookingStatuses, paymentStatuses, filters }: BookingsIndexProps) {
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
            className={`rounded-md text-xs font-medium border p-1 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-ring ${getStatusColor(booking.booking_status)}`}
        >
            {bookingStatuses.map(s => (
                <option key={s} value={s} className="bg-background text-foreground">
                    {label(s)}
                </option>
            ))}
        </select>
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Bookings Management" />
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full">
                    <div>
                        <h2 className="text-2xl font-bold">Booking Management</h2>
                        <p className="text-sm text-muted-foreground mt-1">{bookings.total} total bookings</p>
                    </div>

                    <FilterBar
                        filters={filters}
                        placeholder="Search by name, ref, email, or service..."
                        baseUrl={bookingIndex().url}
                        filterConfigs={[
                            {
                                key: 'booking_status',
                                label: 'All Booking Status',
                                options: bookingStatuses.map(s => ({ label: label(s), value: s })),
                            },
                            {
                                key: 'payment_status',
                                label: 'All Payment Status',
                                options: paymentStatuses.map(s => ({ label: label(s), value: s })),
                            },
                        ]}
                    />

                    <div className="p-3 border rounded-xl bg-card text-card-foreground shadow-xl overflow-x-auto">
                        <Table>
                            <TableHeader className="bg-muted/50">
                                <TableRow>
                                    <TableHead className="w-[150px]">Ref / Client</TableHead>
                                    <TableHead>Service Info</TableHead>
                                    <TableHead className="text-center">Booking Status</TableHead>
                                    <TableHead className="text-center">Payment Status</TableHead>
                                    <TableHead className="text-right">Price</TableHead>
                                    <TableHead className="text-center w-[150px]">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {bookings.data.length > 0 ? (
                                    bookings.data.map((booking) => (
                                        <TableRow key={booking.id} className="hover:bg-muted/50">
                                            <TableCell>
                                                <div className="font-semibold text-blue-700 dark:text-blue-400">{booking.booking_id}</div>
                                                <div className="text-sm">{booking.full_name}</div>
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
                                                <StatusDropdown booking={booking} />
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium border ${getStatusColor(booking.payment_status)}`}>
                                                    {label(booking.payment_status)}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-right">
                                                <div className="font-semibold text-sm">£{Number(booking.service_price).toFixed(2)}</div>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex space-x-2 justify-center">
                                                    <Link href={show(booking.id).url}>
                                                        <Button variant="outline" size="icon" className="h-8 w-8" title="View Details">
                                                            <Eye className="h-4 w-4" />
                                                        </Button>
                                                    </Link>
                                                    <Link href={edit(booking.id).url}>
                                                        <Button variant="outline" size="icon" className="h-8 w-8" title="Edit Booking">
                                                            <Pencil className="h-4 w-4" />
                                                        </Button>
                                                    </Link>
                                                    <Button
                                                        variant="outline" size="icon"
                                                        onClick={() => handleSendEmail(booking)}
                                                        className="h-8 w-8"
                                                        title="Send Confirmation Email"
                                                    >
                                                        <Mail className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow>
                                        <TableCell colSpan={6} className="h-24 text-center text-muted-foreground">
                                            No bookings found matching your filters.
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                        <Pagination links={bookings.links} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
