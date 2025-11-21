// resources/js/Pages/Bookings/Edit.tsx (Updated)

import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, useForm, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
// Import your custom route helpers
import { dashboard, index as bookingIndex, update } from '@/routes/booking'; 
import { Button } from "@/components/ui/button";
import { CornerUpLeft } from 'lucide-react';

type BookingStatus = 'new' | 'confirmed' | 'in_progress' | 'completed' | 'cancelled';
type PaymentStatus = 'pending' | 'paid' | 'failed' | 'assessment_required';
type PriceType = 'FIXED' | 'DONATION' | 'FREE' | 'RESERVATION';

interface Customer { id: number; name: string; } 

interface Booking {
    id: number;
    customer: Customer | null;
    booking_id: string; 
    full_name: string;
    email: string;
    mother_name: string | null;
    inquiry_description: string;
    service_id: string;
    price_type: PriceType;
    service_price: number;
    payment_status: PaymentStatus;
    booking_status: BookingStatus;
    phone_number: string | null;
}

interface BookingsEditProps {
    booking: Booking;
    bookingStatuses: BookingStatus[];
    paymentStatuses: PaymentStatus[];
    errors: { [key: string]: string };
}

export default function Edit({ booking, bookingStatuses, paymentStatuses, errors }: BookingsEditProps) {
    
    // Price types defined based on migration comment
    const priceTypes: PriceType[] = ['FIXED', 'DONATION', 'FREE', 'RESERVATION'];

    const { data, setData, patch, processing } = useForm({
        full_name: booking.full_name,
        email: booking.email,
        mother_name: booking.mother_name || '',
        inquiry_description: booking.inquiry_description,
        service_id: booking.service_id,
        price_type: booking.price_type,
        service_price: booking.service_price.toString(),
        payment_status: booking.payment_status,
        booking_status: booking.booking_status,
        phone_number: booking.phone_number || '',
    });

    const INPUT_CLASSES = "mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2";

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: dashboard().url },
        { title: 'Bookings', href: bookingIndex().url },
        { title: `Edit: ${booking.booking_id}`, href: update(booking.id).url }
    ];

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        patch(update(booking.id).url, {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Booking: ${booking.booking_id}`} />
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full ">
                    <div className="flex justify-between items-center mb-4">
                        <Link 
                            href={bookingIndex().url}
                            className="text-gray-600 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center shadow-sm"
                        >
                            <CornerUpLeft className="mr-2 h-4 w-4" />
                            Back to Bookings List
                        </Link>
                    </div>

                    <div className="p-6 border rounded-xl bg-white shadow-xl max-w-4xl mx-auto w-full">
                        <h2 className="text-2xl font-bold mb-6 text-indigo-700">
                            Editing Booking: <span className="text-gray-900">{booking.booking_id}</span>
                        </h2>
                        
                        <form onSubmit={handleSubmit} className="space-y-6">
                            
                            {/* --- CUSTOMER DETAILS --- */}
                            <div className="grid grid-cols-3 gap-6 border-b pb-4">
                                <h3 className="text-lg font-semibold text-gray-700 col-span-3">Customer Details</h3>
                                
                                {/* Full Name */}
                                <div>
                                    <label htmlFor="full_name" className="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input
                                        id="full_name"
                                        type="text"
                                        value={data.full_name}
                                        onChange={(e) => setData('full_name', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                    {errors.full_name && <p className="mt-2 text-sm text-red-600">{errors.full_name}</p>}
                                </div>

                                {/* Email */}
                                <div>
                                    <label htmlFor="email" className="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input
                                        id="email"
                                        type="email"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                    {errors.email && <p className="mt-2 text-sm text-red-600">{errors.email}</p>}
                                </div>
                                
                                {/* Phone Number */}
                                <div>
                                    <label htmlFor="phone_number" className="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input
                                        id="phone_number"
                                        type="text"
                                        value={data.phone_number}
                                        onChange={(e) => setData('phone_number', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                    {errors.phone_number && <p className="mt-2 text-sm text-red-600">{errors.phone_number}</p>}
                                </div>

                                {/* Mother's Name (Optional) */}
                                <div className="col-span-1">
                                    <label htmlFor="mother_name" className="block text-sm font-medium text-gray-700">Mother's Name (Optional)</label>
                                    <input
                                        id="mother_name"
                                        type="text"
                                        value={data.mother_name}
                                        onChange={(e) => setData('mother_name', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                    {errors.mother_name && <p className="mt-2 text-sm text-red-600">{errors.mother_name}</p>}
                                </div>
                                
                            </div>
                            
                            {/* --- SERVICE & PRICE DETAILS --- */}
                            <div className="grid grid-cols-3 gap-6 border-b pb-4 pt-4">
                                <h3 className="text-lg font-semibold text-gray-700 col-span-3">Service & Price</h3>

                                {/* Service ID */}
                                <div>
                                    <label htmlFor="service_id" className="block text-sm font-medium text-gray-700">Service ID</label>
                                    <input
                                        id="service_id"
                                        type="text"
                                        value={data.service_id}
                                        onChange={(e) => setData('service_id', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                    {errors.service_id && <p className="mt-2 text-sm text-red-600">{errors.service_id}</p>}
                                </div>

                                {/* Price Type */}
                                <div>
                                    <label htmlFor="price_type" className="block text-sm font-medium text-gray-700">Price Type</label>
                                    <select
                                        id="price_type"
                                        value={data.price_type}
                                        onChange={(e) => setData('price_type', e.target.value as PriceType)}
                                        className={INPUT_CLASSES}
                                    >
                                        {priceTypes.map(p => (
                                            <option key={p} value={p}>{p}</option>
                                        ))}
                                    </select>
                                    {errors.price_type && <p className="mt-2 text-sm text-red-600">{errors.price_type}</p>}
                                </div>

                                {/* Service Price */}
                                <div>
                                    <label htmlFor="service_price" className="block text-sm font-medium text-gray-700">Service Price/Donation</label>
                                    <input
                                        id="service_price"
                                        type="number"
                                        step="0.01"
                                        value={data.service_price}
                                        onChange={(e) => setData('service_price', e.target.value)}
                                        className={INPUT_CLASSES}
                                    />
                                    {errors.service_price && <p className="mt-2 text-sm text-red-600">{errors.service_price}</p>}
                                </div>
                            </div>

                            {/* --- STATUS DETAILS --- */}
                            <div className="grid grid-cols-2 gap-6 pt-4">
                                <h3 className="text-lg font-semibold text-gray-700 col-span-2">Status Management</h3>

                                {/* Booking Status */}
                                <div>
                                    <label htmlFor="booking_status" className="block text-sm font-medium text-gray-700">Booking Status</label>
                                    <select
                                        id="booking_status"
                                        value={data.booking_status}
                                        onChange={(e) => setData('booking_status', e.target.value as BookingStatus)}
                                        className={INPUT_CLASSES}
                                    >
                                        {bookingStatuses.map(s => (
                                            <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' ')}</option>
                                        ))}
                                    </select>
                                    {errors.booking_status && <p className="mt-2 text-sm text-red-600">{errors.booking_status}</p>}
                                </div>

                                {/* Payment Status */}
                                <div>
                                    <label htmlFor="payment_status" className="block text-sm font-medium text-gray-700">Payment Status</label>
                                    <select
                                        id="payment_status"
                                        value={data.payment_status}
                                        onChange={(e) => setData('payment_status', e.target.value as PaymentStatus)}
                                        className={INPUT_CLASSES}
                                    >
                                        {paymentStatuses.map(s => (
                                            <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' ')}</option>
                                        ))}
                                    </select>
                                    {errors.payment_status && <p className="mt-2 text-sm text-red-600">{errors.payment_status}</p>}
                                </div>
                            </div>

                            {/* Inquiry Description */}
                            <div className="pt-4">
                                <label htmlFor="inquiry_description" className="block text-sm font-medium text-gray-700">Inquiry Description</label>
                                <textarea
                                    id="inquiry_description"
                                    rows={4}
                                    value={data.inquiry_description}
                                    onChange={(e) => setData('inquiry_description', e.target.value)}
                                    className={INPUT_CLASSES}
                                />
                                {errors.inquiry_description && <p className="mt-2 text-sm text-red-600">{errors.inquiry_description}</p>}
                            </div>

                            <div className="flex justify-end pt-4 border-t border-gray-200">
                                <Button type="submit" disabled={processing} className="bg-indigo-600 hover:bg-indigo-700">
                                    {processing ? 'Saving...' : 'Save Changes'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}