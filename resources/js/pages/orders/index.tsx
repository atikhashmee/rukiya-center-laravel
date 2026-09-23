import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Eye, ShoppingBag } from 'lucide-react';
import Pagination from '@/components/pagination';
import FilterBar from '@/components/filter-bar';
import PageHeader from '@/components/page-header';
import { statusClasses, statusLabel } from '@/lib/status';
import { useCan } from '@/lib/permissions';

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

const th = "text-xs font-medium uppercase tracking-wide text-muted-foreground";
const selectClasses = (status: string) =>
    `${statusClasses(status)} cursor-pointer appearance-none pr-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-ring`;

export default function Index({ orders, orderStatuses, paymentStatuses, filters }: Props) {
    const canManage = useCan('orders.manage');
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
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader title="Order Management" description={`${orders.total} total orders`} />

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

                <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                    <div className="overflow-x-auto">
                        <Table>
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={`w-[160px] ${th}`}>Order #</TableHead>
                                    <TableHead className={th}>Customer</TableHead>
                                    <TableHead className={`text-center ${th}`}>Items</TableHead>
                                    <TableHead className={`text-center ${th}`}>Status</TableHead>
                                    <TableHead className={`text-center ${th}`}>Payment</TableHead>
                                    <TableHead className={`text-right ${th}`}>Total</TableHead>
                                    <TableHead className={`text-center ${th}`}>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {orders.data.length > 0 ? (
                                    orders.data.map((order) => (
                                        <TableRow key={order.id} className="hover:bg-muted/40">
                                            <TableCell>
                                                <Link href={`/admin/orders/${order.id}`} className="font-medium text-primary hover:underline">
                                                    {order.order_number}
                                                </Link>
                                            </TableCell>
                                            <TableCell>
                                                <div className="font-medium">{order.full_name}</div>
                                                <div className="text-xs text-muted-foreground">{order.email}</div>
                                            </TableCell>
                                            <TableCell className="text-center text-sm text-muted-foreground tabular-nums">{order.items_count}</TableCell>
                                            <TableCell className="text-center">
                                                {canManage ? (
                                                    <select
                                                        value={order.status}
                                                        onChange={(e) => handleStatusUpdate(order, 'status', e.target.value)}
                                                        className={selectClasses(order.status)}
                                                    >
                                                        {orderStatuses.map(s => (
                                                            <option key={s} value={s} className="bg-background text-foreground">
                                                                {statusLabel(s)}
                                                            </option>
                                                        ))}
                                                    </select>
                                                ) : (
                                                    <span className={statusClasses(order.status)}>{statusLabel(order.status)}</span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-center">
                                                {canManage ? (
                                                    <select
                                                        value={order.payment_status}
                                                        onChange={(e) => handleStatusUpdate(order, 'payment_status', e.target.value)}
                                                        className={selectClasses(order.payment_status)}
                                                    >
                                                        {paymentStatuses.map(s => (
                                                            <option key={s} value={s} className="bg-background text-foreground">
                                                                {statusLabel(s)}
                                                            </option>
                                                        ))}
                                                    </select>
                                                ) : (
                                                    <span className={statusClasses(order.payment_status)}>{statusLabel(order.payment_status)}</span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-right text-sm font-semibold tabular-nums">£{Number(order.total).toFixed(2)}</TableCell>
                                            <TableCell className="text-center">
                                                <Button variant="ghost" size="icon" className="h-8 w-8" title="View Order" asChild>
                                                    <Link href={`/admin/orders/${order.id}`}>
                                                        <Eye className="h-4 w-4" />
                                                    </Link>
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow className="hover:bg-transparent">
                                        <TableCell colSpan={7}>
                                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                                <ShoppingBag className="h-8 w-8 opacity-40" />
                                                <p className="text-sm">No orders found matching your filters.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </div>
                    <Pagination links={orders.links} />
                </div>
            </div>
        </AppLayout>
    );
}
