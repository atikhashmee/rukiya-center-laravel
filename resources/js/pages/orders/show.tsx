import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { CornerUpLeft, ShoppingBag, Mail } from 'lucide-react';

interface OrderItem {
    id: number;
    product_name: string;
    price: number;
    quantity: number;
    subtotal: number;
}

interface Order {
    id: number;
    order_number: string;
    email: string;
    full_name: string;
    phone: string | null;
    subtotal: number;
    total: number;
    status: string;
    payment_status: string;
    items: OrderItem[];
    created_at: string;
}

interface Props {
    order: Order;
    orderStatuses: string[];
    paymentStatuses: string[];
}

const getStatusColor = (status: string) => {
    switch (status) {
        case 'completed': return 'bg-green-100 text-green-800';
        case 'processing':
        case 'paid': return 'bg-indigo-100 text-indigo-800';
        case 'cancelled':
        case 'failed': return 'bg-red-100 text-red-800';
        case 'pending':
        default: return 'bg-yellow-100 text-yellow-800';
    }
};

export default function Show({ order, orderStatuses, paymentStatuses }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/admin/dashboard' },
        { title: 'Orders', href: '/admin/orders' },
        { title: order.order_number, href: '#' },
    ];

    const handleUpdate = (field: string, value: string) => {
        router.put(`/admin/orders/${order.id}`, {
            status: field === 'status' ? value : order.status,
            payment_status: field === 'payment_status' ? value : order.payment_status,
        }, { preserveScroll: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Order ${order.order_number}`} />
            <div className="container py-4 pl-4">
                <div className="max-w-4xl mx-auto">
                    <Link href="/admin/orders"
                        className="inline-flex items-center gap-2 text-sm text-gray-600 border border-gray-300 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 mb-6">
                        <CornerUpLeft className="h-4 w-4" /> Back to Orders
                    </Link>

                    <div className="bg-white border rounded-xl shadow-xl p-6 mb-6">
                        <div className="flex items-center justify-between mb-6">
                            <div>
                                <h2 className="text-xl font-bold text-gray-800">Order {order.order_number}</h2>
                                <p className="text-sm text-gray-500">{new Date(order.created_at).toLocaleString()}</p>
                            </div>
                            <div className="flex items-center gap-3">
                                <span className={`inline-flex items-center rounded-full px-3 py-1 text-xs font-medium border ${getStatusColor(order.status)}`}>
                                    {order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                </span>
                                <span className={`inline-flex items-center rounded-full px-3 py-1 text-xs font-medium border ${getStatusColor(order.payment_status)}`}>
                                    {order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1).replace('_', ' ')}
                                </span>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div className="p-4 bg-gray-50 rounded-lg">
                                <h3 className="text-sm font-semibold text-gray-700 mb-2">Customer Details</h3>
                                <p className="text-sm text-gray-600">{order.full_name}</p>
                                <p className="text-sm text-gray-600">{order.email}</p>
                                {order.phone && <p className="text-sm text-gray-600">{order.phone}</p>}
                            </div>
                            <div className="p-4 bg-gray-50 rounded-lg">
                                <h3 className="text-sm font-semibold text-gray-700 mb-2">Status</h3>
                                <div className="space-y-2">
                                    <div className="flex items-center gap-2">
                                        <label className="text-xs text-gray-500 w-28">Order Status:</label>
                                        <select value={order.status} onChange={(e) => handleUpdate('status', e.target.value)}
                                            className="rounded-md text-xs font-medium border p-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            {orderStatuses.map(s => (
                                                <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' ')}</option>
                                            ))}
                                        </select>
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <label className="text-xs text-gray-500 w-28">Payment Status:</label>
                                        <select value={order.payment_status} onChange={(e) => handleUpdate('payment_status', e.target.value)}
                                            className="rounded-md text-xs font-medium border p-1 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            {paymentStatuses.map(s => (
                                                <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' ')}</option>
                                            ))}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 className="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <ShoppingBag className="h-5 w-5" /> Order Items
                        </h3>
                        <Table>
                            <TableHeader className="bg-gray-100/70">
                                <TableRow>
                                    <TableHead>Product</TableHead>
                                    <TableHead className="text-right">Price</TableHead>
                                    <TableHead className="text-center">Qty</TableHead>
                                    <TableHead className="text-right">Subtotal</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {order.items.map((item) => (
                                    <TableRow key={item.id}>
                                        <TableCell className="font-medium text-gray-800">{item.product_name}</TableCell>
                                        <TableCell className="text-right text-sm">£{Number(item.price).toFixed(2)}</TableCell>
                                        <TableCell className="text-center text-sm">{item.quantity}</TableCell>
                                        <TableCell className="text-right font-semibold">£{Number(item.subtotal).toFixed(2)}</TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>

                        <div className="flex justify-end mt-4">
                            <div className="w-64 space-y-2 text-sm">
                                <div className="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span>£{Number(order.subtotal).toFixed(2)}</span>
                                </div>
                                <div className="flex justify-between text-gray-400 italic">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </div>
                                <div className="flex justify-between text-lg font-bold text-gray-800 border-t pt-2">
                                    <span>Total</span>
                                    <span>£{Number(order.total).toFixed(2)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
