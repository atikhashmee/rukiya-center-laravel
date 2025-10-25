import { dashboard } from '@/routes';
import ProductForm from './form';
import { ProductFormProps } from '@/types/product';
import { BreadcrumbItem } from "@/types";
import { create, index } from '@/routes/products';   
import AppLayout from "@/layouts/app-layout";
import { Head, useForm, router, Form } from '@inertiajs/react';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Products',
        href: index().url,
    },
    {
        title: 'Create',
        href: create().url,
    },
];

export default function Create(props: ProductFormProps) {
    return <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Create Product' />
            <div className="container p-4">
                <div className='grid grid-cols-1 md:grid-cols-12 gap-4'>
                    <div className='p-3 border rounded-lg bg-[var(--background)] md:col-span-8'>
                        <ProductForm {...props} breadcrumbs={breadcrumbs} />
                    </div>
                    <div className='p-3 border rounded-lg bg-[var(--background)] md:col-span-4'></div>
                </div>
            </div>
    </AppLayout>;
}