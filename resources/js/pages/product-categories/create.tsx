import React from 'react';
import AppLayout from "@/layouts/app-layout";
import { Head, Link } from '@inertiajs/react';
import { BreadcrumbItem } from "@/types";
import { dashboard } from '@/routes';
import { create, index } from '@/routes/productCategories';
import CategoryForm from './form';
import { ArrowLeft } from 'lucide-react';
import { Button } from '@/components/ui/button';
import PageHeader from '@/components/page-header';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Categories', href: index().url },
    { title: 'Create', href: create().url },
];

export default function Create() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Category" />
            <div className="mx-auto flex w-full max-w-3xl flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    title="Create New Category"
                    actions={
                        <Button variant="outline" asChild>
                            <Link href={index().url}>
                                <ArrowLeft className="h-4 w-4" /> Back to Categories
                            </Link>
                        </Button>
                    }
                />

                <div className="rounded-xl border bg-card p-5 text-card-foreground shadow-sm md:p-6">
                    <CategoryForm />
                </div>
            </div>
        </AppLayout>
    );
}
