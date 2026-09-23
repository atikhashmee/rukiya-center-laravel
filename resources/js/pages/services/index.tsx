import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, usePage } from '@inertiajs/react';
import { BreadcrumbItem} from "@/types";
import { dashboard } from '@/routes';
import { index, create, destroy } from "@/actions/App/Http/Controllers/ServiceController";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Pencil, Trash2, Plus, DollarSign, Gift, Heart, ShieldQuestion, AlertTriangle, CheckCircle, XCircle, Layers } from 'lucide-react';
import Pagination from '@/components/pagination';
import { edit } from '@/routes/services';
import FilterBar from '@/components/filter-bar';
import PageHeader from '@/components/page-header';
import { badgeClasses } from '@/lib/status';
import { useCan } from '@/lib/permissions';

type PriceType = 'FREE' | 'DONATION' | 'FIXED' | 'RESERVATION';

interface ServiceOption {
    id: number;
    serviceId: number;
    id_code: string;
    category: { id: number; name: string; slug: string } | null;
    title: string;
    tagline: string;
    card_color: string;
    order: number;
    price_type: PriceType;
    price_value: number | null;
    min_donation: number | null;
    requires_custom_assessment: boolean;
}

interface PaginatedServices {
    data: ServiceOption[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

interface ServiceOptionsIndexProps {
    services: PaginatedServices;
    categories: { id: number; name: string }[];
    filters: Record<string, string>;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Services', href: index().url },
];

const getPriceDisplay = (option: ServiceOption) => {
    switch (option.price_type) {
        case 'FREE':
            return <span className={badgeClasses('success')}><Gift className="mr-1 h-3 w-3" /> Free</span>;
        case 'DONATION':
            return <span className={badgeClasses('warning')}><Heart className="mr-1 h-3 w-3" /> Min. £{option.min_donation || '0.00'}</span>;
        case 'FIXED':
            return <span className={badgeClasses('info')}><DollarSign className="mr-1 h-3 w-3" /> £{option.price_value}</span>;
        case 'RESERVATION':
            return <span className={badgeClasses('accent')}><ShieldQuestion className="mr-1 h-3 w-3" /> Reservation</span>;
        default:
            return option.price_type;
    }
};

export default function Index({ services, categories, filters }: ServiceOptionsIndexProps) {
    const { flash } = usePage().props as any;
    const canManage = useCan('services.manage');

    const handleDelete = (optionId: number, title: string) => {
        if (window.confirm(`Are you sure you want to delete the option: "${title}"? This is permanent.`)) {
             router.delete(destroy(optionId), {
                onSuccess: () => {},
                onError: (errors: any) => console.error("Deletion failed:", errors),
            });
        }
    };

    const th = "text-xs font-medium uppercase tracking-wide text-muted-foreground";

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Services" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                {flash?.success && (
                    <div className="flex items-center gap-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-700 dark:text-emerald-300">
                        <CheckCircle className="h-4 w-4 shrink-0" />
                        <p>{flash.success}</p>
                    </div>
                )}

                {flash?.error && (
                    <div className="flex items-center gap-3 rounded-lg border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm font-medium text-rose-700 dark:text-rose-300">
                        <XCircle className="h-4 w-4 shrink-0" />
                        <p>{flash.error}</p>
                    </div>
                )}

                <PageHeader
                    title="Service Management"
                    description={`${services.total} total services`}
                    actions={canManage && (
                        <Button asChild>
                            <a href={create().url}>
                                <Plus className="h-4 w-4" /> Create New Option
                            </a>
                        </Button>
                    )}
                />

                <FilterBar
                    filters={filters}
                    placeholder="Search by title, tagline, or code..."
                    baseUrl={index().url}
                    filterConfigs={[
                        {
                            key: 'price_type',
                            label: 'All Price Types',
                            options: [
                                { label: 'Free', value: 'FREE' },
                                { label: 'Donation', value: 'DONATION' },
                                { label: 'Fixed Price', value: 'FIXED' },
                                { label: 'Reservation', value: 'RESERVATION' },
                            ],
                        },
                        {
                            key: 'category',
                            label: 'All Categories',
                            options: categories.map(c => ({ label: c.name, value: String(c.id) })),
                        },
                        {
                            key: 'assessment',
                            label: 'Assessment',
                            options: [
                                { label: 'Required', value: 'required' },
                                { label: 'Standard', value: 'standard' },
                            ],
                        },
                    ]}
                />

                <div className="overflow-hidden rounded-xl border bg-card shadow-sm">
                    <div className="overflow-x-auto">
                        <Table>
                            <TableHeader className="bg-muted/50">
                                <TableRow className="hover:bg-transparent">
                                    <TableHead className={`w-[60px] ${th}`}>Order</TableHead>
                                    <TableHead className={`w-[150px] ${th}`}>ID Code</TableHead>
                                    <TableHead className={th}>Title & Tagline</TableHead>
                                    <TableHead className={`text-center ${th}`}>Pricing</TableHead>
                                    <TableHead className={`text-center ${th}`}>Assessment</TableHead>
                                    <TableHead className={`text-center ${th}`}>Category</TableHead>
                                    <TableHead className={`w-[120px] text-right ${th}`}>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {services.data.length > 0 ? (
                                    services.data.map((option) => (
                                        <TableRow
                                            key={option.id}
                                            className={`border-l-4 hover:bg-muted/40 ${option.card_color.replace('border-l-', 'border-')}`}
                                        >
                                            <TableCell className="font-semibold tabular-nums">{option.order}</TableCell>
                                            <TableCell className="font-mono text-xs text-muted-foreground">{option.id_code}</TableCell>
                                            <TableCell>
                                                <div className="font-medium">{option.title}</div>
                                                <div className="text-xs text-muted-foreground">{option.tagline}</div>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                {getPriceDisplay(option)}
                                            </TableCell>
                                            <TableCell className="text-center">
                                                {option.requires_custom_assessment ? (
                                                    <span className={badgeClasses('danger')}>
                                                        <AlertTriangle className="mr-1 h-3 w-3" /> Required
                                                    </span>
                                                ) : (
                                                    <span className={badgeClasses('success')}>Standard</span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className={badgeClasses('neutral')}>
                                                    {option.category?.name ?? 'Uncategorized'}
                                                </span>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex justify-end gap-1">
                                                    {canManage && (
                                                        <>
                                                            <Button
                                                                variant="ghost"
                                                                size="icon"
                                                                className="h-8 w-8"
                                                                onClick={() => router.visit(edit(option.id).url)}
                                                            >
                                                                <Pencil className="h-4 w-4" />
                                                            </Button>
                                                            <Button
                                                                variant="ghost"
                                                                size="icon"
                                                                className="h-8 w-8 text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                                onClick={() => handleDelete(option.id, option.title)}
                                                            >
                                                                <Trash2 className="h-4 w-4" />
                                                            </Button>
                                                        </>
                                                    )}
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow className="hover:bg-transparent">
                                        <TableCell colSpan={7}>
                                            <div className="flex flex-col items-center gap-2 py-12 text-center text-muted-foreground">
                                                <Layers className="h-8 w-8 opacity-40" />
                                                <p className="text-sm">No services found matching your filters.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </div>
                    <Pagination links={services.links} />
                </div>
            </div>
        </AppLayout>
    );
}
