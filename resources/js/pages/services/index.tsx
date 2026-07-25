import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, router, usePage } from '@inertiajs/react';
const Link: React.FC<any> = ({ children, href, className, ...props }) => <a href={href} className={className} {...props}>{children}</a>;
import { BreadcrumbItem} from "@/types";
import { dashboard } from '@/routes';
import { store, index, create, destroy } from "@/actions/App/Http/Controllers/ServiceController";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableCaption } from "@/components/ui/table";
import { Pencil, Trash2, PlusCircle, DollarSign, Gift, Heart, ShieldQuestion, AlertTriangle, CheckCircle, XCircle  } from 'lucide-react';
import Pagination from '@/components/pagination';
import { edit } from '@/routes/services';
import FilterBar from '@/components/filter-bar';

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
            return <div className="flex items-center text-green-600 font-semibold text-sm"><Gift className="h-3 w-3 mr-1" /> Free</div>;
        case 'DONATION':
            return <div className="flex items-center text-yellow-600 font-semibold text-sm"><Heart className="h-3 w-3 mr-1" /> Min. £{option.min_donation || '0.00'}</div>;
        case 'FIXED':
            return <div className="flex items-center text-indigo-600 font-semibold text-sm"><DollarSign className="h-3 w-3 mr-1" /> £{option.price_value}</div>;
        case 'RESERVATION':
            return <div className="flex items-center text-red-600 font-semibold text-sm"><ShieldQuestion className="h-3 w-3 mr-1" /> Reservation</div>;
        default:
            return option.price_type;
    }
};

export default function Index({ services, categories, filters }: ServiceOptionsIndexProps) {
    const { flash } = usePage().props as any;

    const handleDelete = (optionId: number, title: string) => {
        if (window.confirm(`Are you sure you want to delete the option: "${title}"? This is permanent.`)) {
             router.delete(destroy(optionId), {
                onSuccess: () => {},
                onError: (errors: any) => console.error("Deletion failed:", errors),
            });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Services" />
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full">
                    {flash?.success && (
                        <div className="flex items-center gap-3 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm">
                            <CheckCircle className="h-5 w-5 text-green-600" />
                            <p className="text-sm font-medium text-green-800">{flash.success}</p>
                        </div>
                    )}

                    {flash?.error && (
                        <div className="flex items-center gap-3 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm">
                            <XCircle className="h-5 w-5 text-red-600" />
                            <p className="text-sm font-medium text-red-800">{flash.error}</p>
                        </div>
                    )}

                    <div className="flex justify-between items-center mb-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-800">Service Management</h2>
                            <p className="text-sm text-gray-500 mt-1">{services.total} total services</p>
                        </div>
                        <Link
                            href={create().url}
                            className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center shadow-sm"
                        >
                            <PlusCircle className="mr-2 h-4 w-4" />
                            Create New Option
                        </Link>
                    </div>

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

                    <div className="p-3 border rounded-xl bg-white shadow-xl overflow-x-auto">
                        <Table className="min-w-full">
                            <TableCaption>List of available services</TableCaption>
                            <TableHeader className="bg-gray-100/70">
                                <TableRow className="hover:bg-gray-100/70">
                                    <TableHead className="w-[50px] font-bold text-gray-700">Order</TableHead>
                                    <TableHead className="w-[150px] font-bold text-gray-700">ID Code</TableHead>
                                    <TableHead className="font-bold text-gray-700">Title & Tagline</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Pricing</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Assessment</TableHead>
                                    <TableHead className="text-center font-bold text-gray-700">Category</TableHead>
                                    <TableHead className="text-center w-[150px] font-bold text-gray-700">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {services.data.length > 0 ? (
                                    services.data.map((option) => (
                                        <TableRow
                                            key={option.id}
                                            className={`hover:bg-gray-50 transition-colors border-l-4 ${option.card_color.replace('border-l-', 'border-')}`}
                                        >
                                            <TableCell className="font-bold text-lg">{option.order}</TableCell>
                                            <TableCell className="font-mono text-xs text-gray-500">{option.id_code}</TableCell>
                                            <TableCell>
                                                <div className="font-semibold text-base text-gray-800">{option.title}</div>
                                                <div className="text-sm text-gray-500">{option.tagline}</div>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                {getPriceDisplay(option)}
                                            </TableCell>
                                            <TableCell className="text-center">
                                                {option.requires_custom_assessment ? (
                                                    <span className="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 shadow-sm">
                                                        <AlertTriangle className="h-3 w-3 mr-1" /> Required
                                                    </span>
                                                ) : (
                                                    <span className="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                        Standard
                                                    </span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className="text-xs font-medium text-gray-700 bg-gray-200 px-2 py-0.5 rounded-full shadow-inner">
                                                    {option.category?.name ?? 'Uncategorized'}
                                                </span>
                                            </TableCell>
                                            <TableCell className="text-center flex space-x-2 justify-center">
                                                <Button
                                                    variant="outline"
                                                    size="icon"
                                                    className="h-8 w-8 hover:bg-indigo-100 border-indigo-300 text-indigo-600 transition-transform hover:scale-105"
                                                    onClick={() => router.visit(edit(option.id).url)}
                                                >
                                                    <Pencil className="h-4 w-4" />
                                                </Button>
                                                <Button
                                                    variant="destructive"
                                                    size="icon"
                                                    className="h-8 w-8 bg-red-600 hover:bg-red-700 transition-transform hover:scale-105"
                                                    onClick={() => handleDelete(option.id, option.title)}
                                                >
                                                    <Trash2 className="h-4 w-4" />
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow>
                                        <TableCell colSpan={7} className="h-24 text-center text-gray-400">
                                            No services found matching your filters.
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                        <Pagination links={services.links} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
