import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Eye } from 'lucide-react';
import Pagination from '@/components/pagination';
import FilterBar from '@/components/filter-bar';

type OrderStatus = 'pending' | 'paid' | 'processing' | 'completed' | 'cancelled';
type PaymentStatus = 'pending' | 'paid' | 'failed';

interface Order {
    id: number;
    order_number: string;
    email: string;
    full_name: string;
    total: number;
    status: OrderStatus;
    payment_status: PaymentStatus;
    items_count: number;
    created_at: string;
}

interface PaginatedOrders {
    data: Order[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

interface Props {
    orders: PaginatedOrders;
    orderStatuses: OrderStatus[];
    paymentStatuses: PaymentStatus[];
    filters: Record<string, string>;
}

const getStatusColor = (status: string) => {
    switch (status) {
        case 'completed': return 'bg-green-100 text-green-800 border-green-300';
        case 'processing':
        case 'paid': return 'bg-indigo-100 text-indigo-800 border-indigo-300';
        case 'cancelled':
        case 'failed': return 'bg-red-100 text-red-800 border-red-300';
        case 'pending':
        default: return 'bg-yellow-100 text-yellow-800 border-yellow-300';
    }
};

export default function Index({ orders, orderStatuses, paymentStatuses, filters }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/admin/dashboard' },
        { title: 'Orders', href: '/admin/orders' },
    ];

    const handleStatusUpdate = (order: Order, field: 'status' | 'payment_status', value: string) => {
        router.put(`/admin/orders/${order.id}`, {
            status: order.status,
            payment_status: order.payment_status,
            [field]: value,
        }, { preserveScroll: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Orders Management" />
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-800">Order Management</h2>
                        <p className="text-sm text-gray-500 mt-1">{orders.total} total orders</p>
                    </div>

                    <FilterBar
                        filters={filters}
                        placeholder="Search by order #, email, or name..."
                        baseUrl="/admin/orders"
                        filterConfigs={[
                            {
                                key: 'status',
                                label: 'All Order Status',
                                options: orderStatuses.map(s => ({
                                    label: s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' '),
                                    value: s,
                                })),
                            },
                            {
                                key: 'payment_status',
                                label: 'All Payment Status',
                                options: paymentStatuses.map(s => ({
                                    label: s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' '),
                                    value: s,
                                })),
                            },
                        ]}
                    />

                    <div className="p-3 border rounded-xl bg-white shadow-xl overflow-x-auto">
                        <Table>
                            <TableHeader className="bg-gray-100/70">
                                <TableRow>
                                    <TableHead className="w-[160px]">Order #</TableHead>
                                    <TableHead>Customer</TableHead>
                                    <TableHead className="text-center">Items</TableHead>
                                    <TableHead className="text-center">Status</TableHead>
                                    <TableHead className="text-center">Payment</TableHead>
                                    <TableHead className="text-right">Total</TableHead>
                                    <TableHead className="text-center">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {orders.data.length > 0 ? (
                                    orders.data.map((order) => (
                                        <TableRow key={order.id} className="hover:bg-gray-50">
                                            <TableCell>
                                                <span className="font-semibold text-blue-700">{order.order_number}</span>
                                            </TableCell>
                                            <TableCell>
                                                <div className="font-medium text-gray-800">{order.full_name}</div>
                                                <div className="text-xs text-gray-500">{order.email}</div>
                                            </TableCell>
                                            <TableCell className="text-center text-sm text-gray-600">{order.items_count}</TableCell>
                                            <TableCell className="text-center">
                                                <select
                                                    value={order.status}
                                                    onChange={(e) => handleStatusUpdate(order, 'status', e.target.value)}
                                                    className={`rounded-md text-xs font-medium border p-1 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 ${getStatusColor(order.status)}`}
                                                >
                                                    {orderStatuses.map(s => (
                                                        <option key={s} value={s} className="bg-white text-gray-900">
                                                            {s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' ')}
                                                        </option>
                                                    ))}
                                                </select>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <select
                                                    value={order.payment_status}
                                                    onChange={(e) => handleStatusUpdate(order, 'payment_status', e.target.value)}
                                                    className={`rounded-md text-xs font-medium border p-1 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 ${getStatusColor(order.payment_status)}`}
                                                >
                                                    {paymentStatuses.map(s => (
                                                        <option key={s} value={s} className="bg-white text-gray-900">
                                                            {s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' ')}
                                                        </option>
                                                    ))}
                                                </select>
                                            </TableCell>
                                            <TableCell className="text-right font-semibold text-sm">£{Number(order.total).toFixed(2)}</TableCell>
                                            <TableCell className="text-center">
                                                <Link href={`/admin/orders/${order.id}`}>
                                                    <Button variant="outline" size="icon" className="h-8 w-8 text-blue-600 border-blue-300" title="View Order">
                                                        <Eye className="h-4 w-4" />
                                                    </Button>
                                                </Link>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow>
                                        <TableCell colSpan={7} className="h-24 text-center text-gray-400">
                                            No orders found matching your filters.
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                        <Pagination links={orders.links} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
