import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { NativeSelect, NativeSelectOption } from "@/components/ui/native-select";
import { Label } from "@/components/ui/label";
import { ArrowLeft, ShoppingBag, Mail, Phone, User } from 'lucide-react';
import PageHeader from '@/components/page-header';
import { statusClasses, statusLabel } from '@/lib/status';

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

const th = "text-xs font-medium uppercase tracking-wide text-muted-foreground";

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
            <div className="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={
                        <span className="flex flex-wrap items-center gap-3">
                            Order {order.order_number}
                            <span className={statusClasses(order.status)}>{statusLabel(order.status)}</span>
                            <span className={statusClasses(order.payment_status)}>{statusLabel(order.payment_status)}</span>
                        </span>
                    }
                    description={new Date(order.created_at).toLocaleString()}
                    actions={
                        <Button variant="outline" asChild>
                            <Link href="/admin/orders"><ArrowLeft className="h-4 w-4" /> Back to Orders</Link>
                        </Button>
                    }
                />

                <div className="grid gap-6 md:grid-cols-2">
                    <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div className="border-b px-5 py-4">
                            <h2 className="font-semibold">Customer Details</h2>
                        </div>
                        <div className="space-y-3 p-5 text-sm">
                            <p className="flex items-center gap-2"><User className="h-4 w-4 text-muted-foreground" /> <span className="font-medium">{order.full_name}</span></p>
                            <p className="flex items-center gap-2"><Mail className="h-4 w-4 text-muted-foreground" /> {order.email}</p>
                            {order.phone && <p className="flex items-center gap-2"><Phone className="h-4 w-4 text-muted-foreground" /> {order.phone}</p>}
                        </div>
                    </div>
                    <div className="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div className="border-b px-5 py-4">
                            <h2 className="font-semibold">Status</h2>
                        </div>
                        <div className="grid gap-4 p-5 sm:grid-cols-2">
                            <div className="grid gap-2">
                                <Label htmlFor="order_status">Order Status</Label>
                                <NativeSelect id="order_status" className="w-full" value={order.status} onChange={(e) => handleUpdate('status', e.target.value)}>
                                    {orderStatuses.map(s => (
                                        <NativeSelectOption key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' ')}</NativeSelectOption>
                                    ))}
                                </NativeSelect>
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="payment_status">Payment Status</Label>
                                <NativeSelect id="payment_status" className="w-full" value={order.payment_status} onChange={(e) => handleUpdate('payment_status', e.target.value)}>
                                    {paymentStatuses.map(s => (
                                        <NativeSelectOption key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' ')}</NativeSelectOption>
                                    ))}
                                </NativeSelect>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="overflow-hidden rounded-xl border bg-card text-card-foreground shadow-sm">
                    <div className="flex items-center gap-2 border-b px-5 py-4">
                        <ShoppingBag className="h-4 w-4 text-muted-foreground" />
                        <h2 className="font-semibold">Order Items</h2>
                    </div>
                    <div className="overflow-x-auto">
                        <Table>
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={`pl-5 ${th}`}>Product</TableHead>
                                    <TableHead className={`text-right ${th}`}>Price</TableHead>
                                    <TableHead className={`text-center ${th}`}>Qty</TableHead>
                                    <TableHead className={`pr-5 text-right ${th}`}>Subtotal</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {order.items.map((item) => (
                                    <TableRow key={item.id} className="hover:bg-muted/40">
                                        <TableCell className="pl-5 font-medium">{item.product_name}</TableCell>
                                        <TableCell className="text-right text-sm tabular-nums">£{Number(item.price).toFixed(2)}</TableCell>
                                        <TableCell className="text-center text-sm tabular-nums">{item.quantity}</TableCell>
                                        <TableCell className="pr-5 text-right font-semibold tabular-nums">£{Number(item.subtotal).toFixed(2)}</TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </div>

                    <div className="flex justify-end border-t bg-muted/30 p-5">
                        <div className="w-64 space-y-2 text-sm">
                            <div className="flex justify-between text-muted-foreground">
                                <span>Subtotal</span>
                                <span className="tabular-nums">£{Number(order.subtotal).toFixed(2)}</span>
                            </div>
                            <div className="flex justify-between text-muted-foreground italic">
                                <span>Shipping</span>
                                <span>Free</span>
                            </div>
                            <div className="flex justify-between border-t pt-2 text-lg font-semibold">
                                <span>Total</span>
                                <span className="tabular-nums">£{Number(order.total).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
