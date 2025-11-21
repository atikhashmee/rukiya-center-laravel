// resources/js/Pages/Bookings/Index.tsx (Updated)

import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
// Import your custom route helpers
import {  index as bookingIndex, edit, updateStatus, sendOrderEmail } from '@/actions/App/Http/Controllers/BookingController'; 
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Mail, Pencil, RefreshCw, DollarSign, XCircle, CheckCircle } from 'lucide-react';
import Pagination from '@/components/pagination';
import { dashboard } from '@/routes';

// Customer is nullable now
interface Customer { id: number; name: string; } 

type BookingStatus = 'new' | 'confirmed' | 'in_progress' | 'completed' | 'cancelled';
type PaymentStatus = 'pending' | 'paid' | 'failed' | 'assessment_required';

interface PaginationLink {
    url: string | null; 
    label: string;      
    active: boolean;    
}

interface PaginatedData<T> {
    current_page: number;
    data: T[]; 
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[]; 
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

interface Booking {
    id: number;
    customer_id: number | null;
    customer: Customer | null;
    booking_id: string; // Unique identifier from migration
    full_name: string;
    email: string;
    service_id: string;
    service_price: number;
    price_type: string;
    payment_status: PaymentStatus;
    booking_status: BookingStatus;
    phone_number: string | null;
    created_at: string;
}

interface BookingsIndexProps {
    bookings: PaginatedData<Booking>;
    bookingStatuses: BookingStatus[];
    paymentStatuses: PaymentStatus[];
}

// Helper to define colors for statuses
const getStatusColor = (status: string, prefix: 'booking' | 'payment') => {
    switch (status) {
        case 'completed': return 'bg-green-100 text-green-800 border-green-300';
        case 'confirmed': return 'bg-indigo-100 text-indigo-800 border-indigo-300';
        case 'in_progress':
        case 'paid': 
        case 'assessment_required': return 'bg-blue-100 text-blue-800 border-blue-300';
        case 'cancelled':
        case 'failed': return 'bg-red-100 text-red-800 border-red-300';
        case 'new':
        case 'pending': 
        default: return 'bg-yellow-100 text-yellow-800 border-yellow-300';
    }
};

export default function Index({ bookings, bookingStatuses, paymentStatuses }: BookingsIndexProps) {

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Bookings', href: bookingIndex().url }
    ];

    const handleStatusChange = (booking: Booking, newStatus: BookingStatus) => {
        if (window.confirm(`Change Booking #${booking.booking_id} status to ${newStatus.toUpperCase()}?`)) {
            // Note: We are specifically updating 'booking_status' here
            router.patch(updateStatus(booking.id).url, { booking_status: newStatus }, {
                preserveScroll: true,
            });
        }
    };

    const handleSendEmail = (booking: Booking) => {
        if (window.confirm(`Send order email/service details to ${booking.email} for Booking #${booking.booking_id}?`)) {
            router.post(sendOrderEmail(booking.id).url, {}, {
                preserveScroll: true,
            });
        }
    };
    
    const formatDate = (dateString: string) => new Date(dateString).toLocaleDateString('en-GB');

    // Utility for rendering status dropdown
    const StatusDropdown: React.FC<{ booking: Booking }> = ({ booking }) => (
        <select
            value={booking.booking_status}
            onChange={(e) => handleStatusChange(booking, e.target.value as BookingStatus)}
            className={`rounded-md text-xs font-medium border p-1 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500 ${getStatusColor(booking.booking_status, 'booking')}`}
        >
            {bookingStatuses.map(s => (
                <option key={s} value={s} className="bg-white text-gray-900">
                    {s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' ')}
                </option>
            ))}
        </select>
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Bookings Management" />
            <div className="container py-4 pl-4">
                <h2 className="text-2xl font-bold text-gray-800 mb-6">Booking Management</h2>
                
                <div className="p-3 border rounded-xl bg-white shadow-xl overflow-x-auto">
                    <Table>
                        <TableHeader className="bg-gray-100/70">
                            <TableRow>
                                <TableHead className="w-[150px]">Ref / Name</TableHead>
                                <TableHead>Service Info</TableHead>
                                <TableHead className="text-center">Booking Status</TableHead>
                                <TableHead className="text-center">Payment Status</TableHead>
                                <TableHead className="text-right">Price</TableHead>
                                <TableHead className="text-center w-[150px]">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {bookings.data.map((booking) => (
                                <TableRow key={booking.id}>
                                    <TableCell>
                                        <div className="font-semibold text-indigo-700">{booking.booking_id}</div>
                                        <div className="text-sm text-gray-800">{booking.full_name}</div>
                                    </TableCell>
                                    <TableCell>
                                        <div className="font-medium text-gray-700">{booking.service_id}</div>
                                        <div className="text-xs text-gray-500">{booking.price_type}</div>
                                    </TableCell>

                                    <TableCell className="text-center">
                                        <StatusDropdown booking={booking} />
                                    </TableCell>

                                    <TableCell className="text-center">
                                        <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium border ${getStatusColor(booking.payment_status, 'payment')}`}>
                                            {booking.payment_status.charAt(0).toUpperCase() + booking.payment_status.slice(1).replace('_', ' ')}
                                        </span>
                                    </TableCell>

                                    <TableCell className="text-right">
                                        <div className="font-semibold text-sm">${booking.service_price.toFixed(2)}</div>
                                    </TableCell>

                                    <TableCell className="text-center">
                                        <div className="flex space-x-2 justify-center">
                                            
                                            {/* Edit Button */}
                                            <Link href={edit(booking.id).url}>
                                                <Button variant="outline" size="icon" className="h-8 w-8 text-indigo-600 border-indigo-300" title="Edit Booking Details">
                                                    <Pencil className="h-4 w-4" />
                                                </Button>
                                            </Link>

                                            {/* Send Email Button */}
                                            <Button 
                                                variant="outline" size="icon"
                                                onClick={() => handleSendEmail(booking)}
                                                className="h-8 w-8 text-blue-600 border-blue-300"
                                                title="Send Service Email"
                                            >
                                                <Mail className="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                    <Pagination links={bookings.links} />
                </div>
            </div>
        </AppLayout>
    );
}