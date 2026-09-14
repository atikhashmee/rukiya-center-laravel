import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { index } from '@/routes/products';
import { ProductFormProps } from '@/types/product';
import ProductForm from './form';
import { ArrowLeft } from 'lucide-react';
import { Button } from '@/components/ui/button';
import PageHeader from '@/components/page-header';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Products', href: index().url },
    { title: 'Edit', href: '#' },
];

export default function Edit(props: ProductFormProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Product" />
            <div className="mx-auto flex w-full max-w-3xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title={<><span className="text-muted-foreground">Editing:</span> {props.product?.name || 'Product'}</>}
                    actions={
                        <Button variant="outline" asChild>
                            <Link href={index().url}>
                                <ArrowLeft className="h-4 w-4" /> Back to Products
                            </Link>
                        </Button>
                    }
                />

                <div className="rounded-xl border bg-card p-5 text-card-foreground shadow-sm md:p-6">
                    <ProductForm {...props} breadcrumbs={breadcrumbs} />
                </div>
            </div>
        </AppLayout>
    );
}
