import React, { useEffect } from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, useForm, router, usePage } from '@inertiajs/react';
const Link: React.FC<any> = ({ children, href, className, ...props }) => <a href={href} className={className} {...props}>{children}</a>;
import { BreadcrumbItem} from "@/types";
import { dashboard } from '@/routes';
import { store, index, create, destroy } from "@/actions/App/Http/Controllers/ServiceController";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableCaption } from "@/components/ui/table";
import { Pencil, Trash2, PlusCircle, CornerUpLeft, DollarSign, Gift, Heart, ShieldQuestion, AlertTriangle, CheckCircle, XCircle  } from 'lucide-react';
import Pagination from '@/components/pagination';
import { edit } from '@/routes/services';


type PriceType = 'FREE' | 'DONATION' | 'FIXED' | 'RESERVATION';

//for paginate data
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

interface ServiceOption {
    id: number;
    serviceId: number;
    id_code: string;
    category: string;
    title: string;
    tagline: string;
    card_color: string; 
    order: number;
    price_type: PriceType;
    price_value: number | null;
    min_donation: number | null;
    requires_custom_assessment: boolean;
}

interface ServiceOptionsIndexProps {
    // services: ServiceOption[];
    services: PaginatedData<ServiceOption>;
}


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


export default function Index({ services}: ServiceOptionsIndexProps) {
    const { flash } = usePage().props as any;

    const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Services',
        href: index().url,
    }
    ];

    const handleDelete = (optionId: number, title: string) => {
        if (window.confirm(`Are you sure you want to delete the option: "${title}"? This is permanent.`)) {
             router.delete(destroy(optionId), {
                onSuccess: () => {
                    console.log(`Option ${title} deleted successfully.`);
                },
                onError: (errors: any) => {
                    console.error("Deletion failed:", errors);
                }
            });
        }
    };

    const displayOptions = services.data;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Options for ${services.data[0]?.title || 'Service'}`} />
            <div className="container py-4 pl-4">
                <div className="flex flex-col gap-6 w-full ">
                  {flash?.success && (
                        <div key={flash.success} className="flex items-center gap-3 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm animate-fade-in">
                            <CheckCircle className="h-5 w-5 text-green-600" />
                            <p className="text-sm font-medium text-green-800">{flash.success}</p>
                        </div>
                    )}
                    
                    {flash?.error && (
                        <div key={flash.error} className="flex items-center gap-3 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm animate-fade-in">
                            <XCircle className="h-5 w-5 text-red-600" />
                            <p className="text-sm font-medium text-red-800">{flash.error}</p>
                        </div>
                    )}
 
                    {/* Header and Create Button */}
                    <div className="flex justify-between items-center mb-4">
                         <Link 
                            href={index().url}
                            className="text-gray-600 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center shadow-sm"
                        >
                            <CornerUpLeft className="mr-2 h-4 w-4" />
                            Back to Services
                        </Link>

                          <Link 
                            href={create().url}
                            className="text-gray-600 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center shadow-sm"
                        >
                         <PlusCircle className="mr-2 h-4 w-4" />
                          Create New Option
                        </Link>
                       
                    </div>

                    {/* Options Table */}
                    <div className="p-3 border rounded-xl bg-white shadow-xl overflow-x-auto">
                        <Table className="min-w-full">
                            <TableCaption>List of available  services</TableCaption>
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
                                {displayOptions.length > 0 ? (
                                    displayOptions.map((option) => (
                                        <TableRow 
                                            key={option.id} 
                                            // Dynamic left border using the `card_color` value
                                            className={`hover:bg-indigo-50/50 transition-colors border-l-4 ${option.card_color.replace('border-l-', 'border-')} hover:border-r-4`}
                                        >
                                            <TableCell className="font-bold text-lg">{option.order}</TableCell>
                                            <TableCell className="font-mono text-xs text-gray-500">{option.id_code}</TableCell>
                                            <TableCell>
                                                <div className="font-semibold text-base text-indigo-800">{option.title}</div>
                                                <div className="text-sm text-gray-500">{option.tagline}</div>
                                            </TableCell>
                                            
                                            {/* Pricing Column */}
                                            <TableCell className="text-center">
                                                {getPriceDisplay(option)}
                                            </TableCell>

                                            {/* Custom Assessment Column */}
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
                                                <span className="capitalize text-xs font-medium text-gray-700 bg-gray-200 px-2 py-0.5 rounded-full shadow-inner">
                                                    {option.category}
                                                </span>
                                            </TableCell>
                                            
                                            {/* Action Buttons */}
                                            <TableCell className="text-center flex space-x-2 justify-center">
                                                
                                                {/* Edit Button */}
                                                <Button 
                                                    variant="outline" 
                                                    size="icon" 
                                                    className="h-8 w-8 hover:bg-indigo-100 border-indigo-300 text-indigo-600 transition-transform hover:scale-105"
                                                    onClick={() => edit(option.id).url && router.visit(edit(option.id).url)}
                                                >
                                                    <Pencil className="h-4 w-4" />
                                                </Button>
                                                
                                                {/* Delete Button */}
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
                                    // Empty State Row
                                    <TableRow>
                                        <TableCell colSpan={7} className="h-24 text-center text-muted-foreground bg-gray-50/50">
                                            <AlertTriangle className="h-6 w-6 inline-block mr-2 text-yellow-500" />
                                            No services found. Click "Create New Option" to begin.
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
