import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import {
    PackageSearch,
    Kanban,
    Rss,
    User,
    Users,
    Book,
    ArrowRight,
    TrendingUp,
    AlertCircle,
    CheckCircle,
    Clock,
    DollarSign,
} from 'lucide-react';

import { index as blogIndex } from '@/actions/App/Http/Controllers/BlogController';
import productIndex from '@/actions/App/Http/Controllers/ProductController';
import serviceIndex from '@/actions/App/Http/Controllers/ServiceController';
import customerIndex from '@/actions/App/Http/Controllers/CustomerController';
import userIndex from '@/actions/App/Http/Controllers/UserController';
import bookingIndex from '@/actions/App/Http/Controllers/BookingController';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
];

interface Stats {
    products: { total: number; active: number; out_of_stock: number };
    services: { total: number; free: number; paid: number };
    blogs: { total: number; published: number; drafts: number };
    customers: { total: number; active: number; verified: number };
    users: { total: number; verified: number };
    bookings: { total: number; new: number; pending: number; completed: number; revenue: number };
}

interface RecentBooking {
    id: number;
    booking_id: string;
    full_name: string;
    service_id: string;
    booking_status: string;
    payment_status: string;
    service_price: number;
    created_at: string;
}

interface RecentCustomer {
    id: number;
    name: string;
    email: string;
    is_active: boolean;
    created_at: string;
}

interface DashboardProps {
    stats: Stats;
    recentBookings: RecentBooking[];
    recentCustomers: RecentCustomer[];
}

const statusBadge = (status: string) => {
    const map: Record<string, string> = {
        new: 'bg-blue-100 text-blue-800',
        confirmed: 'bg-indigo-100 text-indigo-800',
        in_progress: 'bg-yellow-100 text-yellow-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
        pending: 'bg-yellow-100 text-yellow-800',
        paid: 'bg-green-100 text-green-800',
        failed: 'bg-red-100 text-red-800',
    };
    return map[status] || 'bg-gray-100 text-gray-800';
};

export default function Dashboard({ stats, recentBookings, recentCustomers }: DashboardProps) {
    const cards = [
        {
            title: 'Products',
            total: stats.products.total,
            subtitle: `${stats.products.active} active`,
            alert: stats.products.out_of_stock > 0 ? `${stats.products.out_of_stock} out of stock` : null,
            icon: PackageSearch,
            href: productIndex.index().url,
            color: 'from-blue-500 to-blue-600',
            lightColor: 'bg-blue-50',
            iconColor: 'text-blue-600',
        },
        {
            title: 'Services',
            total: stats.services.total,
            subtitle: `${stats.services.free} free, ${stats.services.paid} paid`,
            alert: null,
            icon: Kanban,
            href: serviceIndex.index().url,
            color: 'from-purple-500 to-purple-600',
            lightColor: 'bg-purple-50',
            iconColor: 'text-purple-600',
        },
        {
            title: 'Blog Posts',
            total: stats.blogs.total,
            subtitle: `${stats.blogs.published} published`,
            alert: stats.blogs.drafts > 0 ? `${stats.blogs.drafts} drafts` : null,
            icon: Rss,
            href: blogIndex().url,
            color: 'from-orange-500 to-orange-600',
            lightColor: 'bg-orange-50',
            iconColor: 'text-orange-600',
        },
        {
            title: 'Customers',
            total: stats.customers.total,
            subtitle: `${stats.customers.active} active`,
            alert: null,
            icon: User,
            href: customerIndex.index().url,
            color: 'from-teal-500 to-teal-600',
            lightColor: 'bg-teal-50',
            iconColor: 'text-teal-600',
        },
        {
            title: 'Admin Users',
            total: stats.users.total,
            subtitle: `${stats.users.verified} verified`,
            alert: null,
            icon: Users,
            href: userIndex.index().url,
            color: 'from-indigo-500 to-indigo-600',
            lightColor: 'bg-indigo-50',
            iconColor: 'text-indigo-600',
        },
        {
            title: 'Bookings',
            total: stats.bookings.total,
            subtitle: `${stats.bookings.completed} completed`,
            alert: stats.bookings.pending > 0 ? `${stats.bookings.pending} pending payment` : null,
            icon: Book,
            href: bookingIndex.index().url,
            color: 'from-rose-500 to-rose-600',
            lightColor: 'bg-rose-50',
            iconColor: 'text-rose-600',
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full">

                    {/* Page Header */}
                    <div>
                        <h2 className="text-2xl font-bold text-gray-800">Dashboard</h2>
                        <p className="text-sm text-gray-500 mt-1">Overview of your platform at a glance</p>
                    </div>

                    {/* Stats Cards */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        {cards.map((card) => {
                            const Icon = card.icon;
                            return (
                                <Link
                                    key={card.title}
                                    href={card.href}
                                    className="group block p-6 border rounded-xl bg-white shadow-xl hover:shadow-2xl transition-all duration-200 hover:-translate-y-0.5"
                                >
                                    <div className="flex items-start justify-between">
                                        <div>
                                            <p className="text-sm font-medium text-gray-500">{card.title}</p>
                                            <p className="text-3xl font-bold text-gray-900 mt-1">{card.total}</p>
                                            <p className="text-xs text-gray-500 mt-1">{card.subtitle}</p>
                                        </div>
                                        <div className={`${card.lightColor} p-3 rounded-lg`}>
                                            <Icon className={`h-6 w-6 ${card.iconColor}`} />
                                        </div>
                                    </div>
                                    {card.alert && (
                                        <div className="mt-3 flex items-center gap-1 text-xs text-amber-700 bg-amber-50 rounded-md px-2 py-1">
                                            <AlertCircle className="h-3 w-3" />
                                            {card.alert}
                                        </div>
                                    )}
                                    <div className="mt-4 flex items-center text-xs font-medium text-gray-400 group-hover:text-blue-600 transition-colors">
                                        View details
                                        <ArrowRight className="h-3 w-3 ml-1 group-hover:translate-x-0.5 transition-transform" />
                                    </div>
                                </Link>
                            );
                        })}
                    </div>

                    {/* Quick Summary Bar */}
                    <div className="p-4 border rounded-xl bg-white shadow-xl">
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div className="flex items-center gap-3">
                                <div className="bg-green-50 p-2 rounded-lg">
                                    <CheckCircle className="h-5 w-5 text-green-600" />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-500">Completed</p>
                                    <p className="text-lg font-bold text-gray-900">{stats.bookings.completed}</p>
                                </div>
                            </div>
                            <div className="flex items-center gap-3">
                                <div className="bg-yellow-50 p-2 rounded-lg">
                                    <Clock className="h-5 w-5 text-yellow-600" />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-500">Pending Payment</p>
                                    <p className="text-lg font-bold text-gray-900">{stats.bookings.pending}</p>
                                </div>
                            </div>
                            <div className="flex items-center gap-3">
                                <div className="bg-blue-50 p-2 rounded-lg">
                                    <TrendingUp className="h-5 w-5 text-blue-600" />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-500">New Bookings</p>
                                    <p className="text-lg font-bold text-gray-900">{stats.bookings.new}</p>
                                </div>
                            </div>
                            <div className="flex items-center gap-3">
                                <div className="bg-emerald-50 p-2 rounded-lg">
                                    <DollarSign className="h-5 w-5 text-emerald-600" />
                                </div>
                                <div>
                                    <p className="text-xs text-gray-500">Total Revenue</p>
                                    <p className="text-lg font-bold text-gray-900">£{Number(stats.bookings.revenue).toFixed(2)}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Recent Activity */}
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {/* Recent Bookings */}
                        <div className="p-6 border rounded-xl bg-white shadow-xl">
                            <div className="flex items-center justify-between mb-4">
                                <h3 className="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                                <Link
                                    href={bookingIndex.index().url}
                                    className="text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center"
                                >
                                    View all <ArrowRight className="h-3 w-3 ml-1" />
                                </Link>
                            </div>
                            {recentBookings.length > 0 ? (
                                <div className="space-y-3">
                                    {recentBookings.map((booking) => (
                                        <div key={booking.id} className="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div className="flex-1 min-w-0">
                                                <div className="flex items-center gap-2">
                                                    <span className="font-semibold text-sm text-gray-900 truncate">{booking.full_name}</span>
                                                    <span className={`inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium ${statusBadge(booking.booking_status)}`}>
                                                        {booking.booking_status.replace('_', ' ')}
                                                    </span>
                                                </div>
                                                <p className="text-xs text-gray-500 mt-0.5">{booking.booking_id} &middot; {booking.service_id} &middot; {booking.created_at}</p>
                                            </div>
                                            <span className="text-sm font-semibold text-gray-700 ml-3">£{Number(booking.service_price).toFixed(2)}</span>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <p className="text-sm text-gray-400 text-center py-6">No bookings yet.</p>
                            )}
                        </div>

                        {/* Recent Customers */}
                        <div className="p-6 border rounded-xl bg-white shadow-xl">
                            <div className="flex items-center justify-between mb-4">
                                <h3 className="text-lg font-semibold text-gray-800">Recent Customers</h3>
                                <Link
                                    href={customerIndex.index().url}
                                    className="text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center"
                                >
                                    View all <ArrowRight className="h-3 w-3 ml-1" />
                                </Link>
                            </div>
                            {recentCustomers.length > 0 ? (
                                <div className="space-y-3">
                                    {recentCustomers.map((customer) => (
                                        <div key={customer.id} className="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div className="flex items-center gap-3 min-w-0">
                                                <div className={`h-9 w-9 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0 ${customer.is_active ? 'bg-teal-600' : 'bg-gray-400'}`}>
                                                    {customer.name.charAt(0).toUpperCase()}
                                                </div>
                                                <div className="min-w-0">
                                                    <p className="text-sm font-semibold text-gray-900 truncate">{customer.name}</p>
                                                    <p className="text-xs text-gray-500 truncate">{customer.email}</p>
                                                </div>
                                            </div>
                                            <div className="text-right flex-shrink-0 ml-3">
                                                <span className={`inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium ${customer.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'}`}>
                                                    {customer.is_active ? 'Active' : 'Inactive'}
                                                </span>
                                                <p className="text-[10px] text-gray-400 mt-0.5">{customer.created_at}</p>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <p className="text-sm text-gray-400 text-center py-6">No customers yet.</p>
                            )}
                        </div>

                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
