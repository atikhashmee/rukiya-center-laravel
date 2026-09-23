import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
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
    CalendarDays,
} from 'lucide-react';
import { badgeClasses, statusClasses, statusLabel, tones, type Tone } from '@/lib/status';
import { useCan } from '@/lib/permissions';

import { index as blogIndex } from '@/actions/App/Http/Controllers/BlogController';
import productIndex from '@/actions/App/Http/Controllers/ProductController';
import serviceIndex from '@/actions/App/Http/Controllers/ServiceController';
import customerIndex from '@/actions/App/Http/Controllers/CustomerController';
import userIndex from '@/actions/App/Http/Controllers/UserController';
import bookingIndex from '@/actions/App/Http/Controllers/BookingController';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
];

// The server only sends the sections this user may view, so every key is optional.
interface Stats {
    products?: { total: number; active: number; out_of_stock: number };
    services?: { total: number; free: number; paid: number };
    blogs?: { total: number; published: number; drafts: number };
    customers?: { total: number; active: number; verified: number };
    users?: { total: number; verified: number };
    bookings?: { total: number; new: number; pending: number; completed: number; revenue: number };
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

const greeting = () => {
    const h = new Date().getHours();
    return h < 12 ? 'Good morning' : h < 18 ? 'Good afternoon' : 'Good evening';
};

export default function Dashboard({ stats, recentBookings, recentCustomers }: DashboardProps) {
    const { auth } = usePage<SharedData>().props;
    const firstName = auth?.user?.name?.split(' ')[0];

    const canViewBookings = useCan('bookings.view');
    const canViewCustomers = useCan('customers.view');

    // Only the sections present in `stats` get a card.
    const cards: { title: string; total: number; subtitle: string; alert: string | null; icon: typeof Book; href: string; tone: Tone }[] = [
        stats.products && {
            title: 'Products',
            total: stats.products.total,
            subtitle: `${stats.products.active} active`,
            alert: stats.products.out_of_stock > 0 ? `${stats.products.out_of_stock} out of stock` : null,
            icon: PackageSearch,
            href: productIndex.index().url,
            tone: 'info' as Tone,
        },
        stats.services && {
            title: 'Services',
            total: stats.services.total,
            subtitle: `${stats.services.free} free, ${stats.services.paid} paid`,
            alert: null,
            icon: Kanban,
            href: serviceIndex.index().url,
            tone: 'accent' as Tone,
        },
        stats.blogs && {
            title: 'Blog Posts',
            total: stats.blogs.total,
            subtitle: `${stats.blogs.published} published`,
            alert: stats.blogs.drafts > 0 ? `${stats.blogs.drafts} drafts` : null,
            icon: Rss,
            href: blogIndex().url,
            tone: 'warning' as Tone,
        },
        stats.customers && {
            title: 'Customers',
            total: stats.customers.total,
            subtitle: `${stats.customers.active} active`,
            alert: null,
            icon: User,
            href: customerIndex.index().url,
            tone: 'success' as Tone,
        },
        stats.users && {
            title: 'Admin Users',
            total: stats.users.total,
            subtitle: `${stats.users.verified} verified`,
            alert: null,
            icon: Users,
            href: userIndex.index().url,
            tone: 'neutral' as Tone,
        },
        stats.bookings && {
            title: 'Bookings',
            total: stats.bookings.total,
            subtitle: `${stats.bookings.completed} completed`,
            alert: stats.bookings.pending > 0 ? `${stats.bookings.pending} pending payment` : null,
            icon: Book,
            href: bookingIndex.index().url,
            tone: 'danger' as Tone,
        },
    ].filter((card) => card !== undefined);

    const bookingStats = stats.bookings;

    const summary: { label: string; value: React.ReactNode; icon: typeof Book; tone: Tone }[] = bookingStats
        ? [
              { label: 'Completed', value: bookingStats.completed, icon: CheckCircle, tone: 'success' },
              { label: 'Pending Payment', value: bookingStats.pending, icon: Clock, tone: 'warning' },
              { label: 'New Bookings', value: bookingStats.new, icon: TrendingUp, tone: 'info' },
              { label: 'Total Revenue', value: `£${Number(bookingStats.revenue).toFixed(2)}`, icon: DollarSign, tone: 'accent' },
          ]
        : [];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">

                {/* Hero */}
                <div className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary to-primary/80 p-6 text-primary-foreground shadow-sm md:p-8">
                    <div className="pointer-events-none absolute -top-16 -right-16 h-56 w-56 rounded-full bg-primary-foreground/10 blur-2xl" />
                    <div className="pointer-events-none absolute -bottom-20 right-24 h-40 w-40 rounded-full bg-primary-foreground/5 blur-2xl" />
                    <div className="relative flex flex-wrap items-end justify-between gap-6">
                        <div>
                            <p className="flex items-center gap-2 text-sm opacity-80">
                                <CalendarDays className="h-4 w-4" />
                                {new Date().toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}
                            </p>
                            <h1 className="mt-2 text-2xl font-semibold tracking-tight md:text-3xl">
                                {greeting()}{firstName ? `, ${firstName}` : ''}
                            </h1>
                            <p className="mt-1 text-sm opacity-80">Overview of your platform at a glance</p>
                        </div>
                        {bookingStats && (
                            <div className="flex gap-3">
                                <div className="rounded-xl bg-primary-foreground/10 px-4 py-3 ring-1 ring-primary-foreground/15 backdrop-blur-sm">
                                    <p className="text-xs opacity-80">Bookings</p>
                                    <p className="text-xl font-semibold">{bookingStats.total}</p>
                                </div>
                                <div className="rounded-xl bg-primary-foreground/10 px-4 py-3 ring-1 ring-primary-foreground/15 backdrop-blur-sm">
                                    <p className="text-xs opacity-80">Revenue</p>
                                    <p className="text-xl font-semibold">£{Number(bookingStats.revenue).toFixed(2)}</p>
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                {/* Stats Cards */}
                {cards.length > 0 && (
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {cards.map((card) => {
                        const Icon = card.icon;
                        return (
                            <Link
                                key={card.title}
                                href={card.href}
                                className="group flex flex-col rounded-xl border bg-card p-5 text-card-foreground shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                            >
                                <div className="flex items-start justify-between">
                                    <div>
                                        <p className="text-sm text-muted-foreground">{card.title}</p>
                                        <p className="mt-1 text-3xl font-semibold tracking-tight">{card.total}</p>
                                        <p className="mt-1 text-xs text-muted-foreground">{card.subtitle}</p>
                                    </div>
                                    <div className={`rounded-lg p-2.5 ring-1 ring-inset ${tones[card.tone]}`}>
                                        <Icon className="h-5 w-5" />
                                    </div>
                                </div>
                                {card.alert && (
                                    <div className={`mt-3 gap-1 self-start ${badgeClasses('warning')}`}>
                                        <AlertCircle className="h-3 w-3" />
                                        {card.alert}
                                    </div>
                                )}
                                <div className="mt-4 flex items-center border-t pt-3 text-xs font-medium text-muted-foreground transition-colors group-hover:text-primary">
                                    View details
                                    <ArrowRight className="ml-1 h-3 w-3 transition-transform group-hover:translate-x-0.5" />
                                </div>
                            </Link>
                        );
                    })}
                </div>
                )}

                {/* Quick Summary Bar */}
                {summary.length > 0 && (
                <div className="grid grid-cols-2 divide-border rounded-xl border bg-card text-card-foreground shadow-sm md:grid-cols-4 md:divide-x">
                    {summary.map(({ label, value, icon: Icon, tone }) => (
                        <div key={label} className="flex items-center gap-3 p-4">
                            <div className={`rounded-lg p-2 ${tones[tone]}`}>
                                <Icon className="h-5 w-5" />
                            </div>
                            <div>
                                <p className="text-xs text-muted-foreground">{label}</p>
                                <p className="text-lg font-semibold tracking-tight">{value}</p>
                            </div>
                        </div>
                    ))}
                </div>
                )}

                {/* Recent Activity */}
                <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">

                    {/* Recent Bookings */}
                    {(canViewBookings || recentBookings.length > 0) && (
                    <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div className="flex items-center justify-between border-b px-5 py-4">
                            <h2 className="font-semibold">Recent Bookings</h2>
                            <Link
                                href={bookingIndex.index().url}
                                className="inline-flex items-center text-xs font-medium text-primary hover:underline"
                            >
                                View all <ArrowRight className="ml-1 h-3 w-3" />
                            </Link>
                        </div>
                        {recentBookings.length > 0 ? (
                            <ul className="divide-y">
                                {recentBookings.map((booking) => (
                                    <li key={booking.id} className="flex items-center justify-between gap-3 px-5 py-3 transition-colors hover:bg-muted/40">
                                        <div className="min-w-0 flex-1">
                                            <div className="flex items-center gap-2">
                                                <span className="truncate text-sm font-medium">{booking.full_name}</span>
                                                <span className={statusClasses(booking.booking_status)}>
                                                    {statusLabel(booking.booking_status)}
                                                </span>
                                            </div>
                                            <p className="mt-0.5 text-xs text-muted-foreground">{booking.booking_id} &middot; {booking.service_id} &middot; {booking.created_at}</p>
                                        </div>
                                        <span className="text-sm font-semibold tabular-nums">£{Number(booking.service_price).toFixed(2)}</span>
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                <Book className="h-8 w-8 opacity-40" />
                                <p className="text-sm">No bookings yet.</p>
                            </div>
                        )}
                    </div>
                    )}

                    {/* Recent Customers */}
                    {(canViewCustomers || recentCustomers.length > 0) && (
                    <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div className="flex items-center justify-between border-b px-5 py-4">
                            <h2 className="font-semibold">Recent Customers</h2>
                            <Link
                                href={customerIndex.index().url}
                                className="inline-flex items-center text-xs font-medium text-primary hover:underline"
                            >
                                View all <ArrowRight className="ml-1 h-3 w-3" />
                            </Link>
                        </div>
                        {recentCustomers.length > 0 ? (
                            <ul className="divide-y">
                                {recentCustomers.map((customer) => (
                                    <li key={customer.id} className="flex items-center justify-between gap-3 px-5 py-3 transition-colors hover:bg-muted/40">
                                        <div className="flex min-w-0 items-center gap-3">
                                            <div className={`flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-sm font-semibold ${customer.is_active ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground'}`}>
                                                {customer.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div className="min-w-0">
                                                <p className="truncate text-sm font-medium">{customer.name}</p>
                                                <p className="truncate text-xs text-muted-foreground">{customer.email}</p>
                                            </div>
                                        </div>
                                        <div className="flex-shrink-0 text-right">
                                            <span className={statusClasses(customer.is_active ? 'active' : 'inactive')}>
                                                {customer.is_active ? 'Active' : 'Inactive'}
                                            </span>
                                            <p className="mt-0.5 text-[10px] text-muted-foreground">{customer.created_at}</p>
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                <User className="h-8 w-8 opacity-40" />
                                <p className="text-sm">No customers yet.</p>
                            </div>
                        )}
                    </div>
                    )}

                </div>
            </div>
        </AppLayout>
    );
}
